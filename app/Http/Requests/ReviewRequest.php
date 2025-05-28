<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['parent', 'student']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'reviewText' => ['required', 'string'],
            'rating' => ['required', 'numeric', 'between:1,5'],
            // 'userId' => ['required', 'exists:users,id'],
            // 'schoolDetailId' => ['required', 'exists:school_details,id']
            //
        ];
        return $rules;
    }
}
