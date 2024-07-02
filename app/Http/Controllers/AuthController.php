<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use App\Models\LikeStory;
use App\Models\Message;
use App\Models\Notification;
use App\Http\Resources\user as UserResource;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Password;
use App\Models\PasswordResets;
use Illuminate\Support\Facades\DB;
use App\Models\personal_access_tokens;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(){
        //
    }

    // public function index(){
        
    //     // if(Auth::id() > 0){
    //     //     return redirect()->route('dashboard.index');
    //     // }
    //     // return view('backend.auth.login');
    // }
    public function index(){
        $users = User::paginate(10);
        $arr = [
            'status' => true,
            'message' => 'Thông tin tài khoản các user',
            'data' => UserResource::collection($users),
        ];
        return response()->json($arr,200);
    }
    public function destroy(User $user){

        // $user = User::find($user);
        DB::beginTransaction();

        try {
            // $user = User::find($id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            $user->posts()->delete();
            $user->comments()->delete();
            $user->friend()->delete();
            $user->likes()->delete();
            $user->likestorys()->delete();
            $user->reports()->delete();
            $user->storys()->delete();
            $user->messages()->delete();
            $user->notifications()->delete();
            $user->personal_access_tokens()->delete();
            $user->delete();

            // Commit transaction nếu không có lỗi
            DB::commit();

            // Trả về thông báo thành công
            return response()->json(['message' => 'User and related records deleted successfully'], 200);
        } catch (\Exception $e) {
            // Nếu có lỗi, rollback transaction và trả về thông báo lỗi
            DB::rollback();
            return response()->json(['message' => 'Failed to delete user and related records', 'error' => $e->getMessage()], 500);
        }
    
    }

    public function login(AuthRequest $request){

        // $validated = $request->validate([
        //     'email' => 'required',
        //     'password' => 'required',
        // ]);
        $credentials = [
            'email' => $request -> input('email'),
            'password' => $request -> input('password')
        ];

        //dd($credential);
        if (Auth::attempt($credentials))
        {   
            $user = User::where('email', $request['email'])->firstOrFail(); 
            $token= $user->createToken('auth_token')->plainTextToken;
            $arr = [
                'success' => True,
                'message' => 'Chào '.$user->name.'',
                'data' => [
                    'access_token' => $token, 
                    'token_type' => 'bearer',
                    'user' => $user,
                ],
            ];
            return response()->json($arr,200);
        }
            // $request->session()->regenerate();
            $arr = [
                'success' => false,
                'message' => 'Đăng nhập thất bại',
                'data' => [],
            ];
            return response()->json($arr,401);
            
        
    }
    public function logout()  {
        auth()->user()->tokens()->delete();
        Auth::logout();
        $arr = [
            'success' => true,
            'message' => 'Bạn đã thoát và token đã xóa',
            'data' => [],
        ];
        return response()->json($arr,201);
    }

    // Refresh token
    public function refresh()
    {
        $user = User::where('id',auth()->user()->id)->firstOrFail(); 
        $token= $user->createToken('auth_token')->plainTextToken;
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        $user = User::where('id',auth()->user()->id)->firstOrFail(); 
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 3600,
            'user' => $user,
        ]);
    }
    // public function changePassword(ChangePasswordRequest $request)
    // {
    //     $user = auth()->user();

    //     if (!Hash::check($request->current_password, $user->password)) 
    //     {
    //         $arr = [
    //             'status' => false,
    //             'message' => 'Current password is incorrect.',
    //             'data' => [],
    //         ];
    //         return response()->json($arr, 401);
    //     }

    //     $user->password = Hash::make($request->new_password);
    //     $user->save();
    //     $arr = [
    //         'status' => true,
    //         'message' => 'Password changed successfully.',
    //         'data' => [],
    //     ];
    //     return response()->json($arr,200);
    // }

    public function reset(Request $request)
    {
        // $request->validate([
        //     'current_password' => 'required',
        //     'password' => 'required|min:8|confirmed',
        // ]);

        $user = auth()->user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json([
                'message' => __('Incorrect current password.'),
            ], 400);
        }

        $status = Password::reset(
            compact('user'), // Pass user object in a compact array
            function ($users, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                // event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? response()->json(['message' => __($status)], 200)
                    : response()->json(['message' => __($status)], 400);
    }
}
