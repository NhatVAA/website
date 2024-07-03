<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;

class LikeController extends Controller
{
    
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
    // Hàm thực hiện Like / Hủy Like
    public function store($postId){
        $post = Post::find($postId);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        $userId = auth()->user()->id; //
        
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
            
            //Gửi thông báo pusher
            $this->pusher->trigger('like', 'LikeSent', [
                'message' => $newLike,
                'post' => $post,
                'user' => auth()->user(),
            ]);

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
