<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Story;
use App\Models\Photo;
use App\Models\Video;
use App\Models\LikeStory;
use App\Http\Resources\story as storyResource;

class StoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $story = Story::with('photos','videos','likestorys')->where('privacy',0)->latest()->get();
        $id = auth()->user()->id ;
        $storyUse = Story::with('photos','videos','likestorys')->where('id_User',$id)->latest()->get();
        $arr = [
            'status' => true,
            'message' => 'danh sách các story',
            'data' => [
                'All' => storyResource::collection($story),
                'of' => storyResource::collection($storyUse),
                // 'of' => $storyUse,
            ]
        ];
        return response()->json($arr,200);
    }

    public function Storyuse($id = 0)
    {
        $id = auth()->user()->id ;
        $story = Story::with('photos','videos','likestorys')->where('id_User',$id)->latest();
        $arr = [
            'status' => true,
            'message' => 'danh sách các story',
            'data' => storyResource::collection($story),
        ];
        return response()->json($arr,200);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
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
                    'privacy' => $request->input('privacy'),
                    'id_User' => $user->id,
                ];
            $story = Story::create($input1);
            $arr = [              
                'status' => true,
                'message' => 'Tạo Story thành công' ,
                'data' => new storyResource($story),
            ];
            return response()->json($arr, 200);
        }
    
            elseif(isset($request->all()['photoUrl']) && !isset($request->all()['videoUrl']))
            {
                $validator = Validator::make($input, 
                    [
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
                            'privacy' => $request->input('privacy'),
                            'id_User' => $user->id,
                        ];
                    // $input2 = 
                    //     [
                    //         'photoUrl' => $request->input('photoUrl'),
                    //         'id_Post' => null,
                    //     ];
                    $photos = [];
                    // DB::transaction(function () use ($input1, $input2) 
                    //     {
                    $story = Story::create($input1);                           
                            // $input2['id_Post'] = $post['id'];
                            foreach ($request->file('photoUrl') as $imageFile) {
                                $fileName = $imageFile->getClientOriginalName();
                                $fileExtension = $imageFile->getClientOriginalExtension();
                                $newFileName = uniqid() . '.' . $fileExtension;
                        
                                try {
                                    $imageFile->move(public_path('uploads/image/'), $newFileName);
                                    $imageUrl = asset('uploads/image/' . $newFileName);
                        
                                    $input2 = [
                                        'photoUrl' => $imageUrl,
                                        'id_Story' => $story['id'],
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
                                'message' => 'Tạo Story thành công',
                                'data' => [
                                    'story' => $story,
                                    'photos' => $photos,
                                ]
                            ];
                            
                    return response()->json($arr, 200);
                        
            }       
                elseif(!isset($request->all()['photoUrl']) && isset($request->all()['videoUrl']))
                {
                    $validator = Validator::make($input, 
                        [
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
                                'privacy' => $request->input('privacy'),
                                'id_User' => $user->id,
                            ];
                        $story = Story::create($input1);

                        $videos = [];
                        foreach ($request->file('videoUrl') as $videoFile) {
                            // $videoFile = $request->file('videoUrl');
                            $fileName = $videoFile->getClientOriginalName();
                            $fileExtension = $videoFile->getClientOriginalExtension();
                            $newFileName = uniqid() . '.' . $fileExtension;                                             
                            $videoFile->move(public_path('uploads/videostory'), $newFileName);// Store the image file in the filesystem
                            $videoUrl = asset('uploads/videostory/' . $newFileName);// Generate the image URL
        
                            $input2 = 
                                [
                                    'videoUrl' =>  $videoUrl,
                                    'id_Story' => $story['id'],
                                ];
                            $video = Video::create($input2);
                            $videos[] = $video;       
                        }  
                        $arr = [              
                            'status' => true,
                            'message' => 'Tạo Story thành công' ,
                            'data' => [
                                    'story' => $story,
                                    'videos' => $videos,
                            ]
                            ];
                        return response()->json($arr, 200);
                }
                    else
                    {
                        $validator = Validator::make($input, 
                        [
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
                                'privacy' => $request->input('privacy'),
                                'id_User' => $user->id,
                            ];               
                        $story = Story::create($input1);

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
                                            'id_Story' => $story['id'],
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
                                    $videoFile->move(public_path('uploads/videostory'), $newFileName);// Store the image file in the filesystem
                                    $videoUrl = asset('uploads/videostory/' . $newFileName);// Generate the image URL
                
                                    $input2 = 
                                        [
                                            'videoUrl' =>  $videoUrl,
                                            'id_Story' => $story['id'],
                                        ];
                                    $video = Video::create($input2);
                                    $videos[] = $video;       
                                }      
                        $arr = [              
                            'status' => true,
                            'message' => 'Tạo Story thành công' ,
                            'data' => [
                                'story' =>$story,
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
    public function show(Story $story)
    {
        $story = $story->with('photos','videos','likestorys',)->where('privacy',0)->find($story);
        if(is_null($story))
            {
                $arr = [
                    'success' => false,
                    'message' => 'Không có story này',
                    'data' => [],
                    ];
                return response()->json($arr,404);        
            }
        else
        {
            $arr = [
                'success' => True,
                'message' => 'Chi tiết bài viết',
                'data' => storyResource::collection($story),
                ];

            return response()->json($arr,200);    
        }  
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Story $story)
    {
        $idUser = auth()->user(); 
        $input = $request->all();
        $validator = Validator::make($input,[
            'privacy' => 'required',
        ]);
        if($validator -> fails())
            {
                $arr = [
                    'success' => false,
                    'message' => 'Lỗi kiểm tra dữ liệu',
                    'data' => $validator -> errors(),$input,
                ];
                return response()->json($arr,400);
            }
        elseif ($idUser->id !== $story->id_User) {
            return response()->json(['error' => 'Unauthorized to edit this story'], 403);
        }
        else
            {
                $story -> privacy = $input['privacy'];
                $story -> save();
                $arr = [
                    'status' => true,
                    'message' => 'Sửa bài viết thành công',
                    'data' => new storyResource($story),
                ];
                return response()->json($arr,200);
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Story $story)
    {
        try {
            $idUser = auth()->user();
            if ($idUser->id !== $story->id_User) {
                return response()->json(['error' => 'Unauthorized to destroy this story'], 403);
            } 
            foreach ($story->photos as $image) {
                $imageUrl = $image->photoUrl;
                $pathUrl = parse_url($imageUrl);
                $filename = basename($pathUrl['path']);

                unlink(public_path('/uploads/imagestorys/' . $filename));
                }
                
                $story->photos()->delete();    

            foreach ($story->videos as $video) {
    
                $videoUrl = $video->videoUrl;
                $pathUrl = parse_url($videoUrl);
                $filename = basename($pathUrl['path']);
    
                unlink(public_path('/uploads/videostorys/' . $filename));
                }
                $story->videos()->delete();
                $story->likestorys()->delete();
                $story->delete();
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
