<?php

namespace App\Actions\Kanban;

use App\Models\Kanban\JobKanban;
use App\Models\Kanban\KanbanDepartment;
use App\Models\User;
use App\Enums\Kanban\JobStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Exception;

class TransitionJobStatusAction
{
    /**
     * Persetujuan (Agree) dari Need Review -> To Be Scheduled
     * Aturan: Jika data dari API, HANYA orang dengan role QA / departemen QA yang berhak.
     */
    public function agree(JobKanban $job, ?string $note = null, ?int $userId = null, ?int $areaId = null): JobKanban
    {
        $userId = $userId ?: Auth::id();
        $user = User::find($userId);

        if ($job->status !== JobStatus::NEED_REVIEW) {
            throw new Exception("Hanya job berstatus Need Review yang dapat disetujui.");
        }

        // Pengecekan otorisasi QA untuk job yang berasal dari API
        if ($job->isFromApi()) {
            if (!$user || !$user->isQa()) {
                throw new AuthorizationException(
                    "Hanya orang dengan role QA atau departemen QA yang berhak mereview data dari API dan memindahkannya ke To Be Scheduled."
                );
            }
        }

        return DB::connection('mysql_kanban')->transaction(function () use ($job, $note, $userId, $user, $areaId) {
            $fromStatus = $job->status;
            $toStatus = JobStatus::TO_BE_SCHEDULED;

            $updateData = [
                'status' => $toStatus,
                'last_stage_update' => Carbon::now(),
            ];

            if ($areaId) {
                $updateData['area_id'] = $areaId;
            }

            $job->update($updateData);

            $userName = $user ? $user->name : "User ID #{$userId}";
            $auditText = "REVIEW APPROVED by {$userName} (" . ($user && $user->isQa() ? "QA Team" : "Reviewer") . ") ➝ Pindah ke To Be Scheduled";
            if ($areaId) {
                $areaName = \App\Models\Kanban\KanbanArea::find($areaId)?->name;
                if ($areaName) {
                    $auditText .= "\nArea ditetapkan: " . $areaName;
                }
            }
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
     * Input Tanggal dari status To Be Scheduled (atau On Hold) -> Scheduled
     * Aturan: HANYA tim PPIC atau orang dengan role PPIC yang berhak mengatur jadwal / memindahkan dari To Be Scheduled.
     */
    public function setSchedule(JobKanban $job, string $startDate, string $deadline, ?string $note = null, ?int $userId = null): JobKanban
    {
        $userId = $userId ?: Auth::id();
        $user = User::find($userId);

        if (!in_array($job->status, [JobStatus::TO_BE_SCHEDULED, JobStatus::ON_HOLD, JobStatus::NEED_REVIEW])) {
            throw new Exception("Jadwal hanya dapat ditentukan saat job berstatus To Be Scheduled (atau Need Review).");
        }

        // Job dari API harus disetujui QA terlebih dahulu sebelum dijadwalkan
        if ($job->isFromApi() && $job->status === JobStatus::NEED_REVIEW) {
            throw new Exception("Job dari API harus ditinjau dan disetujui (Agree) oleh tim QA terlebih dahulu sebelum dapat dijadwalkan.");
        }

        // Pengecekan otorisasi PPIC untuk memindahkan dari To Be Scheduled
        if ($job->status === JobStatus::TO_BE_SCHEDULED) {
            if (!$user || !$user->isPpic()) {
                throw new AuthorizationException(
                    "Hanya tim PPIC atau orang dengan role PPIC yang berhak mengatur jadwal dan memindahkan job dari To Be Scheduled."
                );
            }
        }

        return DB::connection('mysql_kanban')->transaction(function () use ($job, $startDate, $deadline, $note, $userId, $user) {
            $fromStatus = $job->status;
            $toStatus = JobStatus::SCHEDULED;

            $job->update([
                'tanggal_job_mulai' => $startDate,
                'deadline' => $deadline,
                'status' => $toStatus,
                'last_stage_update' => Carbon::now(),
            ]);

            $userName = $user ? $user->name : "User ID #{$userId}";
            $text = "SCHEDULED by PPIC ({$userName}): Tanggal Mulai: {$startDate}, Deadline: {$deadline}";
            if ($note) {
                $text .= "\nCatatan: " . $note;
            }

            $currentDeptId = $job->latestRoute?->to_department_id;

            $job->routes()->create([
                'from_department_id' => $currentDeptId,
                'to_department_id' => $currentDeptId,
                'from_status' => $fromStatus,
                'to_status' => $toStatus,
                'note' => $text,
                'created_by' => $userId,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return $job->fresh();
        });
    }

    /**
     * Mengembalikan tiket mundur ke Need Review
     */
    public function returnToNeedReview(JobKanban $job, string $reason, ?int $userId = null): JobKanban
    {
        $userId = $userId ?: Auth::id();

        return DB::connection('mysql_kanban')->transaction(function () use ($job, $reason, $userId) {
            $fromStatus = $job->status;
            $toStatus = JobStatus::NEED_REVIEW;

            $job->update([
                'status' => $toStatus,
                'last_stage_update' => Carbon::now(),
            ]);

            $noteText = "RETURNED TO NEED REVIEW: Job dikembalikan ke Need Review.\nAlasan: " . $reason;

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
     * Legacy method alias: returnToOnHold
     */
    public function returnToOnHold(JobKanban $job, string $reason, ?int $userId = null): JobKanban
    {
        return $this->returnToNeedReview($job, $reason, $userId);
    }

    /**
     * Memundurkan tiket dari Scheduled atau On Going kembali ke Need Review / To Be Scheduled
     */
    public function reReview(JobKanban $job, string $reason, ?int $userId = null): JobKanban
    {
        $userId = $userId ?: Auth::id();

        if (!in_array($job->status, [JobStatus::SCHEDULED, JobStatus::PREPARATION, JobStatus::ON_GOING, JobStatus::TO_BE_SCHEDULED])) {
            throw new Exception("Job pada status {$job->status} tidak dapat dimundurkan ke Need Review.");
        }

        return DB::connection('mysql_kanban')->transaction(function () use ($job, $reason, $userId) {
            $fromStatus = $job->status;
            $toStatus = JobStatus::NEED_REVIEW;

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
     * Perpindahan status standar (Scheduled -> On Going, dll)
     */
    public function moveStage(JobKanban $job, string $newStatus, ?int $toDepartmentId = null, ?string $note = null, ?int $userId = null): JobKanban
    {
        $userId = $userId ?: Auth::id();
        $user = User::find($userId);

        if (in_array($job->status, [JobStatus::COMPLETED, JobStatus::CLOSED, JobStatus::CANCELLED])) {
            throw new Exception("Pekerjaan dengan status " . JobStatus::label($job->status) . " tidak dapat diubah statusnya.");
        }

        if ($job->status === JobStatus::NEED_REVIEW && !in_array($newStatus, [JobStatus::TO_BE_SCHEDULED, JobStatus::CANCELLED])) {
            throw new Exception("Job berstatus Need Review harus ditinjau dan disetujui (Agree) terlebih dahulu.");
        }

        // Jika berpindah dari To Be Scheduled, harus PPIC
        if ($job->status === JobStatus::TO_BE_SCHEDULED) {
            if (!$user || !$user->isPpic()) {
                throw new AuthorizationException(
                    "Hanya tim PPIC atau orang dengan role PPIC yang berhak memindahkan job dari To Be Scheduled."
                );
            }
        }

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
