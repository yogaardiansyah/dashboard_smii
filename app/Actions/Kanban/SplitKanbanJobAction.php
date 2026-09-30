<?php

namespace App\Actions\Kanban;

use App\Models\Kanban\JobKanban;
use App\Models\Kanban\KanbanDepartment;
use App\Models\Kanban\KanbanItem;
use App\Enums\Kanban\JobStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class SplitKanbanJobAction
{
    /**
     * Memecah Job Induk menjadi Job Anak.
     * Item yang belum selesai dipindahkan ke Job Anak dengan status on_hold.
     */
    public function execute(
        JobKanban $parentJob,
        int $targetDepartmentId,
        ?int $targetAreaId = null,
        ?string $reasonNote = null,
        array $selectedItemIds = [],
        ?int $actionUserId = null
    ): array {
        $actionUserId = $actionUserId ?: Auth::id();

        return DB::connection('mysql_kanban')->transaction(function () use (
            $parentJob,
            $targetDepartmentId,
            $targetAreaId,
            $reasonNote,
            $selectedItemIds,
            $actionUserId
        ) {
            // 1. Tentukan item yang akan dipindahkan
            $itemsQuery = $parentJob->items();
            if (!empty($selectedItemIds)) {
                $itemsToMove = $itemsQuery->whereIn('id', $selectedItemIds)->get();
            } else {
                $itemsToMove = $itemsQuery->where('is_completed', false)->get();
            }

            if ($itemsToMove->isEmpty()) {
                throw new Exception("Tidak ada item yang dapat di-split.");
            }

            // 2. Kalkulasi saldo dan penamaan
            $childIdJob = JobKanban::generateChildJobId($parentJob);
            $movedCount = $itemsToMove->count();
            $remainingItemsCount = $parentJob->items()->whereNotIn('id', $itemsToMove->pluck('id'))->count();

            $childAreaId = $targetAreaId ?: $parentJob->area_id;
            $toDept = KanbanDepartment::find($targetDepartmentId);
            $toDeptName = $toDept?->department_name ?? 'Department';

            // 3. Buat Job Anak (Selalu di ON_HOLD pada departemen tujuan)
            $childJob = JobKanban::create([
                'id_job' => $childIdJob,
                'parent_id' => $parentJob->id,
                'pengaju_id' => $parentJob->pengaju_id,
                'pic_id' => null, // Dikosongkan agar bisa diklaim oleh departemen tujuan
                'area_id' => $childAreaId,
                'list_job' => "SPLIT DARI {$parentJob->id_job}: " . $itemsToMove->pluck('item_name')->implode(', '),
                'balance' => $movedCount,
                'reason_description' => $parentJob->reason_description,
                'remark' => "Pecahan dari {$parentJob->id_job}. Alasan: " . ($reasonNote ?: 'Pemecahan item pekerjaan'),
                'tanggal_job_mulai' => null,
                'deadline' => null,
                'status' => JobStatus::ON_HOLD,
                'last_stage_update' => Carbon::now(),
            ]);

            // 4. Pindahkan relasi item ke Job Anak
            KanbanItem::whereIn('id', $itemsToMove->pluck('id'))
                ->update(['job_id' => $childJob->id]);

            // 5. Sesuaikan saldo (balance) pada Job Induk
            $parentJob->update([
                'balance' => $remainingItemsCount > 0 ? $remainingItemsCount : 0,
                'last_stage_update' => Carbon::now(),
            ]);

            // 6. Buat Route awal untuk Job Anak
            $currentDeptId = $parentJob->latestRoute?->to_department_id;
            $childJob->routes()->create([
                'from_department_id' => $currentDeptId,
                'to_department_id' => $targetDepartmentId,
                'from_status' => null,
                'to_status' => JobStatus::ON_HOLD,
                'note' => "SPLIT JOB CREATED dari {$parentJob->id_job}. Dipindahkan ke {$toDeptName}.\nCatatan: " . ($reasonNote ?: '-'),
                'created_by' => $actionUserId,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // 7. Catat Note & Activity pada Job Induk
            $parentJob->notes()->create([
                'job_id' => $parentJob->id,
                'job_route_id' => $parentJob->latestRoute?->id,
                'note' => "SPLIT JOB: {$movedCount} item dipisahkan ke job baru [{$childJob->id_job}] untuk departemen {$toDeptName}.\nCatatan: " . ($reasonNote ?: '-'),
                'created_by' => $actionUserId,
            ]);

            return [
                'parent' => $parentJob->fresh(['items', 'latestRoute']),
                'child' => $childJob->fresh(['items', 'latestRoute']),
            ];
        });
    }
}
