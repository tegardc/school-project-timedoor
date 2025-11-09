<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // sudah diamankan pakai sanctum middleware
    }

    public function rules(): array
    {
        $user = Auth::user();

        // Default: kosong
        $rules = [];

        // ✅ Jika role STUDENT
        if ($user && $user->hasRole('student')) {
            $rules = [
                'fullname'         => 'required|string|max:255',
                'dateOfBirth'      => 'required|date',
                'nisn'             => 'required|string|max:20|unique:users,nisn,' . $user->id,
                'schoolDetailId'   => 'required|exists:school_details,id',
                'schoolValidation' => 'nullable|string|max:255', // bisa jadi URL / path file
            ];
        }

        // ✅ Jika role PARENT
        elseif ($user && $user->hasRole('parent')) {
            $rules = [
                'fullname'         => 'required|string|max:255', // ini fullname anak
                'dateOfBirth'      => 'required|date',
                'nisn'             => 'required|string|max:20',
                'relation'         => 'required|string|max:50',
                'schoolDetailId'   => 'required|exists:school_details,id',
                'schoolValidation' => 'nullable|string|max:255',
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            // umum
            'fullname.required' => 'Nama lengkap wajib diisi.',
            'dateOfBirth.required' => 'Tanggal lahir wajib diisi.',
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.unique' => 'NISN ini sudah terdaftar, silakan periksa kembali.',
            'email.unique' => 'Email ini sudah digunakan, gunakan email lain.',
            'schoolDetailId.required' => 'Sekolah wajib dipilih.',
            'schoolDetailId.exists' => 'Sekolah tidak ditemukan di database.',

            // parent
            'relation.required' => 'Hubungan dengan anak wajib diisi.',
        ];
    }
}
