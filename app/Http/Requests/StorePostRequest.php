<?php

namespace App\Http\Requests;

use App\Http\Enums\GroupUserStatus;
use App\Models\GroupUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\File;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public static array $extenstions = [
        'jpg', 'png', 'gif', 'jpeg', 'PNG', 'webp',
        'mp4', 'mp3', 'wav', 'zip',
        'doc', 'docx', 'pdf', 'csv', 'xlsx', 'xls'
    ];

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
            'preview' => ['nullable', 'array'],
            'preview_url' => ['nullable', 'string'],
            'group_id' => ['nullable', 'exists:groups,id', function ($attribute, $value, $fail) {
                $groupUser = GroupUser::where('user_id', auth()->id())
                    ->where('group_id', $value)
                    ->where('status', GroupUserStatus::APPROVED->value)->exists();
                if (!$groupUser) {
                    $fail('You don\'t have permission to create post in this group');
                }
            }],
            'attachments' => [
                'array',
                'max:50',
                function ($attribute, $value, $fail) {
                    // Custom rule to check the total size of all files
                    $totalSize = collect($value)->sum(function (UploadedFile $file) {
                        return $file->getSize();
                    });
                    // dd($totalSize);
                    if ($totalSize > 1 * 1024 * 1024 * 1024) {
                        $fail('The total size of all files must not exceed 1GB.');
                    }
                },
            ],
            'attachments.*' => [
                'file',
                File::types(self::$extenstions)
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
        $body = $this->input('body') ?: '';
        $previewUrl = $this->input('preview_url') ?: '';

        $trimmedBody = trim(strip_tags($body));
        if ($trimmedBody === $previewUrl) {
            $body = '';
        }

        // Add your custom key to the request data
        $this->merge([
            'user_id' => auth()->user()->id,
            'body' => $body
        ]);
        // $this->merge([
        //     'user_id' => auth()->id(),
        //     // 'body' => preg_replace_callback('/(#\#+)(?![^<]*<\/a>)/', function($a){
        //     //     return '<a href="/search/'.urlencode($a[0]).'">' . $a[0] . '</a>';
        //     // } ,$this->input('body') ?: ''),
        //     'body' => $this->input('body') ?: '',
        // ]);
    }

    public function messages()
    {
        return [
            'attachments.*.file' => 'Each attachment must be a file.',
            'attachments.*.mimes' => 'Invalid file type for attachments.',
            'attachments.*.max' => 'Kích thước file không vượt quá 500MB.',
        ];
    }
}
