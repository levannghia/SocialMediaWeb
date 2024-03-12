<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Http\Enums\GroupUserRole;
use App\Http\Enums\GroupUserStatus;
use App\Http\Requests\InviteUserRequest;
use App\Models\GroupUser;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function profile(Group $group)
    {
        $group->load('currentUserGroup');
        return Inertia::render('Group/View', [
            'success' => session('success'),
            'group' => new GroupResource($group),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $group = Group::create($data);

        $groupUserData = [
            'status' => GroupUserStatus::APPROVED->value,
            'role' => GroupUserRole::ADMIN->value,
            'user_id' => auth()->id(),
            'group_id' => $group->id,
            'created_by' => auth()->id(),
        ];

        GroupUser::create($groupUserData);
        $group->status = $groupUserData['status'];
        $group->role = $groupUserData['role'];

        return new GroupResource($group);
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function updateImage(Request $request, Group $group)
    {
        if(!$group->isAdmin()){
            return response('You don\'t have permission to perform this action', 403);
        }

        $data = $request->validate([
            'thumbnail' => ['nullable', 'image'],
            'cover' => ['nullable', 'image'],
        ]);

        $thumbnail = $data['thumbnail'] ?? null;
        $cover = $data['cover'] ?? null;
        $success = '';

        if ($cover) {
            if ($group->cover_path) {
                Storage::disk('public')->delete($group->cover_path);
            }
            $path = $cover->store('group-' . $group->id, 'public');
            $group->update(['cover_path' => $path]);
            $success = 'Your cover image was updated';
        }

        if ($thumbnail) {
            if ($group->thumbnail_path) {
                Storage::disk('public')->delete($group->thumbnail_path);
            }
            $path = $thumbnail->store('group-' . $group->id, 'public');
            $group->update(['thumbnail_path' => $path]);
            $success = 'Your thumbnail image was updated';
        }

        // session('success', 'Cover image has been updated');

        return back()->with('success', $success);
    }

    public function inviteUsers(InviteUserRequest $request, Group $group)
    {
        $data = $request->validated();

        $user = $request->user;
        GroupUser::create([
            'status' => GroupUserStatus::PENDING->value,
            'role' => GroupUserRole::USER->value,
            'token' => Str::random(256),
            'token_expire_date' => Carbon::now()->addHours(24),
            'user_id' => $user->id,
            'group_id' => $group->id,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'User was invited to join to group');
    }

    public function update(UpdateGroupRequest $request, Group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        //
    }
}
