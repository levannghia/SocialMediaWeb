<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialProvider extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "social_providers";

    protected $fillable = [
        'user_id',
        'token',
        'provider',
    ];
}
