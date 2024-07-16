<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversationSelected implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conversation_id;
    public $receiver_id;

    public function __construct($conversation_id, $receiver_id)
    {
        $this->conversation_id = $conversation_id;
        $this->receiver_id = $receiver_id;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('conversation.' . $this->conversation_id);
    }

    public function broadcastWith()
    {
        return [
            'conversation_id' => $this->conversation_id,
            'receiver_id' => $this->receiver_id,
        ];
    }
}
