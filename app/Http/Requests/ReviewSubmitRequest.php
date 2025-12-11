<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewSubmitRequest extends FormRequest
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
        return [
            // data kelengkapan user
            'fullname' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phoneNo' => 'nullable|string|max:20',
            'userStatus' => 'required|in:aktif,alumni',
            'schoolDetailId' => 'nullable|integer|exists:school_details,id',
            'schoolValidationFile' => 'nullable|string|max:255|url',
            'liked'    => 'nullable|string|max:500',
            'improved' => 'nullable|string|max:500',


            'reviewText' => 'nullable|string|max:5000',
            'liked' => 'nullable|string|max:2000',
            'improved' => 'nullable|string|max:2000',

            'details' => 'required|array|min:1',
            'details.*.questionId' => 'required|integer|exists:questions,id',
            'details.*.score' => 'required|integer|min:1|max:5',
        ];
    }
}
