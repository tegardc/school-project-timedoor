<?php

namespace App\Http\Requests;

use App\Models\District;
use App\Models\Province;
use App\Models\SubDistrict;
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
            'name' => ['required', 'string', 'max:255'],
            'provinceId' => ['required', 'exists:provinces,id'],
            'districtId' => ['required', 'exists:districts,id'],
            'subDistrictId' => ['required', 'exists:sub_districts,id'],
            'schoolEstablishmentDecree' => ['nullable', 'string', 'max:255'],
            // 'imageUrl' => ['sometimes', 'array'],
            // 'imageUrl.*' => ['url'],
            //
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules = [
                'name' => ['nullable', 'string', 'max:255'],
                'provinceId' => ['nullable', 'exists:provinces,id'],
                'districtId' => ['nullable', 'exists:districts,id'],
                'subDistrictId' => ['nullable', 'exists:sub_districts,id'],
                'schoolEstablishmentDecree' => ['nullable', 'string', 'max:255'],
            ];
        }
        return $rules;
    }
}
//     public function withValidator($validator)
//     {
//         $validator->after(function ($validator) {
//             $provinceId = $this->input('provinceId');
//             $districtId = $this->input('districtId');
//             $subDistrictId = $this->input('subDistrictId');
//             $province = Province::find($provinceId);
//             $district = District::find($districtId);
//             $subDistrict = SubDistrict::find($subDistrictId);
//             if (
//                 $province->provinceId != $provinceId ||
//                 $district->districtId != $districtId ||
//                 $subDistrict->subDistrictId != $subDistrictId
//             ) {
//                 $validator->errors()->add('Locations', 'Location data does not match');
//             }
//         });
//     }
// }
