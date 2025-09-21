<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
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
        if ($this->input('child')) {
            $rules['child.fullname']        = 'nullable|string|max:255';
            $rules['child.dateOfBirth']     = 'nullable|date';
            $rules['child.nisn']            = 'nullable|string|max:20';
            $rules['child.email']           = 'nullable|email|max:255';
            $rules['child.phoneNo']         = 'nullable|string|max:20';
            $rules['child.schoolDetailId']  = 'nullable|integer|exists:school_details,id';
            $rules['child.schoolValidation']= 'nullable|string|max:255';
        }

        return $rules;
    }
}
