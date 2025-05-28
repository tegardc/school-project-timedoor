<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $userId = $this->route('user');
        $rules = [
            'firstName' => ['required', 'string'],
            'lastName'  => ['required', 'string'],
            'username'   => ['required', 'string', 'unique:users,username'],
            'gender'     => ['required', 'in:male,female'],
            'phoneNo'   => ['required', 'string'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$!%*?&])[A-Za-z\d@#$!%*?&]+$/'
            ],
            'confirm_password' => ['required', 'string', 'same:password'],
            'role' => ['required', 'in:parent,student'],

            //
        ];
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules = [
                'firstName' => ['nullable', 'string'],
                'lastName'  => ['nullable', 'string'],
                'username'  => ['nullable', 'string', Rule::unique('users', 'username')->ignore($userId)],
                'gender'    => ['nullable', 'in:male,female'],
                'phoneNo'   => ['nullable', 'string'],
                'email'     => ['nullable', 'email', Rule::unique('users', 'email')->ignore($userId)],
                'current_password' => ['nullable', 'required_with:new_password'],
                'new_password' => ['nullable', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$!%*?&])[A-Za-z\d@#$!%*?&]+$/'],
            ];
        }
        return $rules;
    }
}
