<?php

namespace App\Models;

use App\Http\Enums\GroupUserRole;
use App\Http\Enums\GroupUserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\SoftDeletes;
class Group extends Model
{
    use HasFactory, HasSlug, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'cover_path',
        'thumbnail_path',
        'auto_approval',
        'user_id',
        'deleted_by',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function currentUserGroup(){
        return $this->hasOne(GroupUser::class)->where('user_id', auth()->id());
    }

    public function isOwner($user_id){
        return $this->user_id == $user_id;
    }

    public function isAdmin(){
        return GroupUser::query()
        ->where('user_id', auth()->id())
        ->where('group_id', $this->id)
        ->where('role', GroupUserRole::ADMIN->value)
        ->exists();
    }

    public function adminUser() {
        return $this->belongsToMany(User::class, 'group_users')->wherePivot('role', GroupUserRole::ADMIN->value);
    }

    public function pendingUsers() {
        return $this->belongsToMany(User::class, 'group_users')->wherePivot('status', GroupUserStatus::PENDING->value);
    }

    public function approvedUsers() {
        return $this->belongsToMany(User::class, 'group_users')->wherePivot('status', GroupUserStatus::APPROVED->value);
    }

    public function group(){
        return $this->belongsTo(Group::class);
    }
}
