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

        // Đường dẫn đến tệp JSON chứa Service Account Credentials
        // $serviceAccountFile = storage_path('app/eventhub-4c23c.json');

        // $key = json_decode(file_get_contents(storage_path('app/eventhub-4c23c.json')), true);
        // composer remove google/auth

        // $serviceAccountCredentials = json_decode(file_get_contents(storage_path('app/eventhub-4c23c.json')), true);

        $credentialsFilePath = storage_path('app/eventhub-4c23c.json'); //replace this with your actual path and file name
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
            "subtitle" => ["string", "nullable"],
            "fcmTokens" => ["required", "string"],
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
                    // 'registration_ids' => $request->input('fcmTokens'),
                    "token" => $request->input('fcmTokens'),
                    "notification" => [
                        // "topic" => "news",
                        "title" => $request->input('title') ?? '',
                        "body" => $request->input('body') ?? '',
                        "subtitle" => $request->input('subtitle') ?? '',
                        // "sound" => "default"
                    ],
                    "data" => [
                        'id' => 12
                    ]
                ]
            ]);

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

    public function handleSendInviteNotification(Request $request)
    {
    }
}
