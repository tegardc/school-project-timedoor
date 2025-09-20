<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // sudah diamankan pakai sanctum middleware
    }

    public function rules(): array
    {
        $rules = [
            'fullname'   => ['required', 'string'],
            'dateOfBirth'=> ['required', 'date'],
            'nisn'       => ['required', 'string'],
            'schoolDetailId' => ['required', 'exists:school_details,id'],
        ];

        if ($this->user()->hasRole('student')) {
            $rules['studentValidation'] = ['nullable', 'file', 'mimes:jpg,png,pdf', 'max:2048'];
        }

        if ($this->user()->hasRole('parent')) {
            $rules['relation'] = ['required', 'in:Orang Tua,Wali'];
            $rules['schoolValidation'] = ['nullable', 'file', 'mimes:jpg,png,pdf', 'max:2048'];
        }

        return $rules;
    }
}
