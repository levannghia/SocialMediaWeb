<?php

namespace App\Models;

use App\Http\Enums\GroupUserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Event extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'description',
        'location_title',
        'location_address',
        'position',
        'file_type',
        'file_url',
        'start_at',
        'end_at',
        'date',
        'price',
        'user_id',
        'path',
    ];

    protected $casts = [
        'position' => 'json',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function pendingUsers()
    {
        return $this->belongsToMany(User::class, 'event_users')->wherePivot('status', GroupUserStatus::PENDING->value);
    }

    public function approvedUsers()
    {
        return $this->belongsToMany(User::class, 'event_users')->wherePivot('status', GroupUserStatus::APPROVED->value);
    }

    public function users(){
        return $this->belongsToMany(User::class, 'event_users', 'event_id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
