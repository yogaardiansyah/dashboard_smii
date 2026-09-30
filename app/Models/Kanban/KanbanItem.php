<?php

namespace App\Models\Kanban;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanItem extends Model
{
    use HasFactory;

    protected $connection = 'mysql_kanban';
    protected $table = 'kanban_items';
    protected $guarded = [];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function job()
    {
        return $this->belongsTo(JobKanban::class, 'job_id');
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function attachments()
    {
        return $this->hasMany(KanbanAttachment::class, 'item_id');
    }
}
