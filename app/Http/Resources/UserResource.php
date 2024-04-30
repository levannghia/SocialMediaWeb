<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    // public static $wrap = false;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "username" => $this->username,
            "cover_url" => $this->cover_path != null ? Storage::url($this->cover_path) : '/images/defaut_cover_photo.jpg',
            "avatar_url" => $this->avatar_path != null ? Storage::url($this->avatar_path) : '/images/user_default.png',
            "pinned_post_id" => $this->pinned_post_id,
            "email_verified_at" => $this->email_verified_at,
            "fcm_tokens" => $this->fcm_tokens ?? [],
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
