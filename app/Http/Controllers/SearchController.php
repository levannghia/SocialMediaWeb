<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Post;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;

class SearchController extends Controller
{
    public function search(Request $request, string $search = null)
    {
        // $search = $request->get("search");

        if (!$search) {
            return redirect()->route('dashboard');
        }

        $users = User::query()
            ->where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('username', 'LIKE', '%' . $search . '%')
            ->latest()
            ->get();

        $groups = Group::query()
            ->where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('about', 'LIKE', '%' . $search . '%')
            ->latest()
            ->get();

        $posts = Post::postsForTimeline(Auth::id())
            ->where('body', 'like', "%$search%")
            ->paginate(20);

        $posts = PostResource::collection($posts);
        if ($request->wantsJson()) {
            return $posts;
        }

        return Inertia::render('Search', [
            'posts' => $posts,
            'search' => $search,
            'users' => UserResource::collection($users),
            'groups' => GroupResource::collection($groups)
        ]);
    }
}
