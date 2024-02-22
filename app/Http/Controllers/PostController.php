<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\PostAttachment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
                    // 'created_at' => Carbon::now()
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
