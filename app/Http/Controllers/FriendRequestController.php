<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FriendRequestController extends Controller
{
    public function sendFriendRequest(Request $request, $userId)
    {
        $currentUser = Auth::user();
        $recipientUser = User::find($userId);

        // Kiểm tra người dùng hiện tại và người nhận có phải là bạn bè hay không
        if ($currentUser->isFriendsWith($recipientUser)) {
            return response()->json([
                'message' => 'Bạn đã là bạn bè với người dùng này.',
            ], 400);
        }

        // Gửi yêu cầu kết bạn
        $currentUser->friendRequests()->attach($recipientUser->id);

        return response()->json([
            'message' => 'Yêu cầu kết bạn đã được gửi thành công.',
        ], 200);
    }

    public function acceptFriendRequest(Request $request, $userId)
    {
        $currentUser = Auth::user();
        $friendRequest = $currentUser->friendRequests()->where('friend_id', $userId)->first();

        if (!$friendRequest) {
            return response()->json([
                'message' => 'Yêu cầu kết bạn không được tìm thấy.',
            ], 404);
        }

        // Chấp nhận yêu cầu kết bạn
        $friendRequest->pivot->update(['status' => 'accepted']);

        return response()->json([
            'message' => 'Yêu cầu kết bạn đã được chấp nhận.',
        ], 200);
    }
    public function declineFriendRequest(Request $request, $userId)
    {
        $currentUser = Auth::user();
        $friendRequest = $currentUser->friendRequests()->where('friend_id', $userId)->first();

        if (!$friendRequest) {
            return response()->json([
                'message' => 'Yêu cầu kết bạn không được tìm thấy.',
            ], 404);
        }

        // Từ chối yêu cầu kết bạn
        $friendRequest->delete();

        return response()->json([
            'message' => 'Yêu cầu kết bạn đã bị từ chối.',
        ], 200);
    }

    public function getFriendsList(Request $request)
    {
        $currentUser = Auth::user();
        $friends = $currentUser->friends;

        return response()->json([
            'friends' => $friends->map(function ($friend) {
                return [
                    'id' => $friend->id,
                    'name' => $friend->name,
                    ];
                }),
            ], 200);
    }

    // Lấy danh sách yêu cầu kết bạn đã gửi (yêu cầu đi)
    public function getSentFriendRequests(Request $request)
    {
        $currentUser = Auth::user();
        $sentRequests = $currentUser->friendRequestsSent()->get();

        // Biến đổi dữ liệu yêu cầu đã gửi (tùy chọn)
        // Bạn có thể sử dụng hàm map tương tự như getFriendsList để
        // tùy chỉnh cấu trúc dữ liệu của yêu cầu đã gửi
        $transformedSentRequests = $sentRequests->map(function ($request) {
            return [
                'id' => $request->id,
                'recipient' => [ // Thông tin người nhận
                    'id' => $request->recipient->id,
                    'name' => $request->recipient->name,
                    // Bạn có thể thêm các thuộc tính khác
                ],
                'created_at' => $request->created_at,
                'updated_at' => $request->updated_at,
            ];
        });

        return response()->json([
            'sent_requests' => $transformedSentRequests,
        ], 200);
    }

     // Lấy danh sách yêu cầu kết bạn đang chờ (yêu cầu đến)
     public function getPendingFriendRequests(Request $request)
     {
         $currentUser = Auth::user();
         $pendingRequests = $currentUser->friendRequestsReceived()->where('status', 'pending')->get();
 
         // Biến đổi dữ liệu yêu cầu đang chờ (tùy chọn)
         // Bạn có thể sử dụng hàm map tương tự như getFriendsList để
         // tùy chỉnh cấu trúc dữ liệu của yêu cầu đang chờ
         $transformedPendingRequests = $pendingRequests->map(function ($request) {
             return [
                 'id' => $request->id,
                 'sender' => [ // Thông tin người gửi
                     'id' => $request->sender->id,
                     'name' => $request->sender->name,
                     // Bạn có thể thêm các thuộc tính khác
                 ],
                 'created_at' => $request->created_at,
                 'updated_at' => $request->updated_at,
             ];
         });
 
         return response()->json([
             'pending_requests' => $transformedPendingRequests,
         ], 200);
     }
}