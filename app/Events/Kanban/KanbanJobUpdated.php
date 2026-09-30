<?php

namespace App\Events\Kanban;

use App\Models\Kanban\JobKanban;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class KanbanJobUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public JobKanban $job;
    public string $html;

    /**
     * Create a new event instance.
     */
    public function __construct(JobKanban $job, string $html)
    {
        $this->job = $job;
        $this->html = $html;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('kanban-jobs'),
        ];
    }
}
