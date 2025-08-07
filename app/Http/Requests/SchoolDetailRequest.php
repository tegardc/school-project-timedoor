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
            'institutionCode' => ['required', 'string','unique:school_details,institutionCode'],
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
            'facilityIds' => ['array'],
            'facilityIds.*' => ['exists:facilities,id'],
            'contacts' => ['nullable', 'array'],
            'contacts.*.type' => ['required', 'string'],
            'contacts.*.value' => ['required', 'string'],
            // 'telpNo' => ['required', 'string'],
            'tuitionFee' => ['nullable', 'string'],
            'numStudent' => ['required', 'integer'],
            'numTeacher' => ['required', 'integer'],
            'imageUrl' => ['required', 'array'],
            'movie' => ['required', 'string'],
            'examInfo' => ['nullable', 'string']
            //
        ];
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules = [
                'name' => ['nullable', 'string'],
                'institutionCode' => ['nullable', 'string'],
                'schoolId' => ['nullable', 'exists:schools,id'],
                'statusId' => ['nullable', 'exists:school_statuses,id'],
                'educationLevelId' => ['nullable', 'exists:education_levels,id'],
                'ownershipStatus' => ['nullable', 'string'],
                'dateEstablishmentDecree' => ['nullable', 'string'],
                'operationalLicense' => ['nullable', 'string'],
                'dateOperationalLicense' => ['nullable', 'string'],
                'principal' => ['nullable', 'string'],
                'operator' => ['nullable', 'string'],
                'accreditationId' => ['nullable', 'exists:accreditations,id'],
                'curriculum' => ['nullable', 'string'],
                'facilityIds' => ['array'],
                'facilityIds.*' => ['exists:facilities,id'],
                'contacts' => ['nullable', 'array'],
                'contacts.*.type' => ['nullable', 'string'],
                'contacts.*.value' => ['nullable', 'string'],

                // 'telpNo' => ['nullable', 'string'],
                'tuitionFee' => ['nullable', 'string'],
                'numStudent' => ['nullable', 'integer'],
                'numTeacher' => ['nullable', 'integer'],
                'imageUrl' => ['nullable', 'array'],
                'movie' => ['nullable', 'string'],
                'examInfo' => ['nullable', 'string']
            ];
        }

        return $rules;
    }
}
