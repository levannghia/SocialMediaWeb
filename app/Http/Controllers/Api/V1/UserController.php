<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
}
