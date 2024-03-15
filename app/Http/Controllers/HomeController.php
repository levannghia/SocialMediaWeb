<?php

namespace App\Http\Controllers;

use App\Http\Enums\GroupUserStatus;
use App\Http\Resources\GroupResource;
use App\Http\Resources\PostResource;
use App\Models\Group;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user_id = auth()->id();
        $query = Post::postsForTimeLine($user_id)
            ->paginate(10);

        $posts = PostResource::collection($query);

        $groups = Group::query()
                ->select(['groups.*'])
                ->with('currentUserGroup')
                ->join('group_users AS gu', 'gu.group_id', 'groups.id')
                ->where('gu.user_id', auth()->id())
                // ->where('gu.status', GroupUserStatus::APPROVED->value)
                ->orderBy('gu.role')
                ->orderBy('name', 'desc')
                ->get();
                
        if ($request->wantsJson()) {
            return $posts;
        }

        return Inertia::render('Home', [
            'posts' => $posts,
            'groups' => GroupResource::collection($groups),
        ]);
    }
}
