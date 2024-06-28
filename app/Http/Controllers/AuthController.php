<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Resources\user as UserResource;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Password;
use App\Models\PasswordReset;



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
    public function destroy(User $id){

        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        // Xóa người dùng
        $user->delete();
        $arr = [
            'status' => true,
            'message' => 'Đã xoá tài khoản',
            'data' => [],
        ];
        return response()->json($arr,200);
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

    public function reset(ChangePasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $use;
                $user->save();

                Auth::login($user);

                // event(new PasswordReset($user));
                return response()->json([
                    'error' => $user,
                ], 200);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Password has been reset successfully.',
            ]);
        }

        return response()->json([
            'error' => $status,
        ], 400);
    }
}
