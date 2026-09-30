<?php

namespace App\Models\Kanban;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Collection;

class JobKanban extends Model
{
    use HasFactory, LogsActivity;

    protected $connection = 'mysql_kanban';
    protected $table = 'kanban_jobs';
    protected $guarded = [];

    protected $casts = [
        'tanggal_job_mulai' => 'date',
        'tanggal_job_selesai' => 'date',
        'deadline' => 'date',
        'closed_at' => 'datetime',
        'last_stage_update' => 'datetime',
        'has_issue' => 'boolean',
        'balance' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Kanban')
            ->setDescriptionForEvent(fn(string $eventName) => "Kanban Job '{$this->id_job}' has been {$eventName}");
    }

    public function tapActivity(\Spatie\Activitylog\Contracts\Activity $activity, string $eventName): void
    {
        $activity->setConnection('mysql_kanban');
        $activity->setTable('kanban_activity_logs');
    }

    /**
     * Generate ID Job Baru Utama (Induk)
     */
    public static function generateJobId(): string
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');
        $day = $date->format('d');

        $latestJob = self::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->whereDay('created_at', $date->day)
            ->whereNull('parent_id')
            ->latest('id')
            ->first();

        $sequence = 1;
        if ($latestJob && preg_match('/-(\d{4})$/', $latestJob->id_job, $matches)) {
            $sequence = ((int)$matches[1]) + 1;
        }

        return sprintf('KANBAN-%s%s%s-%04d', $year, $month, $day, $sequence);
    }

    /**
     * Generate ID Job Anak (Lineage / Keturunan Pemecahan)
     */
    public static function generateChildJobId(self $parent): string
    {
        $existingChildrenCount = self::where('parent_id', $parent->id)->count();

        // Jika parent sudah merupakan pecahan (mengandung -A, -B dsb.)
        if (preg_match('/-[A-Z](\d*)$/', $parent->id_job)) {
            $suffix = $existingChildrenCount + 1;
            return sprintf('%s-%d', $parent->id_job, $suffix);
        }

        // Jika parent adalah job induk utama
        $letter = chr(65 + $existingChildrenCount); // A, B, C, ...
        return sprintf('%s-%s', $parent->id_job, $letter);
    }

    // ==========================================
    // Relationships
    // ==========================================

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function items()
    {
        return $this->hasMany(KanbanItem::class, 'job_id');
    }

    public function pengaju()
    {
        return $this->belongsTo(User::class, 'pengaju_id');
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    public function penutup()
    {
        return $this->belongsTo(User::class, 'penutup_id');
    }

    public function area()
    {
        return $this->belongsTo(KanbanArea::class, 'area_id');
    }

    public function routes()
    {
        return $this->hasMany(KanbanRoute::class, 'job_id')->orderBy('created_at');
    }

    public function latestRoute()
    {
        return $this->hasOne(KanbanRoute::class, 'job_id')->latestOfMany();
    }

    public function attachments()
    {
        return $this->hasMany(KanbanAttachment::class, 'job_id');
    }

    public function notes()
    {
        return $this->hasMany(KanbanNote::class, 'job_id');
    }

    // ==========================================
    // Accessors & Helpers
    // ==========================================

    public function getInitialAttachmentsAttribute(): Collection
    {
        if (!$this->relationLoaded('attachments')) {
            $this->load('attachments');
        }
        return $this->attachments->filter(fn ($attachment) => str_contains($attachment->file_path, 'kanban_attachments/open/'));
    }

    public function getClosingAttachmentsAttribute(): Collection
    {
        if (!$this->relationLoaded('attachments')) {
            $this->load('attachments');
        }
        return $this->attachments->filter(fn ($attachment) => str_contains($attachment->file_path, 'kanban_attachments/closed/'));
    }

    public function getProgressPercentageAttribute(): int
    {
        $total = $this->items->count();
        if ($total === 0) {
            return $this->status === 'completed' || $this->status === 'closed' ? 100 : 0;
        }

        $completed = $this->items->where('is_completed', true)->count();
        return (int) round(($completed / $total) * 100);
    }
}
