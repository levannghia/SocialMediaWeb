<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
            if ($user) {
                return response()->json([
                    'user' => new UserResource($user),
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
