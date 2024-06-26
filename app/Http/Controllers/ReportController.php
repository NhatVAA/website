<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\User;
use App\Models\Post;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $reports = Post::find($postId)->reports;
    
        //     return response()->json($reports);
        $reports = Report::all();
    
        return response()->json($reports);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, 
        [
            'id_Post' => 'required',
            'reason' => 'required',
        ]);
        $id_User = Auth::user()->id;
        $report = [
            'id_Post' => $request->input('id_Post'),
            'id_User' => $id_User,
            'reason' => $request->input('reason'),
        ];
        if ($validator->fails()) 
            {
                $arr = [              
                    'status' => false,
                    'message' => 'Thông tin chưa chính xác' ,
                    'data' => [$validator->errors()],
                ];
                return response()->json($arr,404);
            }
            // Lưu báo cáo vào database
            $report = Report::create($report);
            $arr = [
                'status' => true,
                'message' => 'Báo cáo thành công',
                'data' => $report,
            ];
            return response()->json($arr,201);
        

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
