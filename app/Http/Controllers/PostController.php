<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;
use App\Models\Photo;
use App\Models\Video;
use App\Http\Resources\post as postResource;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $post = Post::latest()->get();
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
        if(!isset($request->all()['photoUrl']) && !isset($request->all()['videoUrl']))
        {
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
                'data' => new postResource($post),
            ];
            return response()->json($arr, 200);
        }
    
            elseif(isset($request->all()['photoUrl']) && !isset($request->all()['videoUrl']))
            {
                $validator = Validator::make($input, 
                    [
                        'content' => 'required|string|max:100',
                        'privacy'=> 'required',
                        'photoUrl' => 'required|file|image|max:2048|mimes:jpeg,png,jpg,gif',
                        'photoUrl' => 'required|array|max:5', // Limit to 5 files
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
                    // $input2 = 
                    //     [
                    //         'photoUrl' => $request->input('photoUrl'),
                    //         'idPost' => null,
                    //     ];
                    $photos = [];
                    // DB::transaction(function () use ($input1, $input2) 
                    //     {
                    $post = Post::create($input1);                           
                            // $input2['idPost'] = $post['id'];
                            foreach ($request->file('photoUrl') as $imageFile) {
                                $fileName = $imageFile->getClientOriginalName();
                                $fileExtension = $imageFile->getClientOriginalExtension();
                                $newFileName = uniqid() . '.' . $fileExtension;
                        
                                try {
                                    $imageFile->move(public_path('uploads/image/'), $newFileName);
                                    $imageUrl = asset('uploads/image/' . $newFileName);
                        
                                    $input2 = [
                                        'photoUrl' => $imageUrl,
                                        'idPost' => $post['id'],
                                    ];
                        
                                    $photo = Photo::create($input2);
                                    $photos[] = $photo;
                                } catch (Exception $e) {
                                    return response()->json([
                                        'status' => false,
                                        'message' => 'Error uploading image: ' . $e->getMessage()
                                    ], 500);
                                }
                            }
                        
                            $arr = [
                                'status' => true,
                                'message' => 'Tạo bài viết thành công',
                                'data' => [
                                    'post' => $post,
                                    'photos' => $photos,
                                ]
                            ];
                            
                    return response()->json($arr, 200);
                        
            }       
                elseif(!isset($request->all()['photoUrl']) && isset($request->all()['videoUrl']))
                {
                    $validator = Validator::make($input, 
                        [
                            'content' => 'required|string|max:100',
                            'privacy'=> 'required',
                            'videoUrl' => 'required|file|mimes:mp4,avi,mov,wmv',
                            'videoUrl' => 'required|array|max:5', // Limit to 5 files
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

                        $videos = [];
                        foreach ($request->file('videoUrl') as $videoFile) {
                            // $videoFile = $request->file('videoUrl');
                            $fileName = $videoFile->getClientOriginalName();
                            $fileExtension = $videoFile->getClientOriginalExtension();
                            $newFileName = uniqid() . '.' . $fileExtension;                                             
                            $videoFile->move(public_path('uploads/video'), $newFileName);// Store the image file in the filesystem
                            $videoUrl = asset('uploads/video/' . $newFileName);// Generate the image URL
        
                            $input2 = 
                                [
                                    'videoUrl' =>  $videoUrl,
                                    'idPost' => $post['id'],
                                ];
                            $video = Video::create($input2);
                            $videos[] = $video;       
                        }  
                        $arr = [              
                            'status' => true,
                            'message' => 'Tạo bài viết thành công' ,
                            'data' => [
                                    'post' => $post,
                                    'videos' => $videos,
                            ]
                            ];
                        return response()->json($arr, 200);
                }
                    else
                    {
                        $validator = Validator::make($input, 
                        [
                            'content' => 'required|string|max:100',
                            'privacy'=> 'required',
                            'photoUrl' => 'required|file|image|max:2048|mimes:jpeg,png,jpg,gif',
                            'photoUrl' => 'required|array|max:5', // Limit to 5 files
                            'videoUrl' => 'required|file|mimes:mp4,avi,mov,wmv',
                            'videoUrl' => 'required|array|max:5', // Limit to 5 files
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

                        $photos = [];
                                foreach ($request->file('photoUrl') as $imageFile) {
                                    $fileName = $imageFile->getClientOriginalName();
                                    $fileExtension = $imageFile->getClientOriginalExtension();
                                    $newFileName = uniqid() . '.' . $fileExtension;
                            
                                    try {
                                        $imageFile->move(public_path('uploads/image/'), $newFileName);
                                        $imageUrl = asset('uploads/image/' . $newFileName);
                            
                                        $input2 = [
                                            'photoUrl' => $imageUrl,
                                            'idPost' => $post['id'],
                                        ];
                            
                                        $photo = Photo::create($input2);
                                        $photos[] = $photo;
                                    } catch (Exception $e) {
                                        return response()->json([
                                            'status' => false,
                                            'message' => 'Error uploading image: ' . $e->getMessage()
                                        ], 500);
                                    }
                                }
                                $videos = [];
                                foreach ($request->file('videoUrl') as $videoFile) {
                                    // $videoFile = $request->file('videoUrl');
                                    $fileName = $videoFile->getClientOriginalName();
                                    $fileExtension = $videoFile->getClientOriginalExtension();
                                    $newFileName = uniqid() . '.' . $fileExtension;                                             
                                    $videoFile->move(public_path('uploads/video'), $newFileName);// Store the image file in the filesystem
                                    $videoUrl = asset('uploads/video/' . $newFileName);// Generate the image URL
                
                                    $input2 = 
                                        [
                                            'videoUrl' =>  $videoUrl,
                                            'idPost' => $post['id'],
                                        ];
                                    $video = Video::create($input2);
                                    $videos[] = $video;       
                                }      
                        $arr = [              
                            'status' => true,
                            'message' => 'Tạo bài viết thành công' ,
                            'data' => [
                                'post' =>$post,
                                'photos' => $photos,
                                'videos' => $videos,
                            ]
                            ];
                        return response()->json($arr, 200);
                    }
    }  

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post = Post::with('photos' ,'videos' )->find($post);
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
                'data' => $post,
                ];
            return response()->json($arr,200);    
        }  
        
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
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
    public function destroy(Post $post)
    {
        try {

            foreach ($post->photos as $image) {
                $imageUrl = $image->photoUrl;
                $pathUrl = parse_url($imageUrl);
                $filename = basename($pathUrl['path']);

                unlink(public_path('/uploads/image/' . $filename));
                }
                
                $post->photos()->delete();    

            foreach ($post->videos as $video) {
    
                $videoUrl = $video->videoUrl;
                $pathUrl = parse_url($videoUrl);
                $filename = basename($pathUrl['path']);
    
                unlink(public_path('/uploads/video/' . $filename));
                }
                $post->videos()->delete();
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
    //     $post = Post::with('images', 'videos')->findOrFail($post);

    // $post->delete();

    // foreach ($post->images as $image) {
    //     $image->delete();
    //     unlink(storage_path('app/public/image/' . $image->path));
    // }

    // foreach ($post->videos as $video) {
    //     $video->delete();
    //     unlink(storage_path('app/public/video/' . $video->path));
    // }

    // return response()->json(['message' => 'Post deleted successfully']);
    }
}