<?php

namespace App\Models\Marsho;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarshoUser extends Model
{
    use HasFactory;

    protected $connection = 'mysql_job';
    protected $table = 'marsho_users';
    protected $fillable = ['user_id', 'marsho_department_id'];

    /**
     * Profil Marsho ini dimiliki oleh satu User dari sistem utama.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Profil Marsho ini dimiliki oleh satu MarshoDepartment.
     */
    public function department()
    {
        return $this->belongsTo(MarshoDepartment::class, 'marsho_department_id');
    }
}
