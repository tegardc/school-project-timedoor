<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolDetailRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'institutionCode' => ['required', 'string'],
            'schoolId' => ['required', 'exists:schools,id'],
            'statusId' => ['required', 'exists:school_statuses,id'],
            'educationLevelId' => ['required', 'exists:education_levels,id'],
            'ownershipStatus' => ['required', 'string'],
            'dateEstablishmentDecree' => ['required', 'string'],
            'operationalLicense' => ['required', 'string'],
            'dateOperationalLicense' => ['required', 'string'],
            'principal' => ['required', 'string'],
            'operator' => ['required', 'string'],
            'accreditationId' => ['required', 'exists:accreditations,id'],
            'curriculum' => ['required', 'string'],
            'telpNo' => ['required', 'string'],
            'tuitionFee' => ['nullable', 'string'],
            'numStudent' => ['required', 'integer'],
            'numTeacher' => ['required', 'integer'],
            'movie' => ['required', 'string'],
            'examInfo' => ['nullable', 'string']
            //
        ];
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['institutionCode'] = ['nullable', 'string'];
        }

        return $rules;
    }
}
