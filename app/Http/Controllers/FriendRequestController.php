<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\friend as friendResource;

class FriendRequestController extends Controller
{
    // hàm gửi lời mời kết bạn
    public function sendFriendRequest(Request $request, $userId)
    {
        $id_User = Auth::user();
        $id_friend = User::find($userId);

        // Kiểm tra người dùng hiện tại và người nhận có phải là bạn bè hay không
        if ($id_User->isFriendsWith($id_friend)) {
            $arr =  [
                'status' => false,
                'message' => 'Bạn đã là bạn bè với '.$id_friend -> name,
                'data' => [
                    'id' => $id_friend -> id,
                    'name' => $id_friend -> name,
                ]
            ];
            return response()->json($arr, 400);
        }

        $friendRequest = $id_User->sentFriendRequests()->where('id_friend', $userId)->first();
        if ($friendRequest) {
            $arr =  [
                'status' => false,
                'message' => 'Bạn đã gửi lời mời tới '.$id_friend -> name. ' rồi',
                'data' => [
                    'id' => $id_friend -> id,
                    'name' => $id_friend -> name,
                ]
            ];
            return response()->json($arr, 404);
        }
        else{
        // Gửi yêu cầu kết bạn
        $id_User->sentFriendRequests()->attach($id_friend->id);
        $arr =  [
            'status' => true,
            'message' => 'Đã gửi lời mời đến '.$id_friend -> name.' thành công',
            'data' => [
                'id' => $id_friend -> id,
                'name' => $id_friend -> name,
            ]
        ];
        return response()->json($arr, 200);
        }
    }

    public function acceptFriendRequest(Request $request, $userId)
    {
        $currentUser = Auth::user();
        $friendRequest = $userId->FriendRequests()->where('id_friend', $currentUser)->first();

        if (!$friendRequest) {
            return response()->json([
                'message' => 'Yêu cầu kết bạn không được tìm thấy.',
            ], 404);
        }

        // Chấp nhận yêu cầu kết bạn
        $friendRequest->pivot->update(['status' => 'accepted']);
        $arr =  [
            'status' => true,
            'message' => $friendRequest -> name.'là bạn bè của bạn',
            'data' => [
                'id' => $friendRequest -> id,
                'name' => $friendRequest -> name,
            ]
        ];
        return response()->json($arr, 200);
    }
    // Xoá bạn bè
    public function unfriend($userId)
    {
        $currentUser = Auth::user();
        $recipientUser = User::find($userId);
        // Kiểm tra xem người dùng có tồn tại hay không
        if (!$recipientUser ) {
            return response()->json(['error' => 'Người dùng không tồn tại'], 404);
        }

        // Kiểm tra xem người dùng có phải là bạn của nhau hay không
        if (!$currentUser->isFriendsWith($recipientUser)) {
            return response()->json(['error' => 'Bạn không phải là bạn bè của người dùng này'], 400);
        }
        // Xóa bạn bè
        $currentUser->friends()->detach($userId);
        $arr =  [
            'status' => true,
            'message' => 'Huỷ kết bạn thành công',
            'data' => [],
        ];
        return response()->json($arr, 200);
    }


    // hàm từ chối kết bạn
    public function declineFriendRequest(Request $request, $userId)
    {
        $currentUser = Auth::user();
        $friendRequest = $currentUser->friendRequests()->where('id_friend', $userId)->first();

        if (!$friendRequest) {
            $arr =  [
                'status' => false,
                'message' => 'Yêu cầu kết bạn không được tìm thấy',
                'data' => [],
            ];
            return response()->json($arr, 404);
        }

        // Từ chối yêu cầu kết bạn
        $currentUser->friendRequests()->detach($userId);
        $arr =  [
            'status' => true,
            'message' => 'Từ chối yêu cầu kết bạn thành công',
            'data' => [],
        ];
        return response()->json($arr, 200);
    }

