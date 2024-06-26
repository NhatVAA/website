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
use App\Http\Controllers\FriendRequestController;


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
//
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Group middleware authed (yêu cầu người dùng phải đăng nhập rồi)
Route::group(['middleware' => ['auth:sanctum']] , function () 
{
    Route::apiResource('/profile', ProfileController::class, );
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('/post', PostController::class, );
    Route::apiResource('/comment/{idPost}', CommentController::class, );
    Route::post('/like/{postId}', [LikeController::class, 'store']);
    Route::post('/friend/{userId}', [FriendRequestController::class, 'sendFriendRequest']);
    
});

