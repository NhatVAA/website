<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Resources\user as userResource;
use App\Models\Friendship;
use App\Models\Post;
use Exception;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Hàm trả về dữ liệu cá nhân của người đăng nhập
    public function index()
    {   
        //
        if((auth()->user()) != null){
            $idUser = auth()->user()->id;
            $userInfo = User::all()->find($idUser); // thông tin của User đang đăng nhập 
            $userPost = Post::with('photos','videos','user','comments','likes')->where('id_User',$idUser)->get(); // các bài viết của User đang đăng nhập
            $userFriend = Friendship::where(function ($query) use ($idUser) {
                $query->where('id_User', $idUser)
                      ->orWhere('id_friend', $idUser);
            })->where('status', 1)->get();

            $arr = [
                'status' => true,
                'message' => 'Xin chào '.$userInfo->name,
                'data' => [
                    'user' => [$userInfo],
                    'posts' => $userPost,
                    'friends' => $userFriend,
                ],
            ];
            return response()->json($arr, 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Trang cá nhân này không tồn tại',
                'data' => [],
            ], 404);
        }
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    // Hàm trả về dữ liệu cá nhân (với Id_User truyền vào)
    public function show(string $id)
    {
        //
        $userInfo = User::all()->find($id); // thông tin của user có ID đc truyền vào
        $userPost = Post::with('photos','videos','user','comments', 'likes',)->where('id_User',$id)->where('privacy',0)->get(); // các bài viết công khai của user có ID đc truyền vào
        // danh sách bạn bè của user có ID đc truyền vào
        $userFriend = Friendship::where(function ($query) use ($id) {
            $query->where('id_User', $id)
                  ->orWhere('id_friend', $id);
        })->where('status', 1)->get();
        //
        if(is_null($userInfo)){
            $arr = [
                'status' => false,
                'message' => 'Trang cá nhân không tồn tại',
                'data' => [],
            ];
            return response()->json($arr, 404);
        }
        else{
            $arr = [
                'status' => true,
                'message' => 'Dữ liệu trang cá nhân',
                'data' => [
                    'user' => [$userInfo],
                    'posts' => $userPost,
                    'friends' => $userFriend,
                ],
            ];
            return response()->json($arr, 200);
        }
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
    // Hàm cập nhật Trang cá nhân (chỉ có thể cập nhật trang cá nhân của mình)
    public function update(Request $request, string $id)
    {
        $userId = $id;
        if($userId != (auth()->user()->id)){
            $arr = [
                'status' => false,
                'message' => 'Bạn không có quyền chỉnh sửa trang cá nhân người dùng khác!',
                'data' => [],
            ];
            return response()->json($arr, 400);
        }
        $user = User::find($userId);
        //
        if(is_null($user)){
            $arr = [
                'status' => false,
                'message' => 'Trang cá nhân không tồn tại',
                'data' => [],
            ];
            return response()->json($arr, 404);
        }
        else{
            // tên, sđt, ngày sinh
            if(isset($request->all()['name'])){
                $user->name = $request->input('name');
            }
            if(isset($request->all()['phoneNumber'])){
                $user->phoneNumber = $request->input('phoneNumber');
            }
            if(isset($request->all()['birth'])){
                $user->birth = $request->input('birth');
            }
            // ảnh đại diện
            if(isset($request->all()['avatar'])){
                $avatar = $request->file('avatar');
                // $avatarfileName = $avatar->getClientOriginalName();
                $avatarfileExtension = $avatar->getClientOriginalExtension();
                $avatarnewFileName = uniqid() . '.' . $avatarfileExtension;                                             
                $avatar->move(public_path('uploads/image'), $avatarnewFileName); // Store the avatar file in the filesystem
                $avatarUrl = asset('uploads/image/' . $avatarnewFileName); // Generate the avatar URL
                $user->avatar = $avatarUrl;
            }
            // ảnh bìa
            if(isset($request->all()['coverimage'])){
                $coverimage = $request->file('coverimage');
                // $avatarfileName = $avatar->getClientOriginalName();
                $coverimagefileExtension = $coverimage->getClientOriginalExtension();
                $coverimagenewFileName = uniqid() . '.' . $coverimagefileExtension;                                             
                $coverimage->move(public_path('uploads/image'), $coverimagenewFileName); // Store the coverimage file in the filesystem
                $coverimageUrl = asset('uploads/image/' . $coverimagenewFileName); // Generate the coverimage URL
                $user->coverimage = $coverimageUrl;
            }
            //
            $result = $user->save();
            if($result){
                $user = User::find($userId);
                $arr = [
                    'status' => true,
                    'message' => 'Cập nhật trang cá nhân thành công',
                    'data' => [
                        'user' => $user,
                    ],
                ];
                return response()->json($arr, 200);
            }else{
                $arr = [
                    'status' => false,
                    'message' => 'Cập nhật trang cá nhân không thành công',
                    'data' => [],
                ];
                return response()->json($arr, 400);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    // Hàm lấy ra list bạn bè của trang cá nhân với id_User truyền vào
    public function getProfileFriendList(string $id){
        $profileFriendList = Friendship::with('user')->where(function($query) use ($id) {
            $query->where('id_User', $id)
                  ->orWhere('id_friend', $id);
        })
        ->where('status', 1)
        ->get();
        //
        if($profileFriendList){
            $arr = [
                'status' => true,
                'message' => 'Danh sách bạn bè của người dùng này',
                'data' => $profileFriendList,
            ];
            return response()->json($arr, 200);
        }else{
            $arr = [
                'status' => false,
                'message' => 'Người dùng này chưa có bạn bè nào',
                'data' => [],
            ];
            return response()->json($arr, 204);
        }
       
    }
}
