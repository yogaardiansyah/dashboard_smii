<?php

namespace App\Jobs\Marsho;

use App\Mail\Marsho\JobCompletedMail;
use App\Models\Marsho\JobMarsho;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendJobCompletedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $jobMarsho;

    public function __construct(JobMarsho $jobMarsho)
    {
        $this->jobMarsho = $jobMarsho;
    }

    public function handle()
    {
        $requester = $this->jobMarsho->pengaju;
        if ($requester && $requester->email) {
            Mail::to($requester->email)->send(new JobCompletedMail($this->jobMarsho));
        }
    }
}
