<?php

namespace App\Services\Marsho;

use App\Events\Marsho\JobUpdated;
use App\Jobs\Marsho\SendJobCompletedEmail;
use App\Mail\Marsho\JobAutoClosedNotification;
use App\Mail\Marsho\JobCancelledNotification;
use App\Models\Marsho\Area;
use App\Models\Marsho\JobMarsho;
use App\Models\Marsho\MarshoDepartment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class MarshoJobService
{
    /**
     * Retrieve and group all jobs for the Kanban board view.
     */
    public function getKanbanData(?User $user = null): array
    {
        $hideDate = Carbon::now()->subWeekdays(3)->startOfDay();

        $jobs = JobMarsho::with([
            'pengaju',
            'area',
            'latestRoute.toDepartment',
            'latestRoute.creator',
            'notes'
        ])
            ->where(function ($query) use ($hideDate) {
                $query->where('status', '!=', 'closed')
                    ->orWhere('closed_at', '>=', $hideDate);
            })
            ->latest()
            ->get();

        return [
            'toBeScheduledJobs' => $jobs->where('status', 'to_be_scheduled'),
            'scheduledJobs'     => $jobs->where('status', 'scheduled'),
            'preparationJobs'   => $jobs->where('status', 'preparation'),
            'onGoingJobs'       => $jobs->where('status', 'on_going'),
            'completedJobs'     => $jobs->where('status', 'completed'),
            'closedJobs'        => $jobs->where('status', 'closed'),
            'departments'       => MarshoDepartment::pluck('department_name', 'id'),
            'areas'             => Area::pluck('name', 'id'),
            'user'              => $user,
        ];
    }

    /**
     * Create a new Job with initial Route and optional attachments.
     */
    public function createJob(array $data, int $userId, array $files = []): JobMarsho
    {
        $jobIdString = JobMarsho::generateJobId();

        $status = Carbon::parse($data['start_date'])->isPast() || Carbon::parse($data['start_date'])->isToday()
            ? 'scheduled'
            : 'to_be_scheduled';

        return DB::connection('mysql_job')->transaction(function () use ($data, $userId, $jobIdString, $status, $files) {
            $job = JobMarsho::create([
                'id_job'            => $jobIdString,
                'pengaju_id'        => $userId,
                'area_id'           => $data['area_id'],
                'list_job'          => $data['list_job'],
                'tanggal_job_mulai' => $data['start_date'],
                'deadline'          => $data['deadline'] ?? null,
                'status'            => $status,
                'last_stage_update' => Carbon::now(),
            ]);

            $targetDept = MarshoDepartment::find($data['to_department_id']);
            $deptName = $targetDept ? $targetDept->department_name : 'Department #' . $data['to_department_id'];

            $route = $job->routes()->create([
                'to_department_id' => $data['to_department_id'],
                'note'             => "JOB CREATED. Assigned to: {$deptName}\nNote: " . ($data['note'] ?? '-'),
                'created_by'       => $userId,
            ]);

            if (!empty($files)) {
                $this->storeAttachments($job, $route->id, $files, $userId);
            }

            return $job;
        });
    }

    /**
     * Change Job status and optionally move to a new Department.
     */
    public function changeStatus(JobMarsho $job, string $newStatus, ?int $targetDeptId, string $note, int $userId, array $files = []): array
    {
        DB::connection('mysql_job')->transaction(function () use ($job, $newStatus, $targetDeptId, $note, $userId, $files) {
            $oldStatus = $job->status;

            $job->update([
                'status'            => $newStatus,
                'last_stage_update' => Carbon::now(),
            ]);

            $latestRoute = $job->latestRoute;
            $currentDeptId = $latestRoute ? $latestRoute->to_department_id : null;

            $statusChangeText = "STATUS CHANGE: " . ucfirst(str_replace('_', ' ', $oldStatus)) . " ➔ " . ucfirst(str_replace('_', ' ', $newStatus));

            if ($targetDeptId && $targetDeptId != $currentDeptId) {
                // Scenario A: Move Department + Change Status
                $fromDeptName = $latestRoute && $latestRoute->toDepartment ? $latestRoute->toDepartment->department_name : 'Initial';
                $targetDept = MarshoDepartment::find($targetDeptId);
                $toDeptName = $targetDept ? $targetDept->department_name : 'Department #' . $targetDeptId;

                $finalNote = "{$statusChangeText}\nDEPARTMENT MOVE: {$fromDeptName} ➔ {$toDeptName}\n\nNote: " . $note;

                $newRoute = $job->routes()->create([
                    'job_id'             => $job->id,
                    'from_department_id' => $currentDeptId,
                    'to_department_id'   => $targetDeptId,
                    'note'               => $finalNote,
                    'created_by'         => $userId,
                ]);

                if (!empty($files)) {
                    $this->storeAttachments($job, $newRoute->id, $files, $userId);
                }
            } else {
                // Scenario B: Same Department (Status only note)
                $finalNote = "{$statusChangeText}\n\nNote: " . $note;

                $job->notes()->create([
                    'job_id'       => $job->id,
                    'job_route_id' => $latestRoute ? $latestRoute->id : null,
                    'note'         => $finalNote,
                    'created_by'   => $userId,
                ]);

                if (!empty($files)) {
                    $this->storeAttachments($job, $latestRoute ? $latestRoute->id : null, $files, $userId);
                }
            }
        });

        $job->refresh();

        $message = 'Job moved to ' . ucfirst(str_replace('_', ' ', $newStatus));
        if ($targetDeptId && $targetDeptId != ($job->latestRoute->from_department_id ?? null)) {
            $message .= ' and forwarded to new department.';
        }

        return ['job' => $job, 'message' => $message];
    }

    /**
     * Forward Job to another department while keeping stage.
     */
    public function forwardJob(JobMarsho $job, int $targetDeptId, string $note, int $userId, array $files = []): string
    {
        $oldRoute = $job->latestRoute;
        $fromDeptName = $oldRoute && $oldRoute->toDepartment ? $oldRoute->toDepartment->department_name : 'Initial';
        $targetDept = MarshoDepartment::find($targetDeptId);
        $toDeptName = $targetDept ? $targetDept->department_name : 'Department #' . $targetDeptId;

        DB::connection('mysql_job')->transaction(function () use ($job, $oldRoute, $fromDeptName, $toDeptName, $targetDeptId, $note, $userId, $files) {
            $job->update(['last_stage_update' => Carbon::now()]);

            $route = $job->routes()->create([
                'job_id'             => $job->id,
                'from_department_id' => $oldRoute ? $oldRoute->to_department_id : null,
                'to_department_id'   => $targetDeptId,
                'note'               => "DEPARTMENT MOVE: {$fromDeptName} ➔ {$toDeptName}\nNote: " . $note,
                'created_by'         => $userId,
            ]);

            if (!empty($files)) {
                $this->storeAttachments($job, $route->id, $files, $userId);
            }
        });

        return 'Job forwarded to ' . $toDeptName;
    }

    /**
     * Mark Job as Completed and notify requester.
     */
    public function completeJob(JobMarsho $job, string $note, User $user, array $files = []): JobMarsho
    {
        DB::connection('mysql_job')->transaction(function () use ($job, $note, $user, $files) {
            $job->update([
                'status'              => 'completed',
                'tanggal_job_selesai' => Carbon::now(),
                'last_stage_update'   => Carbon::now(),
            ]);

            $latestRouteId = $job->latestRoute ? $job->latestRoute->id : null;

            $job->notes()->create([
                'job_id'       => $job->id,
                'job_route_id' => $latestRouteId,
                'note'         => "COMPLETED: Job marked as done by " . $user->name . ".\nNote: " . $note,
                'created_by'   => $user->id,
            ]);

            if (!empty($files)) {
                $this->storeAttachments($job, $latestRouteId, $files, $user->id);
            }
        });

        SendJobCompletedEmail::dispatch($job);

        return $job;
    }

    /**
     * Cancel Job by the Requester.
     */
    public function cancelJob(JobMarsho $job, string $reason, User $user): JobMarsho
    {
        DB::connection('mysql_job')->transaction(function () use ($job, $reason, $user) {
            $job->update([
                'status'              => 'cancelled',
                'cancellation_reason' => $reason,
                'closed_at'           => Carbon::now(),
            ]);

            $latestRouteId = $job->latestRoute ? $job->latestRoute->id : null;

            $job->notes()->create([
                'job_id'       => $job->id,
                'job_route_id' => $latestRouteId,
                'note'         => "JOB CANCELLED by Requester.\nReason: " . $reason,
                'created_by'   => $user->id,
            ]);
        });

        // Notify active department members
        $currentDeptId = $job->latestRoute->to_department_id ?? null;
        if ($currentDeptId) {
            $recipients = User::whereHas('marshoProfile', function ($q) use ($currentDeptId) {
                $q->where('marsho_department_id', $currentDeptId);
            })->get();

            foreach ($recipients as $recipient) {
                if ($recipient->email) {
                    Mail::to($recipient->email)->send(
                        new JobCancelledNotification($job, $reason, $user->name)
                    );
                }
            }
        }

        return $job;
    }

    /**
     * Close Job manually and notify all involved users.
     */
    public function closeJob(JobMarsho $job, User $user): JobMarsho
    {
        DB::connection('mysql_job')->transaction(function () use ($job, $user) {
            $job->update([
                'status'     => 'closed',
                'penutup_id' => $user->id,
                'closed_at'  => Carbon::now(),
            ]);

            $job->notes()->create([
                'job_id'     => $job->id,
                'note'       => "JOB CLOSED manual by " . $user->name,
                'created_by' => $user->id,
            ]);
        });

        $this->notifyAllParties($job);

        return $job;
    }

    /**
     * Get detailed timeline activities (routes + notes + associated attachments).
     */
    public function getJobTimeline(JobMarsho $job): Collection
    {
        $job->load([
            'routes.fromDepartment',
            'routes.toDepartment',
            'routes.creator',
            'notes.creator',
            'attachments',
        ]);

        $activities = collect();
        $allAttachments = $job->attachments;

        // 1. Process Routes (Department shifts)
        foreach ($job->routes as $route) {
            $relatedFiles = $allAttachments->filter(function ($att) use ($route) {
                return $att->job_route_id == $route->id &&
                    $att->created_at->diffInSeconds($route->created_at) <= 15;
            });

            $activities->push([
                'type'      => 'route',
                'timestamp' => $route->created_at,
                'creator'   => $route->creator,
                'data'      => $route,
                'files'     => $relatedFiles,
            ]);
        }

        // 2. Process Notes (Status changes / notes)
        foreach ($job->notes as $note) {
            $relatedFiles = $allAttachments->filter(function ($att) use ($note) {
                return $att->job_route_id == $note->job_route_id &&
                    $att->created_at->diffInSeconds($note->created_at) <= 15;
            });

            $activities->push([
                'type'      => 'note',
                'timestamp' => $note->created_at,
                'creator'   => $note->creator,
                'data'      => $note,
                'files'     => $relatedFiles,
            ]);
        }

        return $activities->sortByDesc('timestamp')->values();
    }

    /**
     * Prepare real-time response payload for Kanban board.
     */
    public function prepareJobResponse(JobMarsho $job, string $message): array
    {
        $job->load([
            'pengaju',
            'area',
            'latestRoute.toDepartment',
            'routes.fromDepartment',
            'routes.toDepartment',
            'attachments',
            'notes.creator',
        ]);

        $html = View::make('jobs.partials.job_card', ['job' => $job])->render();

        JobUpdated::dispatch($job, $html);

        return [
            'job'     => $job,
            'html'    => $html,
            'message' => $message,
        ];
    }

    /**
     * Store and associate uploaded files with the Job and Route.
     */
    public function storeAttachments(JobMarsho $job, ?int $routeId, array $files, int $userId): void
    {
        $attachmentNumber = $job->attachments()->count() + 1;

        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                $newFileName = "{$job->id_job}_{$attachmentNumber}_" . time() . "." . $file->getClientOriginalExtension();
                $path = $file->storeAs('job_attachments', $newFileName, 'public');

                $job->attachments()->create([
                    'job_id'       => $job->id,
                    'job_route_id' => $routeId,
                    'file_path'    => $path,
                    'file_name'    => $newFileName,
                    'uploaded_by'  => $userId,
                ]);

                $attachmentNumber++;
            }
        }
    }

    /**
     * Notify all involved users when a job is closed.
     */
    public function notifyAllParties(JobMarsho $job): void
    {
        $involvedUsers = collect();

        if ($job->pengaju) {
            $involvedUsers->push($job->pengaju);
        }

        $deptIds = $job->routes()->pluck('to_department_id')
            ->merge($job->routes()->pluck('from_department_id'))
            ->filter()->unique();

        if ($deptIds->isNotEmpty()) {
            $usersInDepts = User::whereHas('marshoProfile', function ($query) use ($deptIds) {
                $query->whereIn('marsho_department_id', $deptIds);
            })->get();

            $involvedUsers = $involvedUsers->merge($usersInDepts);
        }

        foreach ($involvedUsers->unique('id') as $recipient) {
            if ($recipient->email) {
                try {
                    Mail::to($recipient->email)->send(new JobAutoClosedNotification($job, $recipient));
                } catch (\Throwable $e) {
                    Log::error("Failed to notify user {$recipient->email} for Job {$job->id_job}: " . $e->getMessage());
                }
            }
        }
    }
}
