<?php

namespace App\Events\Marsho;

use App\Models\Marsho\JobMarsho;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JobUpdated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public JobMarsho $job;
    public string $html;

    public function __construct(JobMarsho $job, string $html)
    {
        $this->job = $job;
        $this->html = $html;
    }

    public function broadcastOn(): array
    {
        return [new Channel('jobs')];
    }

    public function broadcastAs(): string
    {
        return 'JobUpdated';
    }
}
