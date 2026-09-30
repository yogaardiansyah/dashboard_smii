<?php

namespace App\Console\Commands;

use App\Mail\Marsho\JobAutoClosedNotification;
use App\Models\Marsho\JobMarsho;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AutoCloseJobs extends Command
{
    protected $signature = 'jobs:autoclose';
    protected $description = 'Automatically closes completed jobs after 2 working days and notifies all involved users.';

    public function handle()
    {
        $this->info('Starting to check for completed jobs to auto-close...');

        $twoWorkDaysAgo = Carbon::now()->subWeekdays(2)->endOfDay();

        $jobsToClose = JobMarsho::where('status', 'completed')
            ->where('tanggal_job_selesai', '<=', $twoWorkDaysAgo)
            ->get();

        if ($jobsToClose->isEmpty()) {
            $this->info('No completed jobs found that are old enough to be closed.');
            return;
        }

        $this->info("Found {$jobsToClose->count()} jobs to be auto-closed.");

        foreach ($jobsToClose as $job) {
            $job->update([
                'status' => 'closed',
                'closed_at' => Carbon::now(),
                'penutup_id' => null,
            ]);

            $job->notes()->create([
                'job_id' => $job->id,
                'note' => 'AUTO-CLOSED: Job was automatically closed by the system after being completed for more than 2 working days.',
                'created_by' => null,
            ]);

            $this->info("Job [{$job->id_job}] has been closed.");

            $involvedUsers = new Collection();

            if ($job->pengaju) {
                $involvedUsers->push($job->pengaju);
            }

            $job->load(['routes.creator', 'notes.creator']);
            $routeCreators = $job->routes->pluck('creator')->filter();
            $noteCreators = $job->notes->pluck('creator')->filter();
            $involvedUsers = $involvedUsers->merge($routeCreators)->merge($noteCreators);

            $departmentIds = $job->routes->pluck('to_department_id')->filter()->unique();
            if ($departmentIds->isNotEmpty()) {
                $usersInDepts = User::whereHas('marshoProfile', function ($query) use ($departmentIds) {
                    $query->whereIn('marsho_department_id', $departmentIds);
                })->get();
                $involvedUsers = $involvedUsers->merge($usersInDepts);
            }

            $uniqueUsers = $involvedUsers->unique('id');

            foreach ($uniqueUsers as $user) {
                try {
                    Mail::to($user->email)->send(new JobAutoClosedNotification($job, $user));
                    $this->line(" - Notified {$user->email}");
                } catch (\Exception $e) {
                    Log::error("Error notifying {$user->email}: " . $e->getMessage());
                }
            }
        }

        $this->info('Auto-close process finished.');
    }
}
