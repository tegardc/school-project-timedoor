<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data' => 'required|array|min:1',

            // Wajib ada NPSN untuk mencocokkan sekolah
            'data.*.npsn' => 'required',

            // Rating (Bintang) wajib ada dan harus angka
            'data.*.review_rating' => 'required|numeric',

            // review_text BOLEH KOSONG (nullable) karena user Google kadang cuma kasih bintang
            'data.*.review_text' => 'nullable|string',
        ];
    }
}
