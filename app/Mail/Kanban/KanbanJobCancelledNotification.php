<?php

namespace App\Mail\Kanban;

use App\Models\Kanban\JobKanban;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KanbanJobCancelledNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public JobKanban $job, 
        public string $reason,
        public string $cancelledBy
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'KANBAN JOB CANCELLED: ' . $this->job->id_job,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'kanban.emails.cancelled',
        );
    }
}
