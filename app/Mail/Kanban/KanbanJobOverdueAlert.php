<?php

namespace App\Mail\Kanban;

use App\Models\Kanban\JobKanban;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class KanbanJobOverdueAlert extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public JobKanban $job)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $days = Carbon::now()->diffInDays($this->job->last_stage_update);

        return new Envelope(
            subject: "URGENT: Kanban Job {$this->job->id_job} Stuck for {$days} Days",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'kanban.emails.overdue',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
