<?php

namespace App\Jobs\Kanban;

use App\Models\Kanban\JobKanban;
use App\Models\Kanban\KanbanUser;
use App\Models\User;
use App\Mail\Kanban\KanbanApiJobCreatedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendKanbanApiJobCreatedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public JobKanban $jobKanban;

    public function __construct(JobKanban $jobKanban)
    {
        $this->jobKanban = $jobKanban;
    }

    public function handle(): void
    {
        try {
            $this->jobKanban->loadMissing(['items', 'area']);

            // Dapatkan seluruh user QA:
            // 1. Role Spatie 'qa'
            $qaRoleUserIds = User::role('qa')->pluck('id');

            // 2. User di departemen Kanban Quality Assurance
            $qaKanbanDeptIds = \App\Models\Kanban\KanbanDepartment::where('department_name', 'like', '%Quality%')
                ->orWhere('department_name', 'like', '%QA%')
                ->pluck('id');
            $qaKanbanUserIds = KanbanUser::whereIn('kanban_department_id', $qaKanbanDeptIds)->pluck('user_id');

            // 3. User di departemen utama QM & HSE
            $qaMainDeptIds = \App\Models\Department::where('department_name', 'like', '%QM%')
                ->orWhere('department_name', 'like', '%Quality%')
                ->pluck('id');
            $qaMainUserIds = User::whereIn('department_id', $qaMainDeptIds)->pluck('id');

            $allQaUserIds = $qaRoleUserIds->merge($qaKanbanUserIds)->merge($qaMainUserIds)->unique();
            $recipients = User::whereIn('id', $allQaUserIds)->whereNotNull('email')->get();

            // Fallback jika tidak ada user QA spesifik: kirim ke super admin atau logging
            if ($recipients->isEmpty()) {
                $superAdmin = User::where('username', 'super')->orWhere('email', 'like', '%@%')->first();
                if ($superAdmin && $superAdmin->email) {
                    $recipients = collect([$superAdmin]);
                }
            }

            foreach ($recipients as $recipient) {
                Mail::to($recipient->email)->send(new KanbanApiJobCreatedMail($this->jobKanban));
            }
        } catch (\Throwable $e) {
            Log::error("Gagal mengirim email notifikasi Job API ke tim QA: " . $e->getMessage(), [
                'job_id' => $this->jobKanban->id,
                'exception' => $e,
            ]);
        }
    }
}
