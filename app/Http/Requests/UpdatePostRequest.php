<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UpdatePostRequest extends StorePostRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // dd($this->input('id'));
        // $post = Post::where('id', $this->input('id'))->where('user_id', auth()->id())->first();
        // return !!$post;
        $post = $this->route('post');
        return $post->user_id == auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rule = parent::rules();
        unset($rule['group_id']);

        return array_merge($rule, [
            'deleted_file_ids' => 'array',
            'delete_file_ids.*' => 'numeric'
        ]);
    }
}
