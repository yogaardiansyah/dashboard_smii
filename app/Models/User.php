<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Marsho\MarshoUser;
use App\Models\PCR\Initiator;
use App\Models\PCR\PCC;
use App\Models\QAD\Approver;
use App\Models\QAD\RequisitionMaster;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    protected $connection = 'mysql';

    public function getUsernameAttribute($value)
    {
        return strtolower($value);
    }

    public function getAvatarUrlAttribute()
    {
        if (!$this->avatar) {
            return null;
        }

        $path = 'user_avatars/' . $this->avatar;

        return Storage::disk('public')->exists($path)
            ? Storage::url('public/' . $path)
            : null;
    }

    public function getBannerUrlAttribute()
    {
        if (!$this->banner) {
            return null;
        }

        $path = 'user_banners/' . $this->banner;

        return Storage::disk('public')->exists($path)
            ? Storage::url('public/' . $path)
            : null;
    }


    // Kanban
    public function tasksDiajukan()
    {
        return $this->hasMany(Task::class, 'pengaju_id');
    }

    public function tasksDitutup()
    {
        return $this->hasMany(Task::class, 'penutup_id');
    }

    public function tasksApproved() // New
    {
        return $this->hasMany(Task::class, 'approver_id');
    }

    public function isAdminProject()
    {
        return $this->level >= 4;
    }

    public function isSuperAdmin()
    {
        return $this->position_id == 1 && $this->username === 'super';
    }

    // New: Check if user can approve tasks for a specific department
    // This is a simple check, you might have more complex role/permission system
    public function canApproveForDepartment(Department $department)
    {
        // Example: User must belong to the department and have a certain level/role
        // For simplicity, let's say any user in that department can approve.
        // Or, perhaps only department heads (e.g., user->is_department_head && user->department_id == $department->id)
        return $this->department_id === $department->id;
    }

    public function marshoProfile()
    {
        return $this->hasOne(MarshoUser::class, 'user_id');
    }

    public function kanbanProfile()
    {
        return $this->hasOne(\App\Models\Kanban\KanbanUser::class, 'user_id');
    }

    public function isQa(): bool
    {
        if ($this->isSuperAdmin() || $this->hasRole('super-admin')) {
            return true;
        }

        if ($this->hasRole(['qa', 'QA', 'Quality Assurance'])) {
            return true;
        }

        $kanbanDeptName = $this->kanbanProfile?->department?->department_name;
        if ($kanbanDeptName && (str_contains(strtolower($kanbanDeptName), 'qualit') || str_contains(strtoupper($kanbanDeptName), 'QA'))) {
            return true;
        }

        $mainDeptName = $this->department?->department_name;
        if ($mainDeptName && (str_contains(strtolower($mainDeptName), 'qualit') || str_contains(strtoupper($mainDeptName), 'QM') || str_contains(strtoupper($mainDeptName), 'QA'))) {
            return true;
        }

        return false;
    }

    public function isPpic(): bool
    {
        if ($this->isSuperAdmin() || $this->hasRole('super-admin')) {
            return true;
        }

        if ($this->hasRole(['ppic', 'PPIC'])) {
            return true;
        }

        $kanbanDeptName = $this->kanbanProfile?->department?->department_name;
        if ($kanbanDeptName && str_contains(strtoupper($kanbanDeptName), 'PPIC')) {
            return true;
        }

        $mainDeptName = $this->department?->department_name;
        if ($mainDeptName && (str_contains(strtoupper($mainDeptName), 'PPIC') || str_contains(strtolower($mainDeptName), 'supply chain'))) {
            return true;
        }

        return false;
    }
}
