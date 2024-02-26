<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'body',
        'user_id',
        'group_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function attachments()
    {
        return $this->hasMany(PostAttachment::class)->latest();
    }

    public function reactions(){
        return $this->hasMany(PostReaction::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function latest5Comments(){
        return $this->hasMany(Comment::class)->latest()->limit(5);
    }
}
