<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = Auth::user();
        $rules = [
            'fullname'    => 'nullable|string|max:255',
            'email'       => 'nullable|email|max:255',
            'phoneNo'     => 'nullable|string|max:20',
            'address'     => 'nullable|string|max:500',
            'image'       => 'nullable|string|max:255',
            'nisn'        => 'nullable|string|max:20',
            'dateOfBirth' => 'nullable|date',
        ];

        // kalau ada child
        if ($user && $user->hasRole('parent')) {
            // Parent bisa update data anak
            $rules['child'] = 'nullable|array';
            $rules['child.fullname']        = 'nullable|string|max:255';
            $rules['child.dateOfBirth']     = 'nullable|date';
            $rules['child.nisn']            = 'nullable|string|max:20';
            $rules['child.email']           = 'nullable|email|max:255';
            $rules['child.phoneNo']         = 'nullable|string|max:20';
            $rules['child.address']         = 'nullable|string|max:500';
        }

        // Validasi password (opsional)
        $rules['current_password'] = ['nullable', 'required_with:new_password'];
        $rules['new_password'] = [
            'nullable',
            'confirmed',
            'min:8',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$!%*?&])[A-Za-z\d@#$!%*?&]+$/'
        ];

        return $rules;
    }
     public function messages(): array
    {
        return [
            'fullname.string' => 'Nama lengkap tidak valid.',
            'email.email' => 'Format email tidak valid.',
            'child.fullname.string' => 'Nama anak tidak valid.',
            'child.nisn.string' => 'NISN anak harus berupa teks.',
            'current_password.required_with' => 'Password saat ini wajib diisi jika ingin mengganti password.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ];
    }
}
