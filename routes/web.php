<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/u/{user:username}', [ProfileController::class, 'index'])->name('profile');

// Route::domain('{account}.127.0.0.1:8000')->group(function () {
//     Route::get('user/{id}', function (string $account, string $id) {
//         return $account;
//     });
// });

Route::middleware('auth')->group(function () {
    Route::post('/profile/update-image', [ProfileController::class, 'updateImage'])->name('profile.updateImage');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //Posts

    Route::post('/post', [PostController::class, 'store'])->name('post.store');
    Route::put('/post/{post}', [PostController::class, 'update'])->name('post.update');
    Route::delete('/post/{post}', [PostController::class, 'destroy'])->name('post.destroy');
    Route::get('/post/download/{attachment}', [PostController::class, 'downloadAttachment'])->name('post.download');
    Route::post('/post/{post}/reaction', [PostController::class, 'postReaction'])->name('post.reaction');
});

require __DIR__.'/auth.php';