    public function declinesendFriendRequest(Request $request, $userId)
    {
        $currentUser = Auth::user();
        $friendRequest = $currentUser->sentFriendRequests()->where('id_friend', $userId)->first();
        if (!$friendRequest) {
            return response()->json([
                'message' => 'Yêu cầu kết bạn không được tìm thấy.',
            ], 404);
        }
        // Huỷ yêu cầu kết bạn
        $currentUser->sentFriendRequests()->detach($userId);
        $arr =  [
            'status' => true,
            'message' => 'Huỷ lời mời kết bạn thành công',
            'data' => [],
        ];
        return response()->json($arr, 200);
    }

    // hàm lấy ra danh sách bạn bè
    public function getFriendsList(Request $request)
    {
        $id_User = Auth::user();
        $friends = $id_User->friends;
        $arr = [
            'status' => true,
            'message' => 'Danh sách bạn bè',
            // 'data' => $friends->map(function ($friend) {
            //     return [
            //         'id' => $friend->id,
            //         'name' => $friend->name,
            //         'avatar' => $friend->avatar,
            //         'updated_at' => $friend -> pivot ->updated_at->format('Y-m-d H:i:s'),
            //         'created_at' => $friend -> pivot ->created_at->format('Y-m-d H:i:s'),
            //         ];
            //     }),
            'data' => friendResource::collection($friends),
        ];
        return response()->json($arr, 200);
    }

    // Lấy danh sách yêu cầu kết bạn đã gửi (yêu cầu đi)
    public function getSentFriendRequests(Request $request)
    {
        $id_User = Auth::user();
        $friendRequests = $id_User->sentFriendRequests()->get();

        // Biến đổi dữ liệu yêu cầu đã gửi (tùy chọn)
        // Bạn có thể sử dụng hàm map tương tự như getFriendsList để
        // tùy chỉnh cấu trúc dữ liệu của yêu cầu đã gửi
        $arr = [
            'status' => true,
            'message' => 'Danh sách kết bạn đã gửi',
            'data' => $friendRequests->map(function ($friend) {
                return [
                    'id' => $friend->id,
                    'name' => $friend->name,
                    'avatar' => $friend->avatar,
                    'updated_at' => $friend -> pivot ->updated_at->format('Y-m-d H:i:s'),
                    'created_at' => $friend -> pivot ->created_at->format('Y-m-d H:i:s'),
                    // 'text' =>$friend,
                ];
            })
        ];

        return response()->json($arr, 200);
    }

     // Lấy danh sách yêu cầu kết bạn đang chờ (yêu cầu đến)
     public function getPendingFriendRequests(Request $request)
     {
         $id_User = Auth::user();
         $friendRequests = $id_User->friendRequests()->where('status', 'pending')->get();
 
         $arr = [
            'status' => true,
            'message' => 'Danh sách kết bạn gửi đến',
            'data' => $friendRequests->map(function ($friend) {
                return [
                    'id' => $friend->id,
                    'name' => $friend->name,
                    'avatar' => $friend->avatar,
                    'updated_at' => $friend -> pivot ->updated_at->format('Y-m-d H:i:s'),
                    'created_at' => $friend -> pivot ->created_at->format('Y-m-d H:i:s'),
                ];
            })
        ];
 
         return response()->json($arr, 200);
     }

     //Lấy danh sách bạn bè chauw kết bạn
    public function getPendingFriends(Request $request)
     {
         $id_User = Auth::user();
 
         // Lấy danh sách bạn bè của người dùng hiện tại
         $friends = $id_User->friends;
 
         // Lấy danh sách tất cả người dùng
         $allUsers = User::all();
 
         // Lọc danh sách người dùng để chỉ lấy những người dùng chưa kết bạn
         $pendingFriends = $allUsers->whereNotIn('id', $friends->pluck('id'))->limit(0,5);
        
         $arr = [
            'status' => true,
            'message' => 'Danh sách chưa kết bạn',
            'data' => $pendingFriends->map(function ($friend) {
                return [
                    'id' => $friend->id,
                    'name' => $friend->name,
                    'avatar' => $friend->avatar,
                ];
            }),
        ];
         return response()->json($arr,200);
     }

}