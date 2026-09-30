<?php

namespace App\Mail\Kanban;

use App\Models\Kanban\JobKanban;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KanbanJobCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $job;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Kanban\JobKanban  $job
     * @return void
     */
    public function __construct(JobKanban $job)
    {
        $this->job = $job;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Kanban Board : ' . $this->job->id_job . ' - completed')
                    ->view('kanban.emails.job_completed');
    }
}
