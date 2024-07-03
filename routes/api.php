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
use App\Http\Controllers\NotificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;






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

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
  
    return redirect('http://localhost:3000/profile/');

})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
 
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// route đăng nhập 
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// Group middleware authed (yêu cầu người dùng phải đăng nhập rồi)
Route::group(['middleware' => ['auth:sanctum']] , function () 
{
    // route đổi mật khẩu
    Route::put('/changePassword', [AuthController::class, 'changePassword']);
    // route đăng xuất
    Route::get('/logout', [AuthController::class, 'logout']);
    // route refresh token
    Route::get('/refresh', [AuthController::class, 'refresh']);
    // route CRUD cho profile (Create, Read, Update, Delete)
    Route::apiResource('/profile', ProfileController::class, );
    Route::get('/profile/friends/{id_User}', [ProfileController::class, 'getProfileFriendList']);
    // route CRUD cho post (Create, Read, Update, Delete)
    Route::apiResource('/post', PostController::class, );
    Route::get('/getLastestInteract', [NotificationController::class, 'getLastestInteract']);
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
    Route::post('/friend/{userId}', [FriendRequestController::class, 'sendFriendRequest']);
    // Chấp nhận lời mời kết bạn
    Route::put('/friend/{userId}', [FriendRequestController::class, 'acceptFriendRequest']);
    // Xoá bạn bè
    Route::delete('/friend/{userId}', [FriendRequestController::class, 'unfriend']);
    // Danh sách bạn bè
    Route::get('/friend/{usedId}', [FriendRequestController::class, 'getFriendsList']);
    // Xoá lời mời đã gửi đi
    Route::delete('/unfriendrequest/{userId}', [FriendRequestController::class, 'declinesendFriendRequest']);
    // Từ chối lời mời kết bạn 
    Route::delete('/unfriend/{userId}', [FriendRequestController::class, 'declineFriendRequest']);

    Route::get('/friendRequest/{userId}', [FriendRequestController::class, 'getSentFriendRequests']);
    Route::get('/RequestFriend/{usedId}', [FriendRequestController::class, 'getPendingFriendRequests']);
    // Đề nghị kết bạn
    Route::get('/noFriend', [FriendRequestController::class, 'noFriends']);
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
    Route::get('/messages/{idSender}', [MessageController::class, 'getMessages']);
    Route::get('/boxMessages', [MessageController::class, 'getBoxMessages']);
    Route::get('/lastestMessages', [MessageController::class, 'getListNewMessages']);
    // Reset password
    // Route::put('/change-password', [AuthController::class, 'reset']);
});

