<?php

namespace App\Models\Kanban;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanArea extends Model
{
    use HasFactory;

    protected $connection = 'mysql_kanban';
    protected $table = 'kanban_areas';
    protected $fillable = ['name', 'description'];

    public function jobs()
    {
        return $this->hasMany(JobKanban::class, 'area_id');
    }
}
