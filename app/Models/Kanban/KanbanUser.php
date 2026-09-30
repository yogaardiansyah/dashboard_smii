<?php

namespace App\Models\Kanban;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanUser extends Model
{
    use HasFactory;

    protected $connection = 'mysql_kanban';
    protected $table = 'kanban_users';
    protected $fillable = ['user_id', 'kanban_department_id'];

    /**
     * Profil Kanban ini dimiliki oleh satu User dari sistem utama.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Profil Kanban ini dimiliki oleh satu KanbanDepartment.
     */
    public function department()
    {
        return $this->belongsTo(KanbanDepartment::class, 'kanban_department_id');
    }
}
