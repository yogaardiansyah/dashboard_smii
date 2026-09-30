<?php

namespace App\Models\Marsho;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRoute extends Model
{
    use HasFactory;

    protected $connection = 'mysql_job';
    protected $table = 'marsho_job_routes';
    const UPDATED_AT = null;
    protected $fillable = ['job_id', 'from_department_id', 'to_department_id', 'note', 'created_by', 'created_at'];

    public function job() { return $this->belongsTo(JobMarsho::class, 'job_id'); }
    
    public function fromDepartment() { return $this->belongsTo(MarshoDepartment::class, 'from_department_id'); }
    public function toDepartment() { return $this->belongsTo(MarshoDepartment::class, 'to_department_id'); }
    
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
