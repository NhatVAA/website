<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($postId)
    {
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
                'likes' => $post->likes()->count()
                ]
        ];
        return response()->json($arr,200);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
