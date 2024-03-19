<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public int $numOfComments = 0;
    public Array $childComments = [];
    
    protected $fillable = [
        'comment',
        'user_id',
        'post_id',
        'parent_id'
    ];

    public function isOwner($userId)
    {
        return $this->user_id == $userId;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'object');
    }

    public function comments()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public static function getAllChildrenComments($comment) {
        $comments = Comment::query()->where('post_id', $comment->id)->get();
        $result = [$comment];
        self::getAllChildrenComments($comments, $comment->id, $result);
        return $result;
    }

    private static function _getAllChildComments($comments, $parentId, &$result = []) {
        foreach ($comments as $comment) {
            if($comment->parent_id === $parentId){
                $result[] = $comment;
                self::_getAllChildComments($comments, $comment->id, $result);
            }
        }
    }
}
