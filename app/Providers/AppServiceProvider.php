<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Observers\PostObserver;
use App\Observers\CommentObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
        Carbon::setLocale('vi');
        Post::observe(PostObserver::class);
        Comment::observe(CommentObserver::class);
    }
}
