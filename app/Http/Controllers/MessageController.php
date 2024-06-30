<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\Pusher;
use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{
    // protected $pusher;

    public function __construct()
    {
        // $this->pusher = $pusher;
        $this->pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'useTLS' => true,
            ]
        );

    }

    public function sendMessage(Request $request)
    {
        $senderId = $request->user()->id;
        $receiverId = $request->input('receiver_id');
        $message = $request->input('message');

        $newMessage = Message::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $message
        ]);
        // broadcast(new MessageSent($newMessage));

        // Gửi thông báo cho người nhận
        $this->pusher->trigger('messages', 'MessageSent', [
            'message' => $newMessage
        ]);
        
        // broadcast(new MessageSent($newMessage))->toOthers();

        $arr = [
            'status' => true,
            'message' => 'Tin nhắn đã được gửi thành công',
            'data' => $newMessage,
        ];
        return response()->json($arr,200);
    }

    public function getMessages(Request $request)
    {
        $userId = $request->user()->id;

        $receivedMessages = Message::where('receiver_id', $userId)->get();
        $sentMessages = Message::where('sender_id', $userId)->get();

        $messages = $receivedMessages->merge($sentMessages)->sortBy('created_at');

        // $messages = $messages->sortBy('created_at');

        $arr = [
            'status' => true,
            'message' => 'Danh sách các cuộc trò chuyện',
            'data' => $messages,
        ];
        return response()->json($arr,200);
    }

}
