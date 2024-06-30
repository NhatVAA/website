<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\Pusher;
use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
        //
        $userSend = User::where('id', $senderId)->get();
        //
        $newMessage = Message::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $message
        ]);

        // Gửi thông báo cho người nhận
        $this->pusher->trigger('messages.' . $senderId + $receiverId, 'MessageSent', [
            'message' => $newMessage
        ]);
        // Gửi thông báo cho người nhận
        $this->pusher->trigger('messagesNotification', 'MessageSent', [
            'message' => $newMessage,
            'user' => $userSend,
        ]);
        // Event(new MessageSent($newMessage));

        $arr = [
            'status' => true,
            'message' => 'Tin nhắn đã được gửi thành công',
            'data' => $newMessage,
        ];
        return response()->json($arr,200);
    }

    public function getMessages(Request $request, string $idSender)
    {
        $userId = $request->user()->id;
        //
        $messages = Message::where(function ($query) use ($userId, $idSender) {
            $query->where('sender_id', $idSender)
                  ->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($userId, $idSender) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', $idSender);
        })->get();

        // $receivedMessages = Message::where('receiver_id', $userId)->get();
        // $sentMessages = Message::where('sender_id', $userId)->get();

        // $messages = $receivedMessages->merge($sentMessages)->sortBy('created_at');


        $arr = [
            'status' => true,
            'message' => 'Danh sách các cuộc trò chuyện',
            'data' => $messages,
        ];
        return response()->json($arr,200);
    }

    public function getBoxMessages(Request $request)
    {
        $userId = $request->user()->id;
        // truy vấn lấy những người đã nhắn tin, ko trùng lập
        $boxMessages = Message::with('user')->select(DB::raw('DISTINCT
        CASE
            WHEN sender_id = ' . $userId . ' THEN receiver_id
            ELSE sender_id
        END AS receiver_id'))
        ->where(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                  ->orWhere('receiver_id', $userId);
        })
        ->get();
        //
        $arr = [
            'status' => true,
            'message' => 'Danh sách các cuộc trò chuyện',
            'data' => $boxMessages,
        ];
        return response()->json($arr,200);
    }

    public function getListNewMessages(Request $request){
        $userId = $request->user()->id;
        //
        $latestMessages = Message::where('receiver_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();
        //
        $arr = [
            'status' => true,
            'message' => 'Danh sách 4 tin nhắn mới nhất',
            'data' => $latestMessages,
        ];
        return response()->json($arr,200);
    }
}
