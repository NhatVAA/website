<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\post;
use App\Http\Resources\post as postResource;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $post = post::latest()->get();
        $arr = [
            'status' => true,
            'message' => 'danh sách các bài viết',
            'data' => postResource::collection($post),
        ];
        return response()->json($arr,200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, 
        [
            'content' => 'required|string|max:100',
            'privacy'=> 'required',
        ]);

        if ($validator->fails()) 
        {
            $arr = [              
                    'status' => false,
                    'message' => 'Thông tin chưa chính xác' ,
                    'data' => [$validator->errors()],
            ];
            return response()->json($arr,404);
        }
        $user = Auth::user(); // Lấy thông tin người dùng đã đăng nhập
        $input1 = 
            [   
                'content' => $request->input('content'),
                'privacy' => $request->input('privacy'),
                'idUser' => $user->id,
            ];
        $post = Post::create($input1);
        $arr = [              
            'status' => true,
            'message' => 'Tạo bài viết thành công' ,
            'data' => $post,
    ];
        return response()->json($arr, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($post = 0)
    {
        $post = post::findOrFail($post);
        if(is_null($post))
            {
                    $arr = [
                        'success' => false,
                        'message' => 'Không có bài viết này',
                        'data' => [],
                    ];
                    return response()->json($arr,404);        
            }
        else
        {
            $arr = [
                'success' => True,
                'message' => 'Chi tiết bài viết',
                'data' => new postResource($post),
                ];
        return response()->json($arr,200);    
        }  
        
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, post $post)
    {
        $input = $request->all();
        $validator = Validator::make($input,[
            'content' => 'required|string|max:100',
            'privacy' => 'required',
        ]);
        if($validator -> fails())
            {
                $arr = [
                    'success' => false,
                    'message' => 'Lỗi kiểm tra dữ liệu',
                    'data' => $validator -> errors(),
                ];
                return response()->json($arr,400);
            }
        else
            {
                $post -> content = $input['content'];
                $post -> privacy = $input['privacy'];
                $post -> save();
                $arr = [
                    'status' => true,
                    'message' => 'Sửa bài viết thành công',
                    'data' => new postResource($post),
                ];
                return response()->json($arr,200);
            }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(post $post)
    {
        try {
            $post->delete();
            $arr = [
                'status' => true,
                'message' => 'Bài viết đã được xoá',
                'data' => [],
            ];
            return response()->json($arr,200);  
        }
        
        catch (\Exception $e) {
                
                        $arr = [
                            'success' => false,
                            'message' => 'Lỗi chưa xoá được'. $e->getMessage(),
                            'data' => [],
                        ];
                        return response()->json($arr,404);
        }      
    }
}