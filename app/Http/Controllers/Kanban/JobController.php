<?php

namespace App\Http\Controllers\Kanban;

use App\Http\Controllers\Controller;
use App\Events\Kanban\KanbanJobUpdated;
use App\Models\Kanban\JobKanban;
use App\Models\Kanban\KanbanDepartment;
use App\Models\Kanban\KanbanArea;
use App\Models\Kanban\KanbanItem;
use App\Models\Kanban\KanbanUser;
use App\Models\User;
use App\Actions\Kanban\CreateKanbanJobAction;
use App\Actions\Kanban\SplitKanbanJobAction;
use App\Actions\Kanban\TransitionJobStatusAction;
use App\Actions\Kanban\ToggleItemStatusAction;
use App\Enums\Kanban\JobStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Jobs\Kanban\SendKanbanJobCompletedEmail;
use App\Mail\Kanban\KanbanJobCancelledNotification;
use App\Mail\Kanban\KanbanJobAutoClosedNotification;

class JobController extends Controller
{
    /**
     * Menampilkan halaman utama Job Board (Kanban).
     */
    public function index()
    {
        $user = Auth::user();

        // Sembunyikan job yang sudah CLOSED lebih dari 3 hari kerja
        $hideDate = Carbon::now()->subWeekdays(3)->startOfDay();

        $jobs = JobKanban::with([
            'pengaju',
            'pic',
            'area',
            'latestRoute.toDepartment',
            'latestRoute.creator',
            'notes',
            'items',
            'parent',
            'children',
        ])
            ->where(function ($query) use ($hideDate) {
                $query->where('status', '!=', JobStatus::CLOSED)
                    ->orWhere('closed_at', '>=', $hideDate);
            })
            ->latest()
            ->get();

        // Filtering status untuk kolom Kanban
        $onHoldJobs = $jobs->where('status', JobStatus::ON_HOLD);
        $needReviewJobs = $jobs->where('status', JobStatus::NEED_REVIEW);
        $scheduledJobs = $jobs->where('status', JobStatus::SCHEDULED);
        $preparationJobs = $jobs->where('status', JobStatus::PREPARATION);
        $onGoingJobs = $jobs->where('status', JobStatus::ON_GOING);
        $completedJobs = $jobs->where('status', JobStatus::COMPLETED);
        $closedJobs = $jobs->where('status', JobStatus::CLOSED);

        $departments = KanbanDepartment::pluck('department_name', 'id');
        $areas = KanbanArea::pluck('name', 'id');

        return view('kanban.jobs.index', compact(
            'onHoldJobs',
            'needReviewJobs',
            'scheduledJobs',
            'preparationJobs',
            'onGoingJobs',
            'completedJobs',
            'closedJobs',
            'user',
            'departments',
            'areas'
        ));
    }

    /**
     * Membuat Job Baru via Web UI.
     */
    public function store(Request $request, CreateKanbanJobAction $createAction)
    {
        $request->validate([
            'area_id' => 'required|exists:mysql_kanban.kanban_areas,id',
            'list_job' => 'required|string',
            'to_department_id' => 'required|exists:mysql_kanban.kanban_departments,id',
            'reason_description' => 'nullable|string',
            'remark' => 'nullable|string',
            'balance' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date|after_or_equal:start_date',
            'note' => 'nullable|string|max:500',
            'items' => 'nullable|array',
            'items.*' => 'nullable|string|max:255',
            'attachments' => 'nullable|array|max:3',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        $items = [];
        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $itemName) {
                if (!empty(trim($itemName))) {
                    $items[] = ['item_name' => trim($itemName)];
                }
            }
        }

        $payload = [
            'area_id' => $request->area_id,
            'to_department_id' => $request->to_department_id,
            'list_job' => $request->list_job,
            'reason_description' => $request->reason_description ?: $request->list_job,
            'remark' => $request->remark,
            'balance' => $request->balance,
            'start_date' => $request->start_date,
            'deadline' => $request->deadline,
            'note' => $request->note,
            'items' => $items,
        ];

        $job = $createAction->execute($payload, Auth::id());

        // Lampiran Awal jika ada
        if ($request->hasFile('attachments')) {
            $this->handleAttachments($request, $job, $job->latestRoute?->id);
        }

        return $this->prepareJobResponse($job, 'Job created successfully!');
    }

