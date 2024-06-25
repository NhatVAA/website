<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct(Pusher $pusher)
    {
        $this->pusher = $pusher;
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

        // Gửi thông báo cho người nhận
        $this->pusher->trigger('messages', 'new-message', [
            'message' => $newMessage
        ]);
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

        $messages = $receivedMessages->merge($sentMessages);

        $messages = $messages->sortBy('created_at');

        $arr = [
            'status' => true,
            'message' => 'Danh sách các cuộc trò chuyện',
            'data' => $messages,
        ];
        return response()->json($arr,200);
    }

}
