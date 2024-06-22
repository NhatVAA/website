<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


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
                    'token_type' => 'Bearer',
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
}
