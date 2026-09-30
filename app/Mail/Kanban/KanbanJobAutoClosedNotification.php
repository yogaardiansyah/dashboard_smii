<?php

namespace App\Mail\Kanban;

use App\Models\Kanban\JobKanban;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KanbanJobAutoClosedNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public JobKanban $job;
    public User $user;

    /**
     * Create a new message instance.
     */
    public function __construct(JobKanban $job, User $user)
    {
        $this->job = $job;
        $this->user = $user;
        \Log::info('KANBAN MAIL JOB QUEUED', [
            'job_id' => $job->id_job,
            'user' => $user->email
        ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Notification] Kanban Job ' . $this->job->id_job . ' Has Been Automatically Closed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'kanban.emails.auto_closed',
        );
    }

    /**
     * (Optional) Attachments
     */
    public function attachments(): array
    {
        return [];
    }
}
