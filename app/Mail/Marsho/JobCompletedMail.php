<?php

namespace App\Mail\Marsho;

use App\Models\Marsho\JobMarsho;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $job;

    public function __construct(JobMarsho $job)
    {
        $this->job = $job;
    }

    public function build()
    {
        return $this->subject('Marsho JobBoard : ' . $this->job->id_job . ' - completed')
                    ->view('jobs.emails.job_completed');
    }
}
