<?php

namespace App\Mail\Marsho;

use App\Models\Marsho\JobMarsho;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobOverdueAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public JobMarsho $job)
    {
    }

    public function envelope(): Envelope
    {
        $days = Carbon::now()->diffInDays($this->job->last_stage_update);

        return new Envelope(
            subject: "URGENT: Job {$this->job->id_job} Stuck for {$days} Days",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'jobs.emails.overdue',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
