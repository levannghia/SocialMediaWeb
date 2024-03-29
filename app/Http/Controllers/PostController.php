<?php

namespace App\Http\Controllers;

use App\Http\Enums\PostReactionEnum;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostAttachment;
use App\Models\Reaction;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\PostResource;
use App\Notifications\PostCreated;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Notifications\PostDeleted;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;
use OpenAI\Laravel\Facades\OpenAI;

class PostController extends Controller
{


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        DB::beginTransaction();
        $allFilesPath = [];
        try {
            $post = Post::create($data);

            /** @var \Illuminate\Http\UploadedFile[] $files */

            $files = $data['attachments'] ?? [];
            foreach ($files as $file) {
                $path = $file->store('attachments/' . $post->id, 'public');
                $allFilesPath[] = $path;
                $attachment = PostAttachment::create([
                    'post_id' => $post->id,
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'created_by' => $user->id,
                ]);
            }

            DB::commit();

            $group = $post->group;

            if ($group) {
                $users = $group->approvedUsers()->where('users.id', $user->id)->get();
                if (count($users) > 0) {
                    Notification::send($users, new PostCreated($post, $user, $group));
                }
            }
        } catch (\Exception $e) {
            foreach ($allFilesPath as $path) {
                Storage::disk('public')->delete($path);
            }
            DB::rollBack();
            Log::debug("Lỗi function store post: " . $e->getMessage() . ' --line:' . $e->getLine());
        }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // $post = $post->with(['attachments', 'comments']);
        $post->loadCount('reactions');
        $post->load([
            'attachments',
            'comments' => function ($query) {
                $query->withCount('reactions'); // SELECT * FROM comments WHERE post_id IN (1, 2, 3...)
                // SELECT COUNT(*) from reactions
            },
        ]);
        return Inertia::render("Post/View", [
            'post' => new PostResource($post),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();
        $user = $request->user();

        // dd($data['attachments']);
        DB::beginTransaction();

        $allFilesPath = [];
        try {
            $post->update($data);

            /** @var \Illuminate\Http\UploadedFile[] $files */

            $deletedFilesId = $data['deleted_file_ids'] ?? [];

            $postAttachmentList = PostAttachment::where('post_id', $post->id)->whereIn('id', $deletedFilesId)->get();
            foreach ($postAttachmentList as $attachment) {
                $attachment->delete();
            }

            $files = $data['attachments'] ?? [];
            foreach ($files as $file) {
                $path = $file->store('attachments/' . $post->id, 'public');
                $allFilesPath[] = $path;
                $attachment = PostAttachment::create([
                    'post_id' => $post->id,
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'created_by' => $user->id,
                    // 'created_at' => Carbon::now()
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            foreach ($allFilesPath as $path) {
                Storage::disk('public')->delete($path);
            }
            DB::rollBack();
            Log::debug("Lỗi function update post: " . $e->getMessage() . ' --line:' . $e->getLine());
        }

        return back();
    }

    public function downloadAttachment(PostAttachment $attachment)
    {
        $path = storage_path($attachment->path);

        // dd($path);

        // if(Storage::disk('public')->exists($path)){
        return response()->download(Storage::disk('public')->path($attachment->path), $attachment->name);
        // }
    }

    public function postReaction(Request $request, Post $post)
    {
        $data = $request->validate([
            'reaction' => [Rule::enum(PostReactionEnum::class)]
        ]);

        $reaction  = Reaction::where('object_id', $post->id)
            ->where('object_type', Post::class)
            ->where('user_id', auth()->id())->first();

        if ($reaction) {
            $hasReaction = false;
            $reaction->delete();
        } else {
            $hasReaction = true;
            $post->reactions()->create([
                'user_id' => auth()->id(),
                'type' => $data['reaction'],
            ]);
        }

        $reaction  = Reaction::where('object_id', $post->id)
            ->where('object_type', Post::class)->count();

        return response([
            'num_of_reaction' => $reaction,
            'current_user_has_reaction' => $hasReaction,
        ]);
    }

    public function commentReaction(Request $request, Comment $comment)
    {
        $data = $request->validate([
            'reaction' => [Rule::enum(PostReactionEnum::class)]
        ]);

        $reaction  = Reaction::where('object_id', $comment->id)
            ->where('object_type', Comment::class)
            ->where('user_id', auth()->id())->first();

        if ($reaction) {
            $hasReaction = false;
            $reaction->delete();
        } else {
            $hasReaction = true;
            $comment->reactions()->create([
                'user_id' => auth()->id(),
                'type' => $data['reaction'],
            ]);
        }

        $reaction  = Reaction::where('object_id', $comment->id)
            ->where('object_type', Comment::class)->count();

        return response([
            'num_of_reaction' => $reaction,
            'current_user_has_reaction' => $hasReaction,
        ]);
    }

    public function postCreateComment(Request $request, Post $post)
    {
        $data = $request->validate([
            'comment' => ['required', "string"],
            'parent_id' => ['exists:comments,id', 'nullable']
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'parent_id' => $data['parent_id'] ?: null,
            'comment' => nl2br($data['comment']),
        ]);

        return new CommentResource($comment);
    }

    public function updateComment(UpdateCommentRequest $request, Comment $comment)
    {
        $data = $request->validated();
        $comment->update([
            'comment' => nl2br($data['comment']),
        ]);

        return new CommentResource($comment);
    }

    public function deleteComment(Comment $comment)
    {
        $post = $comment->post;
        $user_id = auth()->id();

        if ($comment->isOwner($user_id) || $post->isOwed($user_id)) {
            $comment->delete();
            return response('', 204);
        }

        return response('Bạn không có quyền xóa bình luận', 403);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $id = auth()->id();

        if ($post->isOwner($id) || $post->group && $post->group->isAdmin()) {
            $post->delete();
            if (!$post->isOwner($id)) {
                $post->user->notify(new PostDeleted($post->group));
            }
            return back();
        }

        return response("Bạn không có quyền xóa bài viết này!", 403);
    }

    public function aiPostContent(Request $request)
    {
        $prompt = $request->get("prompt");
        // $result = OpenAI::chat()->create([
        //     'model' => 'gpt-3.5-turbo',
        //     'messages' => [
        //         [
        //             'role' => 'user',
        //             'content' => "Please generate social media post content based on the following prompt. Generated formatted content with multiple paragraphs. Put hashtags after 2 lines from the main content" . PHP_EOL . PHP_EOL . "Prompt: " . PHP_EOL
        //             . $prompt
        //         ],
        //     ],
        // ]);

        return response()->json([
            // 'content' => $result->choices[0]->message->content,
            'content' => "\"🎉 Exciting news! We're thrilled to announce that we just released a brand new feature on our app/website! 💥 Get ready to experience the next level of convenience and efficiency with this game-changing addition. 🚀 Try it out now and let us know what you think! 😍 #NewFeatureAlert #UpgradeYourExperience\""
        ]);
    }

    public function fetchUrlPreview(Request $request)
    {
        $data = $request->validate([
            'url' => 'url',
        ]);

        $url = $data['url'];

        $html = file_get_contents($url);

        $dom = new \DOMDocument();

        // Suppress warnings for malformed HTML
        libxml_use_internal_errors(true);

        // Load HTML content into the DOMDocument
        $dom->loadHTML($html);

        // Suppress warnings for malformed HTML
        libxml_use_internal_errors(false);

        $ogTags = [];
        $metaTags = $dom->getElementsByTagName('meta');
        foreach ($metaTags as $tag) {
            $property = $tag->getAttribute('property');
            if (str_starts_with($property, 'og:')) {
                $ogTags[$property] = $tag->getAttribute('content');
            }
        }

        return $ogTags;
    }


    public function pinUppin(Request $request, Post $post){
    
        $forGroup = $request->get('forGroup');
        $group = $post->group;

        if ($forGroup && !$group) {
            return response("Invalid Request", 400);
        }

        if ($forGroup && !$group->isAdmin()) {
            return response("You don't have permission to perform this action", 403);
        }

        $pinned = false;

        if($forGroup && $group && $group->isAdmin()){
           if ($group->pinned_post_id === $post->id) {
                $group->pinned_post_id = null;
            } else {
                $pinned = true;
                $group->pinned_post_id = $post->id;
            }
            $group->save();
        }

        if(!$forGroup){
            $user = $request->user();
            if($user->pinned_post_id === $post->id){
                $user->pinned_post_id = null;
            }else{
                $pinned = true;
                $user->pinned_post_id = $post->id;
            }
            $user->save();
        }

        return back()->with('success', 'Post was successfully ' . ( $pinned ? 'pinned' : 'unpinned' ));
    }
}
