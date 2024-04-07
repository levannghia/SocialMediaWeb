<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request)
    {
        try {
            $data = $request->validated();
            $user = User::create([
                'name' => $data['fullname'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'])
            ]);

            $token = $user->createToken('user_token')->plainTextToken;

            return response()->json([
                'data' => new UserResource($user),
                'token' => $token,
                'message' => 'Đăng ký thành công!'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Trả về lỗi validation
            return response()->json([
                'error' => $e->errors(), // Lấy các lỗi từ exception
                'message' => 'Validation error!'
            ],200); // Trả về mã lỗi 422 (Unprocessable Entity)
        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage() . ' ---- line: ' . $e->getLine());
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Register error!'
            ], 500);
        }
    }
}
