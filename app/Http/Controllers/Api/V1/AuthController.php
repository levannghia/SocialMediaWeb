<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\VerificationEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AuthController extends Controller
{
    private int $minute = 2;

    public function register(StoreUserRequest $request)
    {
        $data = $request->validated();

        try {
            $otp = mt_rand(0000, 9999);

            $user = User::create([
                'name' => $data['fullname'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'otp' => $otp,
                'otp_expire_date' => Carbon::now()->addMinutes($this->minute)
            ]);

            $user->notify(new VerificationEmail($otp, $this->minute));

            return response()->json([
                'data' => new UserResource($user),
                'token' => '',
                'message' => 'Đăng ký thành công!'
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Trả về lỗi validation
            return response()->json([
                'errors' => $e->errors(), // Lấy các lỗi từ exception
                'message' => 'Validation error!'
            ], 422); // Trả về mã lỗi 422 (Unprocessable Entity)
        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage() . ' ---- line: ' . $e->getLine());
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Register error!'
            ], 500);
        }
    }

    public function verificationOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => ["email", "required"],
            "otp" => ['numeric', 'required'],
        ]);

        try {
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                    'message' => 'verification OTP Fails!!!'
                ], 422);
            }

            $user = User::where('email', $request->input('email'))->where('otp', $request->input('otp'))->where('email_verified_at', null)->first();

            if ($user) {
                if ($user->otp_expire_date > Carbon::now()) {
                    $user->email_verified_at = Carbon::now();
                    $user->otp = null;
                    $user->otp_expire_date = null;

                    $user->save();
                    $token = $user->createToken('user_token')->plainTextToken;

                    return response()->json([
                        'data' => new UserResource($user),
                        'token' => $token,
                        'message' => 'Xác thực thành công!'
                    ], 200);
                }

                return response()->json([
                    'message' => 'OTP đã hết hạn !!!'
                ], 403);
            }

            return response()->json([
                'message' => 'OTP không chính xác !!!'
            ], 403);

        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage() . ' ---- line: ' . $e->getLine());
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'verification OTP error!'
            ], 500);
        }
    }

    public function resendVerificationOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => ["email", "required"],
        ]);

        $otp = mt_rand(0000, 9999);
        try {
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                    'message' => 'verification OTP Fails!!!'
                ], 422);
            }

            $user = User::where('email', $request->input('email'))->where('otp', '!=', null)->where('email_verified_at', null)->first();

            if ($user) {
                $user->otp = $otp;
                $user->otp_expire_date = Carbon::now()->addMinutes($this->minute);

                $user->save();
                
                $user->notify(new VerificationEmail($otp, $this->minute));

                return response()->json([
                    'message' => 'OTP gửi thành công!'
                ], 200);
            }

            return response()->json([
                'message' => 'Gửi OTP thất bại!!!'
            ], 400);

        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage() . ' ---- line: ' . $e->getLine());
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Resend Verification OTP error!'
            ], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        try {
            $user = User::where('email', $data['email'])->first();
            if ($user) {
                if (Hash::check($data['password'], $user->password)) {
                    $token = $user->createToken('user_token')->plainTextToken;
                    return response()->json([
                        'data' => new UserResource($user),
                        'token' => $token,
                        'message' => 'Đăng nhập thành công!'
                    ], 200);
                }
                return response()->json([
                    'errors' => [
                        'password' => [
                            "Mật Khẩu không chính xác!!!"
                        ]
                    ],
                    'message' => 'Mật khẩu không chính xác!!!',
                ], 400);
            } else {
                return response()->json([
                    'errors' => [
                        'email' => [
                            "Tài khoản không tồn tại!!!"
                        ]
                    ],
                    'message' => 'Tài khoản không tồn tại!!!',
                ], 400);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Trả về lỗi validation
            return response()->json([
                'errors' => $e->errors(),
                'message' => 'Validation error!'
            ], 422);
        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage() . ' ---- line: ' . $e->getLine());
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Register error!'
            ], 500);
        }
    }

    public function forgotPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            "email" => ["email", "required"],
        ]);

        try {
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                    'message' => 'Forgot Password Fails!!!'
                ], 422);
            }

            $user = User::where('email', $request->input('email'))->first();
            //TODO...
        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage() . ' ---- line: ' . $e->getLine());
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Forgot Password error!'
            ], 500);
        }
    }

    public function loginWithGoogle(Request $request) {
        
    }

    public function logout(Request $request){
        $validator = Validator::make($request->all(), [
            "email" => ["email", "required"],
        ]);

        try {   
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                    'message' => 'Logout Fails!!!'
                ], 422);
            }

            $user = User::where('email', $request->input('email'))->first();
            $user->tokens()->delete();
            
            return response()->json('User logged out!', 200);
        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage() . ' ---- line: ' . $e->getLine());
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Logout error!'
            ], 500);
        }
    }
}
