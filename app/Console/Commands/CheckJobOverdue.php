<?php

namespace App\Console\Commands;

use App\Mail\Marsho\JobOverdueAlert;
use App\Models\Marsho\JobMarsho;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckJobOverdue extends Command
{
    protected $signature = 'jobs:check-overdue';
    protected $description = 'Send email if job stays in a stage for more than 3 days to the current Department';

    public function handle()
    {
        $jobs = JobMarsho::with(['latestRoute', 'pengaju'])
            ->whereNotIn('status', ['completed', 'closed', 'cancelled'])
            ->where('last_stage_update', '<', Carbon::now()->subDays(3))
            ->get();

        foreach ($jobs as $job) {
            $currentDeptId = $job->latestRoute->to_department_id ?? null;

            if ($currentDeptId) {
                $recipients = User::whereHas('marshoProfile', function ($query) use ($currentDeptId) {
                    $query->where('marsho_department_id', $currentDeptId);
                })->get();

                if ($recipients->count() > 0) {
                    foreach ($recipients as $recipient) {
                        if ($recipient->email) {
                            Mail::to($recipient->email)->send(new JobOverdueAlert($job));
                        }
                    }

                    $this->info("Alert sent for Job ID: {$job->id_job} to " . $recipients->count() . " users in Dept ID: $currentDeptId");
                } else {
                    $this->warn("Job ID: {$job->id_job} is overdue, but no users found in Dept ID: $currentDeptId");
                }
            }
        }
    }
}
