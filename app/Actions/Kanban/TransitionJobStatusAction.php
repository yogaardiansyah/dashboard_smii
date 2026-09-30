<?php

namespace App\Actions\Kanban;

use App\Models\Kanban\JobKanban;
use App\Models\Kanban\KanbanDepartment;
use App\Enums\Kanban\JobStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class TransitionJobStatusAction
{
    /**
     * Input Tanggal dari status On Hold -> Need Review
     */
    public function setSchedule(JobKanban $job, string $startDate, string $deadline, ?string $note = null, ?int $userId = null): JobKanban
    {
        $userId = $userId ?: Auth::id();

        if ($job->status !== JobStatus::ON_HOLD) {
            throw new Exception("Jadwal hanya dapat ditentukan saat job berstatus On Hold.");
        }

        return DB::connection('mysql_kanban')->transaction(function () use ($job, $startDate, $deadline, $note, $userId) {
            $fromStatus = $job->status;
            $toStatus = JobStatus::NEED_REVIEW;

            $job->update([
                'tanggal_job_mulai' => $startDate,
                'deadline' => $deadline,
                'status' => $toStatus,
                'last_stage_update' => Carbon::now(),
            ]);

            $text = "SCHEDULE SUBMITTED: Tanggal Mulai: {$startDate}, Deadline: {$deadline}";
            if ($note) {
                $text .= "\nCatatan: " . $note;
            }

            $job->notes()->create([
                'job_id' => $job->id,
                'job_route_id' => $job->latestRoute?->id,
                'note' => $text,
                'created_by' => $userId,
            ]);

            return $job->fresh();
        });
    }

    /**
     * Persetujuan (Agree) dari Need Review -> Scheduled
     */
    public function agree(JobKanban $job, ?string $note = null, ?int $userId = null): JobKanban
    {
        $userId = $userId ?: Auth::id();

        if ($job->status !== JobStatus::NEED_REVIEW) {
            throw new Exception("Hanya job berstatus Need Review yang dapat disetujui.");
        }

        return DB::connection('mysql_kanban')->transaction(function () use ($job, $note, $userId) {
            $fromStatus = $job->status;
            $toStatus = JobStatus::SCHEDULED;

            $job->update([
                'status' => $toStatus,
                'last_stage_update' => Carbon::now(),
            ]);

            $auditText = "AGREED & SCHEDULED by User ID #{$userId}";
            if ($note) {
                $auditText .= "\nCatatan: " . $note;
            }

            $currentDeptId = $job->latestRoute?->to_department_id;

            $job->routes()->create([
                'from_department_id' => $currentDeptId,
                'to_department_id' => $currentDeptId,
                'from_status' => $fromStatus,
                'to_status' => $toStatus,
                'note' => $auditText,
                'created_by' => $userId,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return $job->fresh();
        });
    }

    /**
     * Mengembalikan tiket dari Need Review mundur ke On Hold (Revisi)
     */
    public function returnToOnHold(JobKanban $job, string $reason, ?int $userId = null): JobKanban
    {
        $userId = $userId ?: Auth::id();

        if ($job->status !== JobStatus::NEED_REVIEW) {
            throw new Exception("Hanya job berstatus Need Review yang dapat dikembalikan ke On Hold.");
        }

        return DB::connection('mysql_kanban')->transaction(function () use ($job, $reason, $userId) {
            $fromStatus = $job->status;
            $toStatus = JobStatus::ON_HOLD;

            // Tanggal tetap dipertahankan
            $job->update([
                'status' => $toStatus,
                'last_stage_update' => Carbon::now(),
            ]);

            $noteText = "RETURNED TO ON HOLD: Job dikembalikan ke On Hold.\nAlasan: " . $reason;

            $job->notes()->create([
                'job_id' => $job->id,
                'job_route_id' => $job->latestRoute?->id,
                'note' => $noteText,
                'created_by' => $userId,
            ]);

            return $job->fresh();
        });
    }

    /**
     * Memundurkan tiket dari Scheduled atau On Going kembali ke Need Review
     */
    public function reReview(JobKanban $job, string $reason, ?int $userId = null): JobKanban
    {
        $userId = $userId ?: Auth::id();

        if (!in_array($job->status, [JobStatus::SCHEDULED, JobStatus::PREPARATION, JobStatus::ON_GOING])) {
            throw new Exception("Job pada status {$job->status} tidak dapat dimundurkan ke Need Review.");
        }

        return DB::connection('mysql_kanban')->transaction(function () use ($job, $reason, $userId) {
            $fromStatus = $job->status;
            $toStatus = JobStatus::NEED_REVIEW;

            // Tanggal jadwal TETAP DIPERTAHANKAN (tidak diubah)
            $job->update([
                'status' => $toStatus,
                'last_stage_update' => Carbon::now(),
            ]);

            $noteText = "RE-REVIEW REQUESTED: Status dimundurkan dari " . JobStatus::label($fromStatus) . " ke Need Review.\nAlasan Peninjauan: " . $reason;

            $currentDeptId = $job->latestRoute?->to_department_id;

            $job->routes()->create([
                'from_department_id' => $currentDeptId,
                'to_department_id' => $currentDeptId,
                'from_status' => $fromStatus,
                'to_status' => $toStatus,
                'note' => $noteText,
                'created_by' => $userId,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return $job->fresh();
        });
    }

    /**
     * Menandai atau mencabut kendala/isu pada Job
     */
    public function toggleIssue(JobKanban $job, bool $hasIssue, ?string $issueNote = null, ?int $userId = null): JobKanban
    {
        $userId = $userId ?: Auth::id();

        return DB::connection('mysql_kanban')->transaction(function () use ($job, $hasIssue, $issueNote, $userId) {
            $job->update([
                'has_issue' => $hasIssue,
                'issue_note' => $hasIssue ? $issueNote : null,
                'last_stage_update' => Carbon::now(),
            ]);

            $actionText = $hasIssue ? "ISSUE REPORTED: " . ($issueNote ?: 'Pekerjaan terkendala') : "ISSUE RESOLVED";

            $job->notes()->create([
                'job_id' => $job->id,
                'job_route_id' => $job->latestRoute?->id,
                'note' => $actionText,
                'created_by' => $userId,
            ]);

            return $job->fresh();
        });
    }

    /**
     * Perpindahan status standar (Preparation, On Going, dll)
     */
    public function moveStage(JobKanban $job, string $newStatus, ?int $toDepartmentId = null, ?string $note = null, ?int $userId = null): JobKanban
    {
        $userId = $userId ?: Auth::id();

        return DB::connection('mysql_kanban')->transaction(function () use ($job, $newStatus, $toDepartmentId, $note, $userId) {
            $oldStatus = $job->status;
            $currentDeptId = $job->latestRoute?->to_department_id;
            $targetDeptId = $toDepartmentId ?: $currentDeptId;

            $job->update([
                'status' => $newStatus,
                'last_stage_update' => Carbon::now(),
            ]);

            $isDeptChange = $targetDeptId && $targetDeptId != $currentDeptId;
            $statusChangeText = "STATUS CHANGE: " . JobStatus::label($oldStatus) . " ➝ " . JobStatus::label($newStatus);

            if ($isDeptChange) {
                $fromDeptName = $job->latestRoute?->toDepartment?->department_name ?? 'Initial';
                $toDeptName = KanbanDepartment::find($targetDeptId)?->department_name ?? 'Department';
                $finalNote = "{$statusChangeText}\nDEPARTMENT MOVE: {$fromDeptName} ➝ {$toDeptName}\n\nNote: " . ($note ?: '-');

                $job->routes()->create([
                    'from_department_id' => $currentDeptId,
                    'to_department_id' => $targetDeptId,
                    'from_status' => $oldStatus,
                    'to_status' => $newStatus,
                    'note' => $finalNote,
                    'created_by' => $userId,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            } else {
                $finalNote = "{$statusChangeText}\n\nNote: " . ($note ?: '-');

                $job->notes()->create([
                    'job_id' => $job->id,
                    'job_route_id' => $job->latestRoute?->id,
                    'note' => $finalNote,
                    'created_by' => $userId,
                ]);
            }

            return $job->fresh();
        });
    }
}
