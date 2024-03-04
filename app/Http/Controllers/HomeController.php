<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user_id = auth()->id();
        $query = Post::query()
            ->withCount(['reactions', 'comments'])
            ->with([
                'attachments', 'user', 'reactions' => function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                },
                'comments' => function ($query) use ($user_id) {
                    $query
                        // ->whereNull('parent_id')
                        ->withCount(['reactions', 'comments'])->with([
                            'user',
                            'reactions' => function ($query) use ($user_id) {
                                $query->where('user_id', $user_id);
                            },
                        ]);
                },
            ])->latest()->paginate(10);
            
        $posts = PostResource::collection($query);
        if ($request->wantsJson()) {
            return $posts;
        }

        return Inertia::render('Home', [
            'posts' => $posts,
        ]);
    }
}
