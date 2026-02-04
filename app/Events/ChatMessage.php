<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $username;
    public $message;
    public $avatar;

    /**
     * Create a new event instance.
     */
    public function __construct(string $username, string $message, string $avatar)
    {
        $this->username = $username;
        $this->message = $message;
        $this->avatar = $avatar;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel|array
    {
        return new PrivateChannel('chat');
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message';
    }
}
