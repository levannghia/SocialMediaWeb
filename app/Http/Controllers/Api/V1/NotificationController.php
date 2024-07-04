<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventUser;
use App\Models\User;
use App\Notifications\InvitationEvent;
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

    public function handleSendNotificationEvent($fcmToken, $title, $body, $data = [])
    {

        $accessToken = $this->getAccessToken();

        try {
            $response = Http::withToken($accessToken)->acceptJson()->post('https://fcm.googleapis.com/v1/projects/eventhub-4c23c/messages:send', [
                "message" => [
                    "token" => $fcmToken,
                    "notification" => [
                        "title" => $title ?? '',
                        "body" => $body ?? '',
                    ],
                    "data" => $data,
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

    public function handleSendInviteNotification(Request $request)
    {
        $data = $request->validate([
            'event_id' => ['required', 'numeric', 'exists:events,id'],
            'user_id' => ['required', 'numeric', 'exists:users,id'],
            'from_id' => ['required', 'numeric', 'exists:users,id'],
        ]);

        $event = Event::find($data['event_id']);
        $user = User::find($data['user_id']);
        $fromUser = User::find($data['from_id']);

        if($event) {
            EventUser::create([
                'user_id' => $data['user_id'],
                'event_id' => $data['event_id'],
                'from_id' => $data['from_id'],
                'status' => 'pending',
                'created_by' => $event->user_id,
            ]);

            $title = $fromUser->name . ' mời bạn tham gia event';
            $body = 'Bạn nhận được lời mời tham gia sự kiên ' . $event->title;

            if($user->fcm_tokens){
                foreach ($user->fcm_tokens as $token) { 
                    $this->handleSendNotificationEvent($token, $title, $body, [
                        'id' => '12',
                    ]);
                }
            } else {
                $user->notify(new InvitationEvent($event, $user));
            }

            return response()->json([
                'status' => true,
                'message' => 'Gửi lời mời thành công!',
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Gửi lời mời thất bại!',
        ], 400);
    }

    public function joinEvent(Request $request)
    {
        $data = $request->validate([
            'event_id' => ['required', 'numeric', 'exists:events,id'],
            'user_id' => ['required', 'numeric', 'exists:users,id'],
            'from_id' => ['required', 'numeric', 'exists:users,id'],
            'status' => ['required'],
        ]);

        $eventUser = EventUser::where('event_id', $data['event_id'])
        ->where('user_id', $data['user_id'])
        ->where('from_id', $data['from_id'])->first();

        if($eventUser) {
            $eventUser->status = $data['status'];
            $eventUser->save();

            return response()->json([
                'status' => true,
                'message' => 'Join event success!',
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Join Event that bai!',
        ], 400);
    }
}
