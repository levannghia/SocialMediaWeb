<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Enums\EventUserStatus;
use App\Http\Enums\GroupUserStatus;
use App\Http\Requests\StoreEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use phpDocumentor\Reflection\Types\This;

class EventController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => ['string', 'required'],
            'categoryId' => ['required', 'numeric'],
            'description' => ['nullable'],
            'locationTitle' => ['nullable', 'string'],
            'locationAddress' => ['required', 'string'],
            'position' => ['required', 'array'],
            'fileType' => ['required', Rule::enum(EventUserStatus::class)],
            'fileUrl' => ['string', 'nullable'],
            'startAt' => ['required'],
            'endAt' => ['required'],
            'date' => ['required'],
            'price' => ['nullable', 'numeric'],
            'image' => [
                'file',
                File::types(['jpg', 'png', 'gif', 'jpeg', 'PNG'])->max(10 * 1024 * 1024),
            ],
            'users' => ['array', 'nullable'],
            'users.*' => ['exists:users,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation store event fail!!!'
            ], 422);
        }

        $dt = Carbon::now();
        $year = $dt->year;
        $month = $dt->month;

        // $data = $request->validated();

        $path = '';
        $user = auth()->user();
        DB::beginTransaction();

        try {
            if (isset($data['image']) && $data['fileType'] == EventUserStatus::FILE->value) {
                $file = $data['image'];
                $path = $file->store('events/' . $year . '/' . $month, 'public');
            } else {
                $path = $data['fileUrl'];
            }

            $event = Event::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'location_title' => $data['locationTitle'],
                'location_address' => $data['locationAddress'],
                'position' => $data['position'],
                'user_id' => $user->id,
                'start_at' => $data['startAt'],
                'end_at' => $data['endAt'],
                'date' => $data['date'],
                'price' => $data['price'],
                'file_type' => $data['fileType'],
                'category_id' => $data['categoryId'],
                'path' => $path,
            ]);

            $users = $data['users'] ?? [];


            $event->users()->attach(
                $users,
                [
                    'status' => GroupUserStatus::PENDING->value,
                    'created_by' => $user->id,
                    'created_at' => $dt,
                    'updated_at' => $dt,
                ]
            );

            DB::commit();

            return response()->json([
                'data' => new EventResource($event),
            ], 201);
        } catch (\Exception $e) {
            if ($data['fileType'] == 'file') {
                Storage::disk('public')->delete($path);
            }
            Log::error("message: " . $e->getMessage() . ' ---- line: ' . $e->getLine());
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Store Event error!'
            ], 500);
        }
    }


    private function calcDistanceLocation($currentLat, $currentLong, $addressLat, $addressLong)
    {
        $r = 6371; // Bán kính trái đất ở đơn vị kilomet (km)

        // Chuyển đổi độ sang radian
        $dLat = $this->toRad($addressLat - $currentLat);
        $dLon = $this->toRad($addressLong - $currentLong);

        // Tính biến a theo công thức haversine
        $a = sin($dLat / 2) * sin($dLat / 2) +
            sin($dLon / 2) * sin($dLon / 2) *
            cos($this->toRad($currentLat)) * cos($this->toRad($addressLat));

        // Tính khoảng cách
        $distance = $r * (2 * atan2(sqrt($a), sqrt(1 - $a)));

        return $distance;
    }

    private function toRad($val)
    {
        return $val * pi() / 180;
    }


    public function getEvent(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'lat' => ['numeric', 'nullable'],
            'long' => ['numeric', 'nullable'],
            'distance' => ['nullable', 'numeric'],
            'limit' => ['nullable', 'numeric'],
            'date' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation get event near by current location fail!!!'
            ], 422);
        }

        $distance = $data['distance'] ?? 20;
        $limit = isset($data['limit']) ? $data['limit'] : 0;
        $date = isset($data['date']) ? $data['date'] : null;
        try {
            $newEvents = [];
            $events = Event::with(['approvedUsers'])
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($date, function($query, $date) {
                $query->whereDate('date', '>=', $date);
            })
            ->orderBy('date', 'ASC')->get();

            if (isset($data['lat']) && isset($data['long'])) {
                foreach ($events as $event) {
                    $eventDistance = $this->calcDistanceLocation($data['lat'], $data['long'], $event->position['lat'], $event->position['long']);

                    if ($eventDistance < $distance) {
                        array_push($newEvents, $event);
                    }
                }

                return response()->json([
                    'data' => EventResource::collection($newEvents),
                ], 200);
            }
            return response()->json([
                'data' => EventResource::collection($events)
            ], 200);
        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage() . ' ---- line: ' . $e->getLine());
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Get Event error!'
            ], 500);
        }
    }
}
