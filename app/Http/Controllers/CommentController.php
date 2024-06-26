<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

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
        //tạo thông báo cmt
        return response()->json($arr, 201);
    }

    /**
     * Display the specified resource.
     */
    // Trả về danh sách các bình luận của bài viết với ID truyền vào
    // /api/comment/{id_Post}
    public function show(string $id)
    {
        //
        $listComment = Comment::with('user')->where('id_Post',$id)->get();
        if(!$listComment){
            $arr = [
                'status' => true,
                'message' => 'Bài viết chưa có bình luận nào',
                'data' => [],
            ];
            return response()->json($arr, 204);
        }

        $arr = [
            'status' => true,
            'message' => 'Danh sách các bình luận của bài viết',
            'data' => array_reverse($listComment->toArray()),
        ];
        return response()->json($arr, 200);
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
    public function update(Request $request,Comment $comment)
    {
        //
        $idUser = auth()->user(); 
        $commentOfUser = Comment::all()->where('id', $comment)->get('id_User');
        $input = $request->all();
        $validator = Validator::make($input,[
            'content' => 'required',
        ]);
        if($validator->fails())
        {
            $arr = [
                'status' => false,
                'message' => 'Lỗi thông tin xin nhập lại',
                'data' => $validator->errors(),
            ];
            return response()->json($arr,404);
        }
        // $idUser->id !== $post->id_User
        elseif ($idUser->id !== $commentOfUser) {
            return response()->json(['error' => 'Unauthorized to edit this post' ], 403);
        }
        $comment -> content = $input['content'];
        $comment->save();
        $arr = [
            'status' => true,
            'message' => 'Bình luận đã được cập nhật',
            'data' => $comment,
        ];
        return response()->json($arr,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
        $idUser = auth()->user();
        $idUser1 = $idUser->id ;
        $comment = Comment::all()->find($comment);
        $id_Post = $comment->id_Post;
        $id_User = $comment->id_User;
        $post = $comment->post;
        if ($idUser1 !== $id_User && $idUser1 !== $post->id_User) {
            return response()->json(['error' => 'Unauthorized to delete this comment'], 403);
        }

        $comment->delete();
        $arr = [
            'status' => true,
            'message' => 'Bình luận đã được xóa',
            'data' => [],
        ];
        return response()->json($arr,200);
    }
}
