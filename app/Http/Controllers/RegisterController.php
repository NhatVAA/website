<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\user as ResourceUser;


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
        ];
        $user = User::create($input);
        $arr = [
            'status' => true,
            'message' => 'Đăng ký thành công!',
            'data' => new ResourceUser($user),
        ];
        return response()->json($arr, 201);
    }

}
