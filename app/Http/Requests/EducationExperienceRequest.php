<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EducationExperienceRequest extends FormRequest
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

            'educationLevelId' => 'required|exists:education_levels,id',
            'schoolDetailId' => 'required|exists:school_details,id',
            // 'educationProgramId' => 'nullable|exists:education_programs,id',
            // 'degree' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            //
        ];
        if($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules = [
                'educationLevelId' => 'nullable|exists:education_levels,id',
                'schoolDetailId' => 'nullable|exists:school_details,id',
                // 'educationProgramId' => 'nullable|exists:education_programs,id',
                // 'degree' => 'nullable|string|max:255',
                'status' => 'nullable|string|max:255',
                'startDate' => 'nullable|date',
                'endDate' => 'nullable|date|after_or_equal:startDate',
                //
            ];
        }
        return $rules;
    }
}
