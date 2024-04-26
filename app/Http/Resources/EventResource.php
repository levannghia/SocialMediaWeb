<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'categoryId' => $this->category_id,
            'description' => $this->description,
            'locationTitle' => $this->location_title,
            'locationAddress' => $this->location_address,
            'user' => new UserResource($this->user),
            'category' => new CategoryResource($this->category),
            'position' => $this->position,
            'fileType' => $this->file_type,
            'fileUrl' => $this->path ? Storage::url($this->path) : null,
            'startAt' => $this->start_at,
            'endAt' => $this->end_at,
            'date' => $this->date,
            'price' => $this->price,
            'users' => UserResource::collection($this->whenLoaded('events')),
        ];
    }
}
