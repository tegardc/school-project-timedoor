<?php

namespace App\Http\Requests;

use App\Models\Child;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function wantsJson()
    {
        return true;
    }
    public function isStudent(): bool
    {
        return $this->input('role') === 'student';
    }

    public function isParent(): bool
    {
        return $this->input('role') === 'parent';
    }



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user');
        $rules = [
            'firstName' => ['required', 'string'],
            'lastName'  => ['required', 'string'],
            'username'   => ['required', 'string', 'unique:users,username'],
            'gender'     => ['required', 'in:male,female'],
            'phoneNo'   => ['required', 'string'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$!%*?&])[A-Za-z\d@#$!%*?&]+$/'
            ],
            'confirm_password' => ['required', 'string', 'same:password'],
            'role' => ['required', 'in:parent,student'],

            //
        ];
        if ($this->isStudent()) {
            $rules = array_merge($rules, [
                'nis' => ['required', 'string', 'unique:users,nis'],
                'provinceId' => ['required', 'exists:provinces,id'],
                'districtId' => ['required', 'exists:districts,id'],
                'subDistrictId' => ['required', 'exists:sub_districts,id'],
                'schoolDetailId' => ['required', 'exists:school_details,id'],
            ]);
        }

        if ($this->isParent()) {
            $rules = array_merge($rules, [
                'childName' => ['required', 'string'],
                'nis' => ['required', 'string', 'unique:childs,nis'],
                'provinceId' => ['required', 'exists:provinces,id'],
                'districtId' => ['required', 'exists:districts,id'],
                'subDistrictId' => ['required', 'exists:sub_districts,id'],
                'schoolDetailId' => ['required', 'exists:school_details,id'],
            ]);
        }


        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules = [
                'firstName' => ['nullable', 'string'],
                'lastName'  => ['nullable', 'string'],
                'username'  => ['nullable', 'string', Rule::unique('users', 'username')->ignore($userId)],
                'gender'    => ['nullable', 'in:male,female'],
                'phoneNo'   => ['nullable', 'string'],
                'email'     => ['nullable', 'email', Rule::unique('users', 'email')->ignore($userId)],
                'current_password' => ['nullable', 'required_with:new_password'],
                'new_password' => ['nullable', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$!%*?&])[A-Za-z\d@#$!%*?&]+$/'],
            ];
        }
        return $rules;
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $provinceId = $this->input('provinceId');
            $districtId = $this->input('districtId');
            $subDistrictId = $this->input('subDistrictId');
            $schoolDetailId = $this->input('schoolDetailId');
            if ($schoolDetailId && $provinceId && $districtId && $subDistrictId) {
                $schoolDetail = \App\Models\school_detail::with('schools')->find($schoolDetailId);
                if (!$schoolDetail || !$schoolDetail->schools) {
                    $validator->errors()->add('schoolDetailId', 'School data not found.');
                    return;
                }
                $school = $schoolDetail->schools;
                if (
                    $school->provinceId != $provinceId ||
                    $school->districtId != $districtId ||
                    $school->subDistrictId != $subDistrictId
                ) {
                    $validator->errors()->add('schoolDetailId', 'Location data does not match school location.');
                }
            }
        });
    }

    // public function withValidator($validator)
    // {
    //     $validator->after(function ($validator) {
    //         if ($this->input('role') === 'parent') {
    //             $nis = $this->input('nis');
    //             $schoolDetailId = $this->input('schoolDetailId');

    //             if ($nis && $schoolDetailId) {
    //                 $exists = DB::table('user_child_school')
    //                     ->join('childs', 'user_child_school.childId', '=', 'childs.id')
    //                     ->where('childs.nis', $nis)
    //                     ->where('user_child_school.schoolDetailId', $schoolDetailId)
    //                     ->exists();

    //                 if (!$exists) {
    //                     $validator->errors()->add('nis', 'NIS tidak terdaftar di sekolah yang dipilih.');
    //                 }
    //             }
    //         }
    //     });
    // }
}
