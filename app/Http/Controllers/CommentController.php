<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;

class CommentController extends Controller
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
    public function store($post)
    {
        $post = Post::find($post);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        $commentData = request()->validate([
            'content' => 'required|string',
        ]);
        
        if ($commentData->fails()) 
        {
            $arr = [              
                    'status' => false,
                    'message' => 'Thông tin chưa chính xác' ,
                    'data' => [$commentData->errors()],
            ];
            return response()->json($arr,404);
        }
        $comment = new Comment($commentData);
        $post->comments()->save($comment);

        return response()->json($comment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
