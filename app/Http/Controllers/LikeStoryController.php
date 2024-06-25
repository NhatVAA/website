<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Story;
use App\Models\LikeStory;

class LikeStoryController extends Controller
{
    public function store($storyId){
        $story = Story::find($storyId);
        if (!$story) {
            $arr = [
                'status' => false,
                'message' => 'Không có Story này',
                'data' => []
            ];
            return response()->json($arr, 404);
        }

        $userId = auth()->user()->id; // Assuming you have user authentication
        
        $likestory = $story->likestorys()->where('id_User', $userId)->first();

        if ($likestory) {
            $likestory->delete();
            $arr = [
                'status' => true,
                'message' => 'Đã xóa like',
                'data' => [
                    'likes' => $story->likestorys()->count()
                    ]
            ];
            return response()->json($arr,200);
        } else {
            $newLike = new LikeStory([
                'id_User' => $userId,
                'id_Story' => $storyId,
            ]);

            $story->likestorys()->save($newLike);
            $arr = [
                'status' => true,
                'message' => 'Đã like thành công',
                'data' => [
                    'likes' => $story->likestorys()->count(),
                ]
            ];
            return response()->json($arr,200);
        }
    }
}
