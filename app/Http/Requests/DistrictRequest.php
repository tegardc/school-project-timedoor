<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DistrictRequest extends FormRequest
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
        $rules =  [
            'name' => ['required', 'string'],
            'provinceId' => ['required', 'exists:provinces,id']
            //
        ];
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules = [
                'name' => ['nullable', 'string'],
                'provinceId' => ['nullable', 'exists:provinces,id']
            ];
        }
        return $rules;
    }
}
