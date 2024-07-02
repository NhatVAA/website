<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\user as ResourceUser;
use App\Mail\VerifyEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // $request->validate([
        //     'name' => 'required',
        //     'email' => 'required|email',
        //     'password' => 'required',
        //     'phoneNumber' => 'required',
        //     'birth' => 'required',
        //     'gender' => 'required',
        // ]);
        $input = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'phoneNumber' => $request->input('phoneNumber'),
            'birth' => $request->input('birth'),
            'gender' => $request->input('gender'),
            'role' => 2001, // 2001 là mã quyền của người dùng bình thường, 5150 là của admin
        ];

        try{
            $user = User::create($input);
            $token= $user->createToken('auth_token')->plainTextToken;
            $arr = [
                'status' => true,
                'message' => 'Đăng ký thành công!',
                'data' => [
                            // new ResourceUser($user),
                            'access_token' => $token, 
                            'token_type' => 'Bearer'
                          ],
            ];
            
            event(new Registered($user)); 

            return response()->json($arr, 201);
        }
        catch (\Exception $e) {
            $arr = [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ];
            return response()->json($arr, 404);
          }
    }
        

}
