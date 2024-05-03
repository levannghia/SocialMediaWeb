<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Enums\EventUserStatus;
use App\Http\Resources\UserResource;
use App\Models\Follower;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function getAllUser()
    {
        try {
            $user = auth()->user();
            if ($user) {
                $users = User::where('id', '!=', $user->id)->get();

                return response()->json([
                    'users' => UserResource::collection($users),
                ], 200);
            }

            return response()->json([
                'message' => "Không có quyền truy cập",
            ], 401);
        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage() . ' ---- line: ' . $e->getLine());
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Logout error!',
            ], 500);
        }
    }

    public function getProfile($id)
    {
        try {
            $user = User::where('id', $id)->first();
            $isCurrentUserFollower = false;
            if (!Auth::guest()) {
                $isCurrentUserFollower = Follower::where('user_id', $user->id)->where('follower_id', auth()->id())->exists();
            }
            if ($user) {
                return response()->json([
                    'user' => new UserResource($user),
                    'follow' => $isCurrentUserFollower,
                    'followings' => UserResource::collection($user->followings),
                    'followers' => UserResource::collection($user->followers),
                ], 200);
            }
            return response()->json([
                'message' => 'Profile khong ton tai!',
            ], 404);
        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage() . ' ---- line: ' . $e->getLine());
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Get profile error!',
            ], 500);
        }
    }

    public function followUser(Request $request) {
        $validator = Validator::make($request->all(), [
            'follow' => ['boolean'],
            'id' => ['exists:users,id', 'numeric']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation follow user fail!!!'
            ], 422);
        }

        $user = User::where('id', $request->input('id'))->first();

        if ($request->input('follow')) {
            $message = 'You followed user "'.$user->name.'"';
            Follower::create([
                'user_id' => $user->id,
                'follower_id' => auth()->id(),
            ]);
        } else {
            $message = 'You unfollowed user "'.$user->name.'"';
            Follower::query()
                ->where('user_id', $user->id)
                ->where('follower_id', auth()->id())
                ->delete();
        }

        return response()->json([
            'message' => $message,
        ], 200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['string', 'required'],
            'fileType' => ['nullable', Rule::enum(EventUserStatus::class)],
            'image' => [
                'file',
                File::types(['jpg', 'png', 'gif', 'jpeg', 'PNG'])->max(10 * 1024 * 1024),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Update event fail!!!'
            ], 422);
        }

        try {
            //code...
        } catch (\Exception $e) {
            //throw $th;
        }
    }

    public function updateFcmToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "fcmToken" => ["array", "nullable"],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validate update FCM Token Fails!!!'
            ], 422);
        }

        $user = auth()->user();

        if ($user) {
            $user->fcm_tokens = $request->input('fcmToken');
            $user->save();

            return response()->json([
                'data' => new UserResource($user),
            ], 201);
        }
    }
}
