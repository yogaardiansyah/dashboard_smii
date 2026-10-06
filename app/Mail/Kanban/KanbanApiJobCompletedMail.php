<?php

namespace App\Mail\Kanban;

use App\Models\Kanban\JobKanban;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KanbanApiJobCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public JobKanban $job;

    /**
     * Create a new message instance.
     */
    public function __construct(JobKanban $job)
    {
        $this->job = $job;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Kanban [Job API Selesai] : ' . $this->job->id_job . ' (' . ($this->job->external_reference_id ?? 'API') . ') - Completed')
                    ->view('kanban.emails.api_job_completed');
    }
}
