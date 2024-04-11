<?php

use App\Http\Controllers\Api\V1\AuthController;
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

Route::prefix('auth')->name('api.auth.')->group(function(){
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login-google', [AuthController::class, 'loginWithGoogle'])->name('login.google');
    Route::post('/verification', [AuthController::class, 'verificationOTP'])->name('verification');
    Route::post('/resen-verification', [AuthController::class, 'resendVerificationOTP'])->name('resendVerification');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
});

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
});

Route::get('/test', function (){
    return response()->json([
        'test' => 'OK2',
    ],200);
});