<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class NotificationController extends Controller
{

    private function getAccessToken()
    {
        // $serviceAccountFile = storage_path('app/eventhub-4c23c.json');
        // $key = json_decode(file_get_contents(storage_path('app/eventhub-4c23c.json')), true);
        // 'registration_ids' => $request->input('fcmTokens'),
        // composer remove google/auth
        $credentialsFilePath = storage_path('app/eventhub-4c23c.json');
        $client = new \Google_Client();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();
        return $token['access_token'];
    }

    public function handleSendNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title" => ["string", "nullable"],
            "body" => ["string", "nullable"],
            "fcmToken" => ["required", "string"],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation send notification fail!!!'
            ], 422);
        }

        $token = 'AAAA29q30NE:APA91bHxrWGXNWsrtVVu6eVit85U7a62frJaiKO7og_TYdBWWmyUTHKq-X1zIC7A7aKbyJ1a_opPU2Hsj3K-wau5XZdepKW7jcdBKBRexJeuUPLOj1m6D7obcSH7YThL5l8vry0zsZ5s';
        $accessToken = $this->getAccessToken();

        try {
            $response = Http::withToken($accessToken)->acceptJson()->post('https://fcm.googleapis.com/v1/projects/eventhub-4c23c/messages:send', [
                "message" => [
                    "token" => $request->input('fcmToken'),
                    "notification" => [
                        "title" => $request->input('title') ?? '',
                        "body" => $request->input('body') ?? '',
                    ],
                    "data" => [
                        'id' => "12"
                    ]
                ]
            ]);

            if ($response->failed()) {
                $error = $response->json('error');
                Log::error("Lỗi FCM: " . $error['message']);
                return response()->json([
                    'error' => $error['message'],
                    'message' => 'Gửi thông báo thất bại!',
                ], 500);
            }

            return response()->json([
                'message' => 'Send notification success!',
                'data' => $response,
            ], 200);
        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage() . ' ---- line: ' . $e->getLine());
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Send notification error!',
            ], 500);
        }
    }

    public function sendNotification($title, $body, $fcmToken, $data = [])
    {
        $accessToken = $this->getAccessToken();

        try {
            $response = Http::withToken($accessToken)
                ->acceptJson()
                ->post('https://fcm.googleapis.com/v1/projects/eventhub-4c23c/messages:send', [
                    "message" => [
                        "token" => $fcmToken,
                        "notification" => [
                            "title" => $title ?? '',
                            "body" => $body ?? '',
                        ],
                        "data" => $data
                    ]
                ]);

            if ($response->failed()) {
                $error = $response->json('error');
                Log::error("Lỗi FCM: " . $error['message']);
                return response()->json([
                    'error' => $error['message'],
                    'message' => 'Gửi thông báo thất bại!',
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage() . ' ---- line: ' . $e->getLine());
        }
    }

    public function sendInviteNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title" => ["string", "nullable"],
            "body" => ["string", "nullable"],
            "fcmToken" => ["required", "string"],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation send notification fail!!!'
            ], 422);
        }
    }
}
