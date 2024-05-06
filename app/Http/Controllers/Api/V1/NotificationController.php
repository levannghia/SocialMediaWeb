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
        $key = [
            "type" => "service_account",
            "project_id" => "eventhub-4c23c",
            "private_key_id" => "e48edc657e4fbef81774f28e79df8611d4085ec1",
            "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCZTrHc3/P5l+y2\noJ96g1PhtLyruAitwPaD1POhNTeqNAfLSUkiQdxpZbX01oIYEu/GkrfnVLtlrmT1\nx222qa6nOWXWJJ1FQFtOOp2SiaVViMXvWX6Ukls2ofxjKDG1vJphWyOLbkpHbt/m\nrcPC+MdP39woAskpkQoO8vzl2UuPi+eA9Iei4jRCoxLSTEWBmmeM+sQ8OwWy6aI4\nxw+bM0BYQZH9TgPf8f//K7NnXIuihD3KvzWtjxTU4WryUXqU4U7dM0p32wmdiahE\nP9pYh/9ik8YEX9Prjqdd918yBPqCMg1Ox84wZoZ8YFo4JQDOknmWqWtxn7oVQ9vX\nz3HJzb0tAgMBAAECggEABt8EEcY9L8lODrwRlHLLu6isxsraI2TWp6n6ZsCf3L0K\n7GLofY8+IGsymzqnXLTcklCDyuvSn7K6oQQYjWtQLKaMmJXOMWBlDJM2pMQOk6WB\nruyamRtw2His0Io9+stCkBfUTAIxdL9/bDNgPB4yW14ehpyOr5I/RSmW3tESaz3k\nQYmo7hxF4EKDOAQ660f3HjTpWt25PtKFhJyVtO/pVABxT6HUfXzBIq1S8ON8nX5M\nty/VfdKOk97t7KHIGRK4GgIiPLTHkNClzmxpdJp4FAaA8LIBEVsQjWAXY/CYcrI+\nDPRBwXWVeGyP65W8yDECkSeN3CE2RIdb7ZPTw5klcQKBgQDGddJPPoQHm9ldc6dB\ncdVXJ6q1F+yUpZp5HyTpI8309Ns9b5ZTY5AbxRMRvNJE7jUfV9NEu8vwTXz/9zWa\nzZjnGuQ6bb+t8HhZ2RUSXwVWSTScawYQKLLxvU/zfAp5hyE0RMbHXu8IusmPHFP6\nbK3UXUz9NYeD50MgOuDI0U+0ZwKBgQDFwYZoDmMsI+gzHZ4i5gh8gnpHowzkZWm9\n6xWShVDC2tIN5PI/A03odbo/gyrbXI1x3vHO+/vuoWg2YNBks2YNK/oYZCKnxIW/\nc3TuO97AeKrYF8H2mT7ITFlhXanwmXOOnyBl9PeGwuwsl4v3kosLEEWfxATx7od9\ntdJOJfclSwKBgBIYvEytjqyC8Zcr7JxzHNkNrOtGezQyxZs108/OjAFCMpuviS2h\nboqZtdtwNFxEvGNXRtSFq1sGNdfBWwn6pW6tbRJG36ukudS3jsxWDc6Iblu1BqCC\nlY6ljJzPOsVKJST3AIk9ht5s6eQ62Q2Ey3UJ7PNJ5kmI1P4jYEvvii0RAoGALowo\n2OFaEo+5Hh8Ak/JVWQlVQvtsE26TfzKQd1aN9e+PtdmH87ERa4AagMydD9kvKfhy\nLmg9mqO/Zd1P8AQSJ1OMoKUhSyAE41WH1nOdMOy2OfsNmr/jeT4PY82qLInSG2X1\nOEp64OuMr8pUFcgWlloUKVD4YQHmPn7Hq8Ff9RUCgYAG0bOlFCc8oDfMyrgKLJqv\nErgqv4FFeb4YCsrr8ldPle6QwBTaTgyQY86AXdad4jTVR0+uvXAU5gmukW6IYmL0\ngxR6rXp6LNkhB4mh7cQAMOlT62+0D7vhAMhHMEOWA4MJsdY6SVvxAWmoxzJWUZCm\ndDEH7aHsY1WUvXWLBHhM2A==\n-----END PRIVATE KEY-----\n",
            "client_email" => "firebase-adminsdk-4uex5@eventhub-4c23c.iam.gserviceaccount.com",
            "client_id" => "109980089897624149047",
            "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
            "token_uri" => "https://oauth2.googleapis.com/token",
            "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
            "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-4uex5%40eventhub-4c23c.iam.gserviceaccount.com",
            "universe_domain" => "googleapis.com"
        ];

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
            "fcmTokens" => ["required"],
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
                    'notification' => [
                        "title" => $request->input('title') ?? '',
                        "body" => $request->input('body') ?? '',
                        "subtitle" => $request->input('subtitle') ?? '',
                        "sound" => "default"
                    ],
                    'data' => [
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
