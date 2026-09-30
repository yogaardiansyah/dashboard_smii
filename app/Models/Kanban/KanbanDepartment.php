<?php

namespace App\Models\Kanban;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanDepartment extends Model
{
    use HasFactory;

    protected $connection = 'mysql_kanban';
    protected $table = 'kanban_departments';
    protected $fillable = ['department_name'];

    /**
     * Satu departemen Kanban memiliki banyak profil pengguna Kanban.
     */
    public function kanbanUsers()
    {
        return $this->hasMany(KanbanUser::class, 'kanban_department_id');
    }
}
