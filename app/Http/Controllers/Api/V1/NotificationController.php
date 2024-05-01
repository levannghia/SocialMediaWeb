<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class NotificationController extends Controller
{
    public function handleSendNotification(Request $request){
        $validator = Validator::make($request->all(), [
            "title" => ["string", "nullable"],
            "body" => ["string", "nullable"],
            "subtitle" => ["string", "nullable"],
            "fcmTokens" => ["array", "required"],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation send notification fail!!!'
            ], 422);
        }
        $token = 'AAAA29q30NE:APA91bHxrWGXNWsrtVVu6eVit85U7a62frJaiKO7og_TYdBWWmyUTHKq-X1zIC7A7aKbyJ1a_opPU2Hsj3K-wau5XZdepKW7jcdBKBRexJeuUPLOj1m6D7obcSH7YThL5l8vry0zsZ5s';
        try {
            $response = Http::withToken($token)->acceptJson()->post('https://fcm.googleapis.com/fcm/send', [
                'registration_ids' => $request->input('fcmTokens'),
                'notification' => [
                    "title" => $request->input('title') ?? '',
                    "body" => $request->input('body') ?? '',
                    "subtitle" => $request->input('subtitle') ?? '',
                    "sound" => "default"
                ]
            ]);

            return response()->json([
                'message' => 'Send notification success!',
            ], 200);

        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage() . ' ---- line: ' . $e->getLine());
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Send notification error!',
            ], 500);
        }
    }
}
