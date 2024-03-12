<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// use Inertia\Inertia;
// use Goutte\Client;

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
Route::get('/g/{group:slug}', [GroupController::class, 'profile'])->name('group.profile');

// Route::domain('{account}.127.0.0.1:8000')->group(function () {
//     Route::get('user/{id}', function (string $account, string $id) {
//         return $account;
//     });
// });

// Route::get('/crawl', function () {
//     // Khởi tạo một đối tượng Client
//     // $client = new Client();

//     // // URL của trang web cần crawl
//     // $url = 'https://www.facebook.com/groups/543629622444787/';

//     // // Sử dụng Client để gửi yêu cầu GET và nhận nội dung HTML của trang web
//     // $crawler = $client->request('GET', $url);

//     // // Tìm tất cả các thẻ <h1> trong nội dung HTML
//     // $h1_tags = $crawler->filter('h1');

//     // print_r($h1_tags);
//     // // echo '<pre>' . $h1_tags . '</pre>';
//     // // Lặp qua từng thẻ <h1> và hiển thị nội dung
//     // $h1_tags->each(function ($node) {
//     //     echo $node->text() . '<br>';
//     // });

//     // $output = shell_exec('node test');
//     // return response()->json(['result' => $output]);

//     //Truy cập đến trang web https://www.newsweek.com/world bằng phương thức get
//     $response = Http::get('https://www.facebook.com/groups/543629622444787/');
//     //Sử dụng DOMDocument để lấy body của trang web ở đây là mình sẽ lấy tất cả HTML text của trang https://www.newsweek.com/world
//     //Sau đó convert text về UTF-8 để tránh vì lỗi font chữ
//     $dom = new \DOMDocument();
//     @$dom->loadHTML(mb_convert_encoding((string) $response->body(), 'HTML-ENTITIES',  'UTF-8'));
//     //Xem dữ liệu nhận được
//     $xpath = new \DOMXPath($dom);
//     // $images_query = $xpath->query('.//article //div[contains(@class, "image")] //picture //img');
//     $titles = $xpath->query('.//h1');
//     $h1_tags = $dom->getElementsByTagName('h1');
//     // foreach ($images_query as $key => $image) {
//     //     $data[] = ['image' => $image->getAttribute("data-src"),
//     //         'link' => $titles[$key] ? $titles[$key]->getAttribute("href") : '',
//     //         'text' => $titles[$key] ? $titles[$key]->textContent : '',
//     //     ];
//     // }

//     if ($h1_tags->length > 0) {
//         $h1_content = $h1_tags[0]->textContent;
//         var_dump($h1_content); // Hiển thị nội dung của thẻ h1
//     } else {
//         var_dump('Không tìm thấy thẻ h1');
//     }
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
    Route::post('/post/{post}/comment', [PostController::class, 'postCreateComment'])->name('post.comment.create');
    Route::put('/comment/{comment}', [PostController::class, 'updateComment'])->name('comment.update');
    Route::delete('/comment/{comment}', [PostController::class, 'deleteComment'])->name('comment.delete');
    Route::post('/comment/{comment}/reaction', [PostController::class, 'commentReaction'])->name('comment.reaction');

    //Group

    Route::post('/group', [GroupController::class, 'store'])->name('group.store');
    Route::post('/group/update-image/{group:slug}', [GroupController::class, 'updateImage'])->name('group.updateImage');
});

require __DIR__ . '/auth.php';
