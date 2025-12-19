<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateForgotPaswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function wantsJson()
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
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required|string|exists:verification_tokens,token',
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Kata Sandi wajib diisi',
            'password.string' => 'Password harus berupa string',
            'password.min' => 'Password harus terdiri dari minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'token.required' => 'Token wajib diisi',
            'token.string' => 'Token harus berupa string',
            'token.exists' => 'Token tidak valid',
        ];
    }
}
