<?php

namespace App\Models\Kanban;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanRoute extends Model
{
    use HasFactory;

    protected $connection = 'mysql_kanban';
    protected $table = 'kanban_routes';
    const UPDATED_AT = null;
    protected $guarded = [];

    public function job()
    {
        return $this->belongsTo(JobKanban::class, 'job_id');
    }
    
    public function fromDepartment()
    {
        return $this->belongsTo(KanbanDepartment::class, 'from_department_id');
    }

    public function toDepartment()
    {
        return $this->belongsTo(KanbanDepartment::class, 'to_department_id');
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
