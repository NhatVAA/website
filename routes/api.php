<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Api;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Laravel\Sanctum\Sanctum;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\StoryController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// route đăng ký
Route::post('/register', [RegisterController::class, 'register']);
// route đăng nhập 
Route::post('/login', [AuthController::class, 'login']);

// Group middleware authed (yêu cầu người dùng phải đăng nhập rồi)
Route::group(['middleware' => ['auth:sanctum']] , function () 
{
    // route đăng xuất
    Route::post('/logout', [AuthController::class, 'logout']);
    // route refresh token
    Route::post('/refresh', [AuthController::class, 'refresh']);
    // route CRUD cho profile (Create, Read, Update, Delete)
    Route::apiResource('/profile', ProfileController::class, );
    // route CRUD cho post (Create, Read, Update, Delete)
    Route::apiResource('/post', PostController::class, );

    // *** phần Comment ***
    // Route::apiResource('/comment', CommentController::class, );
    // Lấy ra các Comment của bài viết (với Id bài viết)
    // ...
    // Thêm Comment vào bài viết (với Id bài viết)
    Route::post('/comment/{idPost}', [CommentController::class, 'store']);
    // Sửa Comment (với Id của Comment)
    Route::put('/comment/{comment}', [CommentController::class, 'update']);
    // Xóa Comment (với Id của Comment)
    Route::delete('/comment/{comment}', [CommentController::class, 'destroy']);
    // *** hết phần Comment ***

    // *** phần Like ***
    // Lấy ra các Like của bài viết (với Id bài viết)
    // ...
    // Thêm Like / Bỏ Like cho bài viết (với Id bài viết)
    Route::post('/like/{postId}', [LikeController::class, 'store']);
    // *** hết phần Like ***
    // route cho Story
    Route::apiResource('/story', StoryController::class, );
    // Route::get('/story', [StoryController::class, 'Storyuse']);
});

