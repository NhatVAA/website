<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Resources\user as userResource;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Hàm get ra dữ liệu cá nhân của người đăng nhập (dùng làm Trang cá nhân)
    public function index()
    {
        //
        if(auth()->user() != null){
            $user = auth()->user();
            $user = userResource::collection($user);
            $arr = [
                'status' => true,
                'message' => 'Xin chào '.$user->name,
                'data' => [
                    'user' => $user,
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
    // Hàm get ra dữ liệu cá nhân của người khác (dùng làm Trang cá nhân của người khác)
    public function show(string $id)
    {
        //
        $user = User::all()->find($id);
        $arr = [
            'status' => true,
            'message' => 'Xin chào '.$user->name,
            'data' => [
                'user' => $user,
            ],
        ];
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
