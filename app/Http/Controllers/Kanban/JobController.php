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
use App\Models\Item;
use App\Http\Requests\Kanban\StoreExternalJobRequest;
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
use Illuminate\Auth\Access\AuthorizationException;
use App\Jobs\Kanban\SendKanbanJobCompletedEmail;
use App\Jobs\Kanban\SendKanbanApiJobCompletedEmail;
use App\Jobs\Kanban\SendKanbanApiJobCreatedEmail;
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

        // Filtering status untuk 6 kolom Kanban alur baru:
        // Need Review -> To Be Scheduled -> Scheduled -> On Going -> Completed -> Closed
        $needReviewJobs = $jobs->where('status', JobStatus::NEED_REVIEW);
        $toBeScheduledJobs = $jobs->where('status', JobStatus::TO_BE_SCHEDULED);
        $scheduledJobs = $jobs->where('status', JobStatus::SCHEDULED);
        $onGoingJobs = $jobs->where('status', JobStatus::ON_GOING);
        $completedJobs = $jobs->where('status', JobStatus::COMPLETED);
        $closedJobs = $jobs->where('status', JobStatus::CLOSED);

        // Fallback untuk backward-compatibility jika ada data lama
        $onHoldJobs = $jobs->where('status', JobStatus::ON_HOLD);
        $preparationJobs = $jobs->where('status', JobStatus::PREPARATION);

        $departments = KanbanDepartment::pluck('department_name', 'id');
        $areas = KanbanArea::pluck('name', 'id');

        return view('kanban.jobs.index', compact(
            'needReviewJobs',
            'toBeScheduledJobs',
            'scheduledJobs',
            'onGoingJobs',
            'completedJobs',
            'closedJobs',
            'onHoldJobs',
            'preparationJobs',
            'user',
            'departments',
            'areas'
        ));
    }

    /**
     * Search item dari tabel inventories (ld_part, ld_lot, ld_qty_oh) dan fallback ke items.
     * Mendukung: 1 jenis item tapi balance banyak karena beda lot.
     */
    public function searchItems(Request $request)
    {
        $term = trim($request->input('q', ''));

        // 1. Cari di tabel inventories terlebih dahulu (tersedia lot & balance/kuantitas aktual)
        $invQuery = DB::connection('mysql')->table('inventories');
        if (!empty($term)) {
            $invQuery->where(function ($q) use ($term) {
                $q->where('ld_part', 'LIKE', "%{$term}%")
                  ->orWhere('pt_desc1', 'LIKE', "%{$term}%")
                  ->orWhere('ld_lot', 'LIKE', "%{$term}%");
            });
        }

        $invResults = $invQuery->select('id', 'ld_part', 'pt_desc1', 'ld_lot', 'ld_qty_oh', 'pt_um', 'ld_loc')
            ->orderBy('ld_part')
            ->orderBy('ld_lot')
            ->limit(40)
            ->get();

        if ($invResults->isNotEmpty()) {
            $items = $invResults->map(function ($item) {
                $qty = (int) $item->ld_qty_oh;
                $unit = $item->pt_um ? strtoupper(trim($item->pt_um)) : 'PCS';
                $lot = $item->ld_lot ? trim($item->ld_lot) : '';
                return [
                    'id' => $item->id,
                    'pt_part' => $item->ld_part,
                    'item_code' => $item->ld_part,
                    'description' => $item->pt_desc1,
                    'pt_desc1' => $item->pt_desc1,
                    'lot_number' => $lot,
                    'qty' => $qty > 0 ? $qty : 1,
                    'unit' => $unit,
                    'location' => $item->ld_loc,
                    'label' => "[{$item->ld_part}] {$item->pt_desc1}" . ($lot ? " | Lot: {$lot}" : '') . " (Saldo: {$qty} {$unit})",
                ];
            });

            return response()->json($items);
        }

        // 2. Fallback ke tabel master items jika di inventory tidak ada
        $query = DB::connection('mysql')->table('items');

        if (!empty($term)) {
            $query->where(function ($q) use ($term) {
                $q->where('pt_part', 'LIKE', "%{$term}%")
                  ->orWhere('pt_desc1', 'LIKE', "%{$term}%")
                  ->orWhere('pt_desc2', 'LIKE', "%{$term}%");
            });
        }

        $items = $query->select('id', 'pt_part', 'pt_desc1', 'pt_desc2', 'pt_um', 'pt_prod_line')
            ->orderBy('pt_part')
            ->limit(30)
            ->get()
            ->map(function ($item) {
                $desc = trim(($item->pt_desc1 ?? '') . ' ' . ($item->pt_desc2 ?? ''));
                $unit = $item->pt_um ? strtoupper(trim($item->pt_um)) : '';
                return [
                    'id' => $item->id,
                    'pt_part' => $item->pt_part,
                    'item_code' => $item->pt_part,
                    'pt_desc1' => $item->pt_desc1,
                    'pt_desc2' => $item->pt_desc2,
                    'description' => $desc,
                    'lot_number' => '',
                    'qty' => 1,
                    'unit' => $unit,
                    'label' => "[{$item->pt_part}] {$desc}" . ($unit ? " ({$unit})" : ''),
                ];
            });

        return response()->json($items);
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
            'items.*' => 'nullable',
            'attachments' => 'nullable|array|max:3',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        $items = [];
        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $rawItem) {
                if (is_array($rawItem)) {
                    $name = trim($rawItem['item_name'] ?? '');
                    if (!empty($name)) {
                        $items[] = [
                            'item_code' => !empty($rawItem['item_code']) ? trim($rawItem['item_code']) : null,
                            'item_name' => $name,
                            'lot_number' => !empty($rawItem['lot_number']) ? trim($rawItem['lot_number']) : null,
                            'qty' => isset($rawItem['qty']) && (int) $rawItem['qty'] > 0 ? (int) $rawItem['qty'] : 1,
                            'unit' => !empty($rawItem['unit']) ? trim($rawItem['unit']) : null,
                        ];
                    }
                } elseif (is_string($rawItem) && !empty(trim($rawItem))) {
                    $items[] = [
                        'item_name' => trim($rawItem),
                        'qty' => 1,
                    ];
                }
            }
        }

        $payload = [
            'area_id' => $request->area_id,
            'to_department_id' => $request->to_department_id,
            'source' => 'manual',
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
     * Submit job dari sistem eksternal (misal: submit perubahan dari Inventory Oil).
     * Secara default masuk ke tahap Need Review.
     * Aturan: Lempar email ke QA kalau data dari API.
     */
    public function storeExternalJob(StoreExternalJobRequest $request, CreateKanbanJobAction $createAction)
    {
        $validated = $request->validated();

        $items = [];
        if (!empty($validated['items']) && is_array($validated['items'])) {
            foreach ($validated['items'] as $rawItem) {
                // Ekstrak nomor lot jika tidak dikirim terpisah di payload (misal dari string item_name)
                $lotNumber = $rawItem['lot_number'] ?? $rawItem['lot'] ?? null;
                if (empty($lotNumber) && !empty($rawItem['item_name'])) {
                    if (preg_match('/Lot:\s*([^,\)]+)/i', $rawItem['item_name'], $matches)) {
                        $lotNumber = trim($matches[1]);
                    }
                }

                $items[] = [
                    'item_code' => $rawItem['item_code'] ?? null,
                    'item_name' => $rawItem['item_name'],
                    'lot_number' => $lotNumber,
                    'qty' => isset($rawItem['qty']) && (int) $rawItem['qty'] > 0 ? (int) $rawItem['qty'] : 1,
                    'unit' => $rawItem['unit'] ?? null,
                ];
            }
        }

        $deptId = $validated['department_id'] ?? null;
        if (!$deptId && !empty($validated['department_name'])) {
            $deptId = KanbanDepartment::where('department_name', 'like', "%{$validated['department_name']}%")->value('id');
        }
        if (!$deptId) {
            $deptId = KanbanDepartment::where('department_name', 'like', '%Quality%')->value('id') 
                ?? KanbanDepartment::first()?->id 
                ?? 1;
        }

        // Area: Biarkan null jika tidak dikirim dari API (Warehouse), agar ditentukan/dikonfirmasi saat Need Review
        $areaId = $validated['area_id'] ?? null;
        if (!$areaId && !empty($validated['area_name'])) {
            $areaId = KanbanArea::where('name', 'like', "%{$validated['area_name']}%")->value('id');
        }

        $payload = [
            'external_reference_id' => $validated['external_id'],
            'source' => 'api',
            'area_id' => $areaId, // nullable, ditentukan saat Need Review
            'to_department_id' => $deptId,
            'list_job' => !empty($validated['list_job']) ? $validated['list_job'] : $validated['reason_description'],
            'reason_description' => $validated['reason_description'],
            'remark' => $validated['remark'] ?? null,
            'balance' => $validated['balance'] ?? null,
            'status' => JobStatus::NEED_REVIEW,
            'items' => $items,
        ];

        $job = $createAction->execute($payload, Auth::id() ?: 1);

        // Notifikasi email ke tim QA bahwa ada job baru dari API masuk ke Need Review
        try {
            SendKanbanApiJobCreatedEmail::dispatch($job);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Gagal mengirim email notifikasi Job API: " . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Job berhasil dibuat dari submission API.',
            'job' => [
                'id' => $job->id,
                'id_job' => $job->id_job,
                'external_reference_id' => $job->external_reference_id,
                'source' => $job->source,
                'status' => $job->status,
                'balance' => $job->balance,
            ],
        ], 201);
    }

    /**
     * Input Tanggal dari To Be Scheduled -> Scheduled (Khusus PPIC).
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

            return $this->prepareJobResponse($updatedJob, 'Jadwal berhasil ditentukan oleh PPIC. Job berpindah ke Scheduled.');
        } catch (AuthorizationException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Setujui Job di Need Review -> To Be Scheduled (Khusus QA jika dari API).
     */
    public function agree(Request $request, JobKanban $job, TransitionJobStatusAction $transitionAction)
    {
        $request->validate([
            'note' => 'nullable|string|max:500',
            'area_id' => 'nullable|exists:mysql_kanban.kanban_areas,id',
        ]);

        try {
            $areaId = $request->filled('area_id') ? (int) $request->input('area_id') : null;
            $updatedJob = $transitionAction->agree($job, $request->note, Auth::id(), $areaId);
            return $this->prepareJobResponse($updatedJob, 'Job disetujui (QA). Berpindah ke tahap To Be Scheduled.');
        } catch (AuthorizationException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
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

            $cardHtml = View::make('kanban.jobs.partials.job_card', ['job' => $job])->render();

            return response()->json([
                'status' => 'success',
                'item' => $updatedItem,
                'job' => $job,
                'card_html' => $cardHtml,
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
        if ($job->status !== JobStatus::SCHEDULED) {
            return response()->json(['message' => 'Hanya job berstatus Scheduled yang dapat dipindahkan ke Preparation.'], 422);
        }

        try {
            $updatedJob = $transitionAction->moveStage($job, JobStatus::PREPARATION, null, 'Moved to Preparation stage', Auth::id());
            return $this->prepareJobResponse($updatedJob, 'Job moved to Preparation.');
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function start(JobKanban $job, TransitionJobStatusAction $transitionAction)
    {
        if (!in_array($job->status, [JobStatus::SCHEDULED, JobStatus::PREPARATION])) {
            return response()->json(['message' => 'Hanya job berstatus Scheduled atau Preparation yang dapat dimulai.'], 422);
        }

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
            'status' => 'required|in:to_be_scheduled,scheduled,preparation,on_going',
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
        } catch (AuthorizationException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
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

        if (in_array($job->status, [JobStatus::COMPLETED, JobStatus::CLOSED, JobStatus::CANCELLED])) {
            return response()->json(['message' => 'Job yang sudah selesai, ditutup, atau dibatalkan tidak dapat di-forward.'], 422);
        }

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
     * Aturan: Lempar notif completed ke pengaju klo tambah manual.
     */
    public function complete(Request $request, JobKanban $job)
    {
        $request->validate(['note' => 'required|string', 'attachments' => 'nullable|array']);

        if (in_array($job->status, [JobStatus::COMPLETED, JobStatus::CLOSED, JobStatus::CANCELLED])) {
            return response()->json(['message' => 'Job sudah berstatus ' . JobStatus::label($job->status) . '.'], 422);
        }

        if ($job->has_issue) {
            return response()->json([
                'message' => 'Pekerjaan masih memiliki kendala aktif (' . ($job->issue_note ?: 'Kendala fisik') . '). Harap selesaikan kendala terlebih dahulu sebelum menyelesaikan job.'
            ], 422);
        }

        $uncompletedItemsCount = $job->items()->where('is_completed', false)->count();
        if ($uncompletedItemsCount > 0) {
            return response()->json([
                'message' => "Masih ada {$uncompletedItemsCount} checklist item yang belum selesai. Selesaikan seluruh item atau gunakan fitur 'Pecah Job' untuk memindahkan sisa item ke tiket baru."
            ], 422);
        }

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

        // Aturan: Kirim email notifikasi setelah job selesai (bukan di job awal):
        // 1. Jika data dari API -> lempar email ke tim QA
        // 2. Jika tambah manual -> lempar notif completed ke pengaju
        if ($job->isFromApi()) {
            SendKanbanApiJobCompletedEmail::dispatch($job);
        } else {
            SendKanbanJobCompletedEmail::dispatch($job);
        }

        return $this->prepareJobResponse($job, 'Job marked as completed!');
    }

    /**
     * Tutup dan arsipkan Job (Closed).
     * Aturan: Yang completed ke closed itu yang terakhir request.
     */
    public function close(Request $request, JobKanban $job)
    {
        $user = Auth::user();

        if ($job->status !== JobStatus::COMPLETED) {
            return response()->json([
                'message' => 'Hanya job yang sudah diselesaikan (Completed) yang dapat ditutup dan diarsipkan.'
            ], 422);
        }

        // Aturan: Yang completed ke closed itu yang terakhir request
        if (!$job->canBeClosedBy($user)) {
            return response()->json([
                'message' => 'Hanya pengaju atau requester terakhir yang berhak menutup (close) job ini.'
            ], 403);
        }

        DB::connection('mysql_kanban')->transaction(function () use ($job, $user) {
            $job->update([
                'status' => JobStatus::CLOSED,
                'penutup_id' => $user->id,
                'closed_at' => Carbon::now(),
            ]);

            $job->notes()->create([
                'job_id' => $job->id,
                'note' => "JOB CLOSED by " . $user->name . " (Last Requester)",
                'created_by' => $user->id,
            ]);
        });

        try {
            $this->notifyAllParties($job);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Gagal mengirim email notifyAllParties saat close job: " . $e->getMessage());
        }

        return $this->prepareJobResponse($job, 'Job closed and archived.');
    }

    /**
     * Batalkan Job (Cancel).
     */
    public function cancel(Request $request, JobKanban $job)
    {
        $user = Auth::user();

        if (in_array($job->status, [JobStatus::COMPLETED, JobStatus::CLOSED, JobStatus::CANCELLED])) {
            return response()->json([
                'message' => 'Pekerjaan yang sudah berstatus ' . JobStatus::label($job->status) . ' tidak dapat dibatalkan.'
            ], 422);
        }

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
                try {
                    Mail::to($recipient->email)->send(
                        new KanbanJobCancelledNotification($job, $request->reason, $user->name)
                    );
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning("Gagal mengirim email cancelled ke {$recipient->email}: " . $e->getMessage());
                }
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
            try {
                if ($recipient->email) {
                    Mail::to($recipient->email)->send(new KanbanJobAutoClosedNotification($job, $recipient));
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("Gagal mengirim notif closed ke {$recipient->email}: " . $e->getMessage());
            }
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
