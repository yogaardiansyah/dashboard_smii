<?php

namespace App\Mail\Marsho;

use App\Models\Marsho\JobMarsho;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class JobAutoClosedNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public JobMarsho $job;
    public User $user;

    public function __construct(JobMarsho $job, User $user)
    {
        $this->job = $job;
        $this->user = $user;
        Log::info('MAIL JOB QUEUED', [
            'job_id' => $job->id_job,
            'user' => $user->email
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Notification] Job ' . $this->job->id_job . ' Has Been Automatically Closed',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'jobs.emails.auto_closed',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