    /**
     * Input Tanggal dari On Hold -> Need Review.
     */
    public function setSchedule(Request $request, JobKanban $job, TransitionJobStatusAction $transitionAction)
    {
        $request->validate([
            'start_date' => 'required|date',
            'deadline' => 'required|date|after_or_equal:start_date',
            'note' => 'nullable|string|max:500',
        ]);

        try {
            $updatedJob = $transitionAction->setSchedule(
                $job,
                $request->start_date,
                $request->deadline,
                $request->note,
                Auth::id()
            );

            return $this->prepareJobResponse($updatedJob, 'Jadwal berhasil diinput. Job berpindah ke Need Review.');
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Setujui Job di Need Review -> Scheduled.
     */
    public function agree(Request $request, JobKanban $job, TransitionJobStatusAction $transitionAction)
    {
        $request->validate([
            'note' => 'nullable|string|max:500',
        ]);

        try {
            $updatedJob = $transitionAction->agree($job, $request->note, Auth::id());
            return $this->prepareJobResponse($updatedJob, 'Job disetujui dan dijadwalkan (Scheduled).');
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Kembalikan Job dari Need Review mundur ke On Hold.
     */
    public function returnToOnHold(Request $request, JobKanban $job, TransitionJobStatusAction $transitionAction)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            $updatedJob = $transitionAction->returnToOnHold($job, $request->reason, Auth::id());
            return $this->prepareJobResponse($updatedJob, 'Job dikembalikan ke On Hold.');
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Memundurkan Job dari Scheduled/On Going kembali ke Need Review (Re-Review).
     */
    public function reReview(Request $request, JobKanban $job, TransitionJobStatusAction $transitionAction)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            $updatedJob = $transitionAction->reReview($job, $request->reason, Auth::id());
            return $this->prepareJobResponse($updatedJob, 'Job dimundurkan ke Need Review.');
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Tandai atau cabut penanda Kendala (Issue) pada Job di status On Going.
     */
    public function toggleIssue(Request $request, JobKanban $job, TransitionJobStatusAction $transitionAction)
    {
        $request->validate([
            'has_issue' => 'required|boolean',
            'issue_note' => 'nullable|string|max:500',
        ]);

        try {
            $updatedJob = $transitionAction->toggleIssue(
                $job,
                (bool) $request->has_issue,
                $request->issue_note,
                Auth::id()
            );

            $msg = $request->has_issue ? 'Job ditandai memiliki kendala/isu.' : 'Kendala/isu pada job berhasil diselesaikan.';
            return $this->prepareJobResponse($updatedJob, $msg);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Pemecahan Job (Split Job) untuk item yang belum selesai.
     */
    public function split(Request $request, JobKanban $job, SplitKanbanJobAction $splitAction)
    {
        $request->validate([
            'target_department_id' => 'required|exists:mysql_kanban.kanban_departments,id',
            'target_area_id' => 'nullable|exists:mysql_kanban.kanban_areas,id',
            'reason_note' => 'nullable|string|max:500',
            'selected_item_ids' => 'nullable|array',
            'selected_item_ids.*' => 'integer|exists:mysql_kanban.kanban_items,id',
        ]);

        try {
            $result = $splitAction->execute(
                $job,
                (int) $request->target_department_id,
                $request->target_area_id ? (int) $request->target_area_id : null,
                $request->reason_note,
                $request->selected_item_ids ?: [],
                Auth::id()
            );

            // Broadcast real-time untuk kedua job (Induk dan Anak)
            $this->prepareJobResponse($result['parent'], 'Job induk diperbarui.');
            $childResponse = $this->prepareJobResponse($result['child'], 'Job anak berhasil dibuat di On Hold.');

            return response()->json([
                'status' => 'success',
                'message' => "Job berhasil di-split. Job baru: {$result['child']->id_job}",
                'parent' => $result['parent'],
                'child' => $result['child'],
                'html_child' => $childResponse->original['html'] ?? '',
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Menceklis status penyelesaian Item secara individual.
     */
    public function toggleItem(Request $request, JobKanban $job, KanbanItem $item, ToggleItemStatusAction $toggleItemAction)
    {
        $request->validate([
            'is_completed' => 'required|boolean',
        ]);

        if ($item->job_id !== $job->id) {
            return response()->json(['message' => 'Item tidak sesuai dengan Job.'], 404);
        }

        try {
            $updatedItem = $toggleItemAction->execute($item, (bool) $request->is_completed, Auth::id());
            $job->refresh();

            return response()->json([
                'status' => 'success',
                'item' => $updatedItem,
                'job_progress' => $job->progress_percentage,
                'balance' => $job->balance,
                'message' => 'Status item diperbarui.',
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function setPreparation(JobKanban $job, TransitionJobStatusAction $transitionAction)
    {
        try {
            $updatedJob = $transitionAction->moveStage($job, JobStatus::PREPARATION, null, 'Moved to Preparation stage', Auth::id());
            return $this->prepareJobResponse($updatedJob, 'Job moved to Preparation.');
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function start(JobKanban $job, TransitionJobStatusAction $transitionAction)
    {
        try {
            $updatedJob = $transitionAction->moveStage($job, JobStatus::ON_GOING, null, 'Work in Progress started', Auth::id());
            return $this->prepareJobResponse($updatedJob, 'Job moved to On Going.');
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Ganti Status umum (Maju Stage).
     */
    public function changeStatus(Request $request, JobKanban $job, TransitionJobStatusAction $transitionAction)
    {
        $request->validate([
            'status' => 'required|in:scheduled,preparation,on_going',
            'to_department_id' => 'nullable|exists:mysql_kanban.kanban_departments,id',
            'note' => 'required|string|max:1000',
            'attachments' => 'nullable|array|max:3',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        try {
            $updatedJob = $transitionAction->moveStage(
                $job,
                $request->status,
                $request->to_department_id ? (int) $request->to_department_id : null,
                $request->note,
                Auth::id()
            );

            if ($request->hasFile('attachments')) {
                $this->handleAttachments($request, $updatedJob, $updatedJob->latestRoute?->id);
            }

            return $this->prepareJobResponse($updatedJob, 'Status job diperbarui.');
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Forward Job ke departemen lain tanpa ganti status.
     */
    public function forward(Request $request, JobKanban $job)
    {
        $request->validate([
            'to_department_id' => 'required|exists:mysql_kanban.kanban_departments,id',
            'note' => 'required|string|max:500',
            'attachments' => 'nullable|array|max:3',
        ]);

        $oldRoute = $job->latestRoute;
        $fromDeptName = $oldRoute->toDepartment->department_name ?? 'Initial';
        $toDeptName = KanbanDepartment::find($request->to_department_id)?->department_name ?? 'Department';

        $job->update(['last_stage_update' => Carbon::now()]);

        $route = $job->routes()->create([
            'from_department_id' => $oldRoute?->to_department_id,
            'to_department_id' => $request->to_department_id,
            'from_status' => $job->status,
            'to_status' => $job->status,
            'note' => "DEPARTMENT MOVE: {$fromDeptName} ➝ {$toDeptName}\nNote: " . $request->note,
            'created_by' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $this->handleAttachments($request, $job, $route->id);

        return $this->prepareJobResponse($job, 'Job forwarded to ' . $toDeptName);
    }

    /**
     * Selesaikan Job (Complete).
     */
    public function complete(Request $request, JobKanban $job)
    {
        $request->validate(['note' => 'required|string', 'attachments' => 'nullable|array']);

        $job->update([
            'status' => JobStatus::COMPLETED,
            'tanggal_job_selesai' => Carbon::now(),
            'last_stage_update' => Carbon::now(),
        ]);

        $latestRouteId = $job->latestRoute?->id;

        $job->notes()->create([
            'job_id' => $job->id,
            'job_route_id' => $latestRouteId,
            'note' => "COMPLETED: Job marked as done by " . Auth::user()->name . ".\nNote: " . $request->note,
            'created_by' => Auth::id(),
        ]);

        $this->handleAttachments($request, $job, $latestRouteId);

        // Kirim Email Notifikasi via Queue
        SendKanbanJobCompletedEmail::dispatch($job);

        return $this->prepareJobResponse($job, 'Job marked as completed!');
    }

    /**
     * Tutup dan arsipkan Job (Closed).
     */
    public function close(Request $request, JobKanban $job)
    {
        DB::connection('mysql_kanban')->transaction(function () use ($job) {
            $job->update([
                'status' => JobStatus::CLOSED,
                'penutup_id' => Auth::id(),
                'closed_at' => Carbon::now(),
            ]);

            $job->notes()->create([
                'job_id' => $job->id,
                'note' => "JOB CLOSED manual by " . Auth::user()->name,
                'created_by' => Auth::id(),
            ]);
        });

        $this->notifyAllParties($job);

        return $this->prepareJobResponse($job, 'Job closed and archived.');
    }

    /**
     * Batalkan Job (Cancel).
     */
    public function cancel(Request $request, JobKanban $job)
    {
        $user = Auth::user();

        if ($job->pengaju_id !== $user->id) {
            return response()->json(['message' => 'Hanya pengaju yang berhak membatalkan job.'], 403);
        }

        $request->validate(['reason' => 'required|string|max:500']);

        $job->update([
            'status' => JobStatus::CANCELLED,
            'cancellation_reason' => $request->reason,
            'closed_at' => Carbon::now(),
        ]);

        $job->notes()->create([
            'job_id' => $job->id,
            'job_route_id' => $job->latestRoute?->id,
            'note' => "JOB CANCELLED by Requester.\nReason: " . $request->reason,
            'created_by' => $user->id,
        ]);

        $currentDeptId = $job->latestRoute?->to_department_id;
        if ($currentDeptId) {
            $userIds = KanbanUser::where('kanban_department_id', $currentDeptId)->pluck('user_id');
            $recipients = User::whereIn('id', $userIds)->get();

            foreach ($recipients as $recipient) {
                Mail::to($recipient->email)->send(
                    new KanbanJobCancelledNotification($job, $request->reason, $user->name)
                );
            }
        }

        return $this->prepareJobResponse($job, 'Job cancelled.');
    }

    /**
     * Detail Modal Content (Timeline, Items, Lineage).
     */
    public function showDetails(JobKanban $job)
    {
        $job->load([
            'routes.fromDepartment',
            'routes.toDepartment',
            'routes.creator',
            'notes.creator',
            'attachments',
            'items',
            'parent',
            'children',
            'pengaju',
            'pic',
            'area',
        ]);

        $activities = collect();
        $allAttachments = $job->attachments;

        foreach ($job->routes as $route) {
            $relatedFiles = $allAttachments->filter(function ($att) use ($route) {
                return $att->job_route_id == $route->id;
            });

            $activities->push([
                'type' => 'route',
                'timestamp' => $route->created_at,
                'creator' => $route->creator,
                'data' => $route,
                'files' => $relatedFiles,
            ]);
        }

        foreach ($job->notes as $note) {
            $relatedFiles = $allAttachments->filter(function ($att) use ($note) {
                return $att->job_route_id == $note->job_route_id;
            });

            $activities->push([
                'type' => 'note',
                'timestamp' => $note->created_at,
                'creator' => $note->creator,
                'data' => $note,
                'files' => $relatedFiles,
            ]);
        }

        $activities = $activities->sortByDesc('timestamp')->values();

        $html = View::make('kanban.jobs.partials.job_detail_content', compact('job', 'activities'))->render();

        return response()->json(['html' => $html]);
    }

    private function notifyAllParties($job)
    {
        $involvedUsers = collect();

        if ($job->pengaju) {
            $involvedUsers->push($job->pengaju);
        }

        $deptIds = $job->routes()->pluck('to_department_id')->filter()->unique();

        if ($deptIds->isNotEmpty()) {
            $userIds = KanbanUser::whereIn('kanban_department_id', $deptIds)->pluck('user_id');
            $usersInDepts = User::whereIn('id', $userIds)->get();
            $involvedUsers = $involvedUsers->merge($usersInDepts);
        }

        foreach ($involvedUsers->unique('id') as $recipient) {
            Mail::to($recipient->email)->send(new KanbanJobAutoClosedNotification($job, $recipient));
        }
    }

    private function prepareJobResponse(JobKanban $job, string $message)
    {
        $job->load([
            'pengaju',
            'pic',
            'area',
            'latestRoute.toDepartment',
            'routes.fromDepartment',
            'routes.toDepartment',
            'attachments',
            'notes.creator',
            'items',
            'parent',
            'children',
        ]);

        $html = View::make('kanban.jobs.partials.job_card', ['job' => $job])->render();

        KanbanJobUpdated::dispatch($job, $html);

        return response()->json(['job' => $job, 'html' => $html, 'message' => $message]);
    }

    private function handleAttachments($request, $job, $routeId)
    {
        if ($request->hasFile('attachments')) {
            $attachmentNumber = $job->attachments()->count() + 1;
            foreach ($request->file('attachments') as $file) {
                $newFileName = "{$job->id_job}_{$attachmentNumber}_" . time() . "." . $file->getClientOriginalExtension();
                $path = $file->storeAs('kanban_attachments', $newFileName, 'public');

                $job->attachments()->create([
                    'job_id' => $job->id,
                    'job_route_id' => $routeId,
                    'file_path' => $path,
                    'file_name' => $newFileName,
                    'uploaded_by' => Auth::id(),
                ]);
                $attachmentNumber++;
            }
        }
    }
}
