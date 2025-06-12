<?php


use App\Http\Controllers\CommentController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\UserMediaController;
use App\Http\Controllers\PodcastController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/register', [AuthController::class, 'register']);

Route::post('/passwordForgot', [AuthController::class, 'forgotPassword']);

Route::post('/verify-code', [AuthController::class, 'verifyCode']);

Route::post('/resend-code', [AuthController::class, 'resendCode']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
});

Route::post('/verify-2fa', [AuthController::class, 'verify2FA']);

Route::post('/reset-password', [AuthController::class, 'resetPassword']);



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/UserMedia', [UserMediaController::class, 'store']);
    Route::get('/UserMedia', [UserMediaController::class, 'show']);
});





Route::middleware('auth:sanctum')->group(function () {
    Route::post('/StoreChannel', [ChannelController::class, 'store']);
    Route::get('/ShowChannel', [ChannelController::class, 'index']);
});




/*Route::middleware('auth:sanctum')->group(function () {
    Route::post('/StoreAudio', [PodcastController::class, 'store']);
    Route::get('/ShowAudio/{id}', [PodcastController::class, 'index']);
});
*/




Route::middleware('auth:sanctum')->group(function () {
    Route::post('/podcasts', [PodcastController::class, 'store']);
    Route::post('/podcasts/{id}/like', [PodcastController::class, 'toggleLike']);
});

Route::get('/channels/{id}/podcasts', [PodcastController::class, 'index']);
Route::get('/podcasts/random', [PodcastController::class, 'randomList']); // بودكاستات عشوائية
Route::get('/podcasts/{podcastId}', [PodcastController::class, 'show']); // بودكاست مع تعليقاته







Route::middleware('auth:sanctum')->group(function () {
    Route::post('/comments', [CommentController::class, 'store']);
});

Route::get('/podcasts/{podcastId}/comments', [CommentController::class, 'index']);



Route::get('/create-token', function () {
    $user = User::factory()->create();

    return response()->json($user->createToken('auth_token')->plainTextToken);
});
