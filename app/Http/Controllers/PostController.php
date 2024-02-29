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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
        } catch (\Exception $e) {
            foreach ($allFilesPath as $path) {
                Storage::disk('public')->delete($path);
            }
            DB::rollBack();
            Log::debug("Lỗi function store post: " . $e->getMessage() . ' --line:' . $e->getLine());
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
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

    public function commentReaction(Request $request, Comment $comment){
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

        return response(new CommentResource($comment), 201);
    }

    public function updateComment(UpdateCommentRequest $request, Comment $comment)
    {
        $data = $request->validated();
        $comment->update([
            'comment' => nl2br($data['comment']),
        ]);

        return response(new CommentResource($comment), 201);
    }

    public function deleteComment(Comment $comment)
    {
        $post = $comment->post;

        if ($comment->user_id != $post->user_id) {
            return response('Bạn không có quyền xóa bình luận', 403);
        }

        $comment->delete();

        return response('', 204);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $id = auth()->id();

        if ($post->user_id != $id) {
            return response("Bạn không có quyền xóa bài viết này!", 403);
        }

        $post->delete();
        return back();
    }
}
