<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'body' => $this->body,
            'user' => new UserResource($this->user),
            'group' => $this->group,
            'attachments' => PostAttachmentResource::collection($this->whenLoaded('attachments')),
            'num_of_reaction' => $this->reactions_count,
            'current_user_has_reaction' => $this->reactions->count() > 0,
            'num_of_comment' => $this->comments_count,
            'comments' => self::convertCommentsIntoTree($this->comments),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }

    private static function convertCommentsIntoTree($comments, $parent_id = null)
    {
        $commentTree = [];

        foreach($comments as $comment){
            if($comment->parent_id === $parent_id){
                $children = self::convertCommentsIntoTree($comments, $comment->id);
                $comment->childOfComments = $children;

                $commentTree[] = new CommentResource($comment);
            }
        }

        return $commentTree;
    } 
}
