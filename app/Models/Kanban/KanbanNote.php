<?php

namespace App\Models\Kanban;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanNote extends Model
{
    use HasFactory;

    protected $connection = 'mysql_kanban';
    protected $table = 'kanban_notes';
    const UPDATED_AT = null;
    protected $fillable = ['job_id', 'job_route_id', 'note', 'created_by', 'created_at'];

    public function job()
    {
        return $this->belongsTo(JobKanban::class, 'job_id');
    }

    public function route()
    {
        return $this->belongsTo(KanbanRoute::class, 'job_route_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
