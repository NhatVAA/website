<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Post;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->input('search');
        $users = User::where('name', 'like', "%$keyword%")->get();
        $posts = Post::with('user','comments', 'likes', 'photos', 'videos')->where('content', 'like', "%$keyword%")->where('privacy',0)->get();
        if(!isset($users) && !isset($posts))
        {
            $arr = [
                'status' => false,
                'message' => 'Thông tin tìm kiếm không có',
                'data' => [],
            ];
            return response()->json($arr,200);
        }
        $arr = [
            'status' => true,
            'message' => 'Thông tin tìm kiếm',
            'data' => [
                'users' => $users,
                'posts' => $posts,
            ]
        ];
        return response()->json($arr,200);
    }
}
