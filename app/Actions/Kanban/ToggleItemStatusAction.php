<?php

namespace App\Actions\Kanban;

use App\Models\Kanban\KanbanItem;
use App\Models\User;
use App\Enums\Kanban\JobStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Exception;

class ToggleItemStatusAction
{
    public function execute(KanbanItem $item, bool $isCompleted, ?int $userId = null): KanbanItem
    {
        $userId = $userId ?: Auth::id();
        $user = $userId ? User::find($userId) : null;
        $userName = $user ? $user->name : "User ID #{$userId}";

        $job = $item->job;
        if ($job && in_array($job->status, [JobStatus::COMPLETED, JobStatus::CLOSED, JobStatus::CANCELLED])) {
            throw new Exception("Tidak dapat mengubah checklist item pada pekerjaan yang sudah selesai, ditutup, atau dibatalkan.");
        }

        $item->update([
            'is_completed' => $isCompleted,
            'completed_by' => $isCompleted ? $userId : null,
            'completed_at' => $isCompleted ? Carbon::now() : null,
        ]);

        if ($job) {
            $statusText = $isCompleted ? 'SELESAI (COMPLETED)' : 'DIBATALKAN (REVERTED TO PENDING)';
            $lotInfo = $item->lot_number ? " [Lot: {$item->lot_number}]" : "";
            $qtyInfo = $item->qty ? " ({$item->qty} {$item->unit})" : "";
            $auditNote = "CHECKLIST ITEM {$statusText}: '{$item->item_name}'{$lotInfo}{$qtyInfo} oleh {$userName}";

            $job->notes()->create([
                'job_id' => $job->id,
                'job_route_id' => $job->latestRoute?->id,
                'note' => $auditNote,
                'created_by' => $userId,
            ]);

            $job->update([
                'last_stage_update' => Carbon::now(),
            ]);
        }

        return $item->fresh();
    }
}
