<?php

namespace App\Models\Marsho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $connection = 'mysql_job';
    protected $table = 'marsho_areas';
    protected $fillable = ['name', 'description'];

    public function jobs()
    {
        return $this->hasMany(JobMarsho::class, 'area_id');
    }
}
