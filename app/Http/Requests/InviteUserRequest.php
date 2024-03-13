<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Enums\GroupUserStatus;

class InviteUserRequest extends FormRequest
{
    public ?User $user = null;
    public Group $group;
    public ?GroupUser $groupUser = null;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $this->group = $this->route('group');

        return $this->group->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', function($attribute, $value, \Closure $fail){
                $this->user = User::query()->where('email', $value)->orWhere('username', $value)->first();
                if(!$this->user){
                    $fail('User does not exits');
                }

                $this->groupUser = GroupUser::query()->where('user_id', $this->user?->id)->where('group_id', $this->group->id)->first();

                if($this->groupUser && $this->groupUser->status === GroupUserStatus::APPROVED->value){
                    $fail('User does not exits');
                }
            }]
        ];
    }
}
