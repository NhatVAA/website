<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Notification;

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
    // lấy ra các tương tác mới nhất của các bài viết của mình
    public function getLastestInteract(){
        $id_User = auth()->user()->id;
        //
        $latestComments = Comment::with('user')->select('comment.*')
        ->join('post', 'comment.id_Post', '=', 'post.id')
        ->where('post.id_User', $id_User)
        ->whereIn('comment.id', function($query) {
            $query->select(DB::raw('MAX(c2.id)'))
                ->from('comment as c2')
                ->whereColumn('c2.id_Post', 'comment.id_Post')
                ->groupBy('c2.id_Post');
        })
        ->orderBy('comment.updated_at', 'desc')
        ->get();
        //
        $lastLikes = Like::with('user')->select('like.*')
        ->join('post', 'like.id_Post', '=', 'post.id')
        ->where('post.id_User', $id_User)
        ->whereIn('like.id', function($query) {
            $query->select(DB::raw('MAX(c2.id)'))
                ->from('like as c2')
                ->whereColumn('c2.id_Post', 'like.id_Post')
                ->groupBy('c2.id_Post');
        })
        ->orderBy('like.updated_at', 'desc')
        ->get();
        $arr = [
            'status' => true,
            'message' => 'Các tương tác mới nhất của các bài viết của mình',
            'data' => [
                'lastestComment' => $latestComments,
                'lastestLike' => $lastLikes,
            ],
        ];
        return response()->json($arr,200);
    }
}
