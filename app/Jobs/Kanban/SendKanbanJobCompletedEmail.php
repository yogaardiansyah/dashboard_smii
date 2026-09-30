<?php

namespace App\Jobs\Kanban;

use App\Models\Kanban\JobKanban;
use App\Mail\Kanban\KanbanJobCompletedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendKanbanJobCompletedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $jobKanban;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\Kanban\JobKanban  $jobKanban
     * @return void
     */
    public function __construct(JobKanban $jobKanban)
    {
        $this->jobKanban = $jobKanban;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $requester = $this->jobKanban->pengaju;
        if ($requester && $requester->email) {
            Mail::to($requester->email)->send(new KanbanJobCompletedMail($this->jobKanban));
        }
    }
}
