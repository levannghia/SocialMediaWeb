<?php

namespace App\Http\Requests;

use App\Http\Enums\EventUserStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class StoreEventRequest extends FormRequest
{

    public static array $extenstions = [
        'jpg', 'png', 'gif', 'jpeg', 'PNG'
    ];

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
            'title' => ['string', 'required'],
            'categoryId' => ['required', 'numeric'],
            'description' => ['nullable'],
            'locationTitle' => ['nullable', 'string'],
            'locationAddress' => ['required', 'string'],
            'position' => ['required', 'array'],
            'fileType' => ['required', Rule::enum(EventUserStatus::class)],
            'fileUrl' => ['string', 'nullable'],
            'startAt' => ['required', 'string'],
            'endAt' => ['required', 'string'],
            'date' => ['required', 'string'],
            'price' => ['nullable', 'numeric'],
            'image' => [
                'file',
                File::types(self::$extenstions)
                    ->max(10 * 1024 * 1024),
            ],
            'users' => ['array', 'nullable'],
            'users.*' => ['exists:users,id']
        ];
    }
}
