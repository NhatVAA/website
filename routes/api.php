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
use App\Http\Controllers\LikeController;
use App\Http\Controllers\LikeStoryController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MessageController;




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
    Route::get('/logout', [AuthController::class, 'logout']);
    // route refresh token
    Route::get('/refresh', [AuthController::class, 'refresh']);
    // route CRUD cho profile (Create, Read, Update, Delete)
    Route::apiResource('/profile', ProfileController::class, );
    Route::get('/profile/friends/{id_User}', [ProfileController::class, 'getProfileFriendList']);
    // route CRUD cho post (Create, Read, Update, Delete)
    Route::apiResource('/post', PostController::class, );

    // *** phần Comment ***
    // Route::apiResource('/comment', CommentController::class, );
    // Lấy ra các Comment của bài viết (với Id bài viết)
    // ...
    // Lấy danh sách comment của bài viết (với ID bài viết)
    Route::get('/comment/{idPost}', [CommentController::class, 'show']);
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
    Route::get('/like/{postId}', [LikeController::class, 'store']);
    // Lấy danh sách lượt Like của bài viết (với ID bài viết)
    Route::get('/likes/{postId}', [LikeController::class, 'show']);
    // *** hết phần Like ***
    Route::post('/likestory/{storyId}', [LikeStoryController::class, 'store']);
    // route cho Story
    Route::apiResource('/story', StoryController::class, );
    // Route::get('/story', [StoryController::class, 'Storyuse']);
    // *** Phần Friends ***
    // Route::get('/friends', [FriendRequestController::class, 'getFriendsList']);
    Route::post('/friend/{postId}', [FriendRequestController::class, 'sendFriendRequest']);
    Route::put('/friend/{postId}', [FriendRequestController::class, 'acceptFriendRequest']);
    // Xoá bạn bè
    Route::delete('/friend/{postId}', [FriendRequestController::class, 'unfriend']);
    // Danh sách bạn bè
    Route::get('/friend', [FriendRequestController::class, 'getFriendsList']);
    // Xoá lời mời đã gửi đi
    Route::delete('/unfriendrequest/{postId}', [FriendRequestController::class, 'declinesendFriendRequest']);
    // Từ chối kết bạn 
    Route::delete('/unfriend/{postId}', [FriendRequestController::class, 'declineFriendRequest']);

    Route::get('/friendRequest', [FriendRequestController::class, 'getSentFriendRequests']);
    Route::get('/RequestFriend', [FriendRequestController::class, 'getPendingFriendRequests']);
    // Đề nghị kết bạn
    Route::get('/noFriend', [FriendRequestController::class, 'getPendingFriends']);
    // Tìm kiếm
    Route::post('/search', [SearchController::class, 'search']);
    // Report cho user truyền id_Post với thông tin report vào nhé.
    Route::post('/report', [ReportController::class, 'store']);
    // *** Admin *** //
    // Hiển thị Report cho admin.
    Route::get('/report', [ReportController::class, 'index']);
    // Xoá bài viết cho admin.
    Route::delete('/admindelete/{post}', [PostController::class, 'Admindestroy']);
    // Các thông tin tài khoản user.
    Route::get('/userAdmin', [AuthController::class, 'index']);
    // Xoá tài khoản dành cho admin.
    Route::delete('/userAdmin/{id}', [AuthController::class, 'destroy']);
    // 
    Route::post('/messages', [MessageController::class, 'sendMessage']);
    Route::get('/messages', [MessageController::class, 'getMessages']);
    Route::post('/change-password', [AuthController::class, 'reset']);
});

