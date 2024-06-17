<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;

class LikeController extends Controller
{
    //
    public function store($postId){
        $post = Post::find($postId);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        $userId = auth()->user()->id; // Assuming you have user authentication
        
        $like = $post->likes()->where('id_User', $userId)->first();

        if ($like) {
            $like->delete();
            $arr = [
                'status' => true,
                'message' => 'Đã xóa like',
                'data' => [
                    'likes' => $post->likes()->count()
                    ]
            ];
            return response()->json($arr,200);
        } else {
            $newLike = new Like([
                'id_User' => $userId,
                'id_Post' => $postId,
            ]);

            $post->likes()->save($newLike);
            $arr = [
                'status' => true,
                'message' => 'Đã like thành công',
                'data' => [
                    'likes' => $post->likes()->count(),
                ]
            ];
            return response()->json($arr,200);
        }
    }
}
