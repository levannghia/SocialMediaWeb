<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/update-file-demo', function (Request $request) {
    // Kiểm tra xem request có chứa file không
    $data = $request->all();
    if ($request->hasFile('image')) {
        $file = $request->file('image');

        // Kiểm tra xem file có lỗi không
        if ($file->isValid()) {
            // Lưu file vào thư mục 'test-demo' trong thư mục 'public'
            $path = $file->store('test-demo', 'public');

            return response()->json([
                'path' => $path,
            ], 200);
        } else {
            // Xử lý lỗi nếu file không hợp lệ
            return response()->json([
                'error' => 'Invalid file.',
            ], 400);
        }
    } else {
        // Xử lý trường hợp không có file được tải lên
        return response()->json([
            'error' => 'No file uploaded.',
        ], 400);
    }
});

Route::prefix('auth')->name('api.auth.')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login-google', [AuthController::class, 'loginWithGoogle'])->name('login.google');
    Route::post('/verification', [AuthController::class, 'verificationOTP'])->name('verification');
    Route::post('/resen-verification', [AuthController::class, 'resendVerificationOTP'])->name('resendVerification');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/list-user', [UserController::class, 'getAllUser'])->name('listUser');
    });
    Route::prefix('event')->name('event.')->group(function () {
        Route::post('/store', [EventController::class, 'store'])->name('store');
    });
});



Route::get('/test', function () {
    return response()->json([
        'test' => 'OK2',
    ], 200);
});