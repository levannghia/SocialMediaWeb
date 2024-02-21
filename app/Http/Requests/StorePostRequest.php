<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body' => ['nullable', 'string'],
            'user_id' => ['numeric'],
            'attachments' => ['array', 'max:10'],
            'attachments.*' => [
                'file',
                File::types([
                    'jpg', 'png', 'gif', 'jpeg', 'PNG', 'webp',
                    'mp4', 'mp3', 'wav', 'zip',
                    'doc', 'docx', 'pdf', 'csv', 'xlsx', 'xls'
                    ])
                    ->max(500 * 1024 * 1024),
            ]
        ];
    }

    // protected function passedValidation()
    // {
    //     $this->replace(['user_id' => auth()->id()]);
    // }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => auth()->id(),
            'body' => $this->input('body') ?: '',
        ]);
    }
}
