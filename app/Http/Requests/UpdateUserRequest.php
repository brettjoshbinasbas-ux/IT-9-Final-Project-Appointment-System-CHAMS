<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var AuthManager $auth */
        $auth = auth();

        return $auth->check() && $auth->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $userId,
            'password' => 'nullable|min:8|confirmed',
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter a name.',
            'email.required' => 'Please enter an email address.',
            'email.unique' => 'This email is already registered.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'role.required' => 'Please select a role.',
        ];
    }
}