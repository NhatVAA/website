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
    // Hàm thực hiện Like / Hủy Like
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

    // Trả về danh sách các lượt like của bài viết với ID truyền vào
    // /api/like/{id_Post}
    public function show(string $id)
    {
        //
        $listLike = Like::with('user')->where('id_Post',$id)->get();
        if(!$listLike){
            $arr = [
                'status' => true,
                'message' => 'Bài viết chưa có lượt thích nào',
                'data' => [],
            ];
            return response()->json($arr, 204);
        }

        $arr = [
            'status' => true,
            'message' => 'Danh sách các lượt thích của bài viết',
            'data' => array_reverse($listLike->toArray()),
        ];
        return response()->json($arr, 200);
    }
}
