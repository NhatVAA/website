<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $id_User = auth()->id();
        $notifications = Notification::where('user_id', $id_User)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json(['notifications' => $notifications]);
    }

    public function NotificationRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->read_at = now();
        $notification->save();

        return response()->json(['message' => 'Notification marked as read']);
    }
    
    public function store(Request $request, $idPost)
    {
        $content = $request->content;
        $post = Post::find($idPost);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        $commentData = request()->validate([
            'content' => 'required|string',
        ]);
        
        $userId = auth()->user()->id;
        $commentData = [
            'content' => $content,
            'id_Post' => $post->id,
            'id_User' => $userId,
        ];
    
        $comment = new Comment($commentData);
        $post->comments()->save($comment);
        $arr = [
            'status' => true,
            'message' => 'Bình luận thành công',
            'data' => $comment,
        ];
        return response()->json($arr, 201);
    }

}
