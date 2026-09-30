<?php

namespace App\Mail\Marsho;

use App\Models\Marsho\JobMarsho;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobCancelledNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public JobMarsho $job, 
        public string $reason,
        public string $cancelledBy
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'JOB CANCELLED: ' . $this->job->id_job,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'jobs.emails.cancelled',
        );
    }
}
