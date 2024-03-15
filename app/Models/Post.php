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

    // public function reactions(){
    //     return $this->hasMany(Reaction::class);
    // }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'object');
    }
    public function latest5Comments()
    {
        return $this->hasMany(Comment::class)->with('user');
    }

    public static function postsForTimeLine($user_id)
    {
        return Post::query()
            ->withCount(['reactions', 'comments'])
            ->with([
                'group',
                'attachments',
                'user',
                'reactions' => function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                },
                'comments' => function ($query) use ($user_id) {
                    $query
                        // ->whereNull('parent_id')
                        ->withCount(['reactions', 'comments'])->with([
                                'user',
                                'reactions' => function ($query) use ($user_id) {
                                    $query->where('user_id', $user_id);
                                },
                            ]);
                },
            ])
            ->latest();
    }
}
