<?php

namespace App\Models\Kanban;

use Spatie\Activitylog\Models\Activity as SpatieActivity;
use Illuminate\Database\Eloquent\Builder;

class KanbanActivityLog extends SpatieActivity
{
    protected $connection = 'mysql_kanban';
    protected $table = 'kanban_activity_logs';

    public function scopeForJobs(Builder $query): Builder
    {
        return $query->where('subject_type', JobKanban::class);
    }

    public function scopeForSubjectId(Builder $query, int $id): Builder
    {
        return $query->where('subject_id', $id);
    }
}
