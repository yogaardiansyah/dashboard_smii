<?php

namespace App\Actions\Kanban;

use App\Models\Kanban\KanbanItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ToggleItemStatusAction
{
    public function execute(KanbanItem $item, bool $isCompleted, ?int $userId = null): KanbanItem
    {
        $userId = $userId ?: Auth::id();

        $item->update([
            'is_completed' => $isCompleted,
            'completed_by' => $isCompleted ? $userId : null,
            'completed_at' => $isCompleted ? Carbon::now() : null,
        ]);

        return $item->fresh();
    }
}
