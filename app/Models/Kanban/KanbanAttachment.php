<?php

namespace App\Models\Kanban;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanAttachment extends Model
{
    use HasFactory;

    protected $connection = 'mysql_kanban';
    protected $table = 'kanban_attachments';
    const UPDATED_AT = null;
    protected $guarded = [];

    public function job()
    {
        return $this->belongsTo(JobKanban::class, 'job_id');
    }

    public function item()
    {
        return $this->belongsTo(KanbanItem::class, 'item_id');
    }

    public function route()
    {
        return $this->belongsTo(KanbanRoute::class, 'job_route_id');
    }

    public function uploadedByUser()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
