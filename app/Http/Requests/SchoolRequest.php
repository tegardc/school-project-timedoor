<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolRequest extends FormRequest
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
        $rules = [
            'name' => ['sometimes', 'string', 'max:255'],
            'provinceId' => ['sometimes', 'exists:provinces,id'],
            'districtId' => ['sometimes', 'exists:districts,id'],
            'subDistrictId' => ['sometimes', 'exists:sub_districts,id'],
            'schoolEstablishmentDecree' => ['nullable', 'string', 'max:255'],
            'imageUrl' => ['sometimes', 'array'],
            'imageUrl.*' => ['url'],

            //
        ];
        return $rules;
    }
}
