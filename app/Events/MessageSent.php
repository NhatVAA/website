<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Message;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('message.' . $this->message->sender_id . '.' . $this->message->receiver_id);
    }
    // public function broadcastWith(): array
    // {
    //     return [
    //         'message' => [
    //             'id' => $this->message->id,
    //             'sender_id' => $this->message->sender_id,
    //             'receiver_id' => $this->message->receiver_id,
    //             'content' => $this->message->message,
    //             'created_at' => $this->message->created_at->format('d/m/Y H:i:s'),
    //             // Add more fields if needed
    //         ],
    //     ];
    // }
}
