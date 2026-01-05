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
        return $this->user()?->hasRole('student');
    }

    public function isParent(): bool
    {
        return $this->user()?->hasRole('parent');
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
        'fullname'   => ['required', 'string'],
        // 'username'   => ['required', 'string', 'unique:users,username'],
        'email'      => ['required', 'email', 'unique:users,email'],
        'password'   => [
            'required',
            'string',
            'min:8',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$!%*?&])[A-Za-z\d@#$!%*?&]+$/'
        ],
        // 'confirm_password' => ['required', 'string', 'same:password'],
        'role' => ['required', 'in:parent,student'],
    ];

    if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
    $rules = [
        'firstName' => ['nullable', 'string'],
        'lastName'  => ['nullable', 'string'],
        // 'username'  => ['nullable', 'string', Rule::unique('users', 'username')->ignore($userId)],
        'gender'    => ['nullable', 'in:male,female'],
        'phoneNo'   => ['nullable', 'string'],
        'image'     => ['nullable', 'string'],

        'address'   => ['nullable', 'string'],
        'email'     => ['nullable', 'email', Rule::unique('users', 'email')->ignore($userId)],
        'current_password' => ['nullable', 'required_with:new_password'],
        'new_password' => [
            'nullable',
            'confirmed',
            'min:8',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$!%*?&])[A-Za-z\d@#$!%*?&]+$/'
        ],
    ];

//     if ($this->isStudent()) {
//     $rules = array_merge($rules, [
//         'nis' => [
//             'nullable',
//             'string',
//             Rule::unique('childs')
//                 ->where(fn ($q) =>
//                     $q->where('schoolDetailId', $this->input('schoolDetailId'))
//                 )
//                 ->ignore($this->route('child')), // kalau update
//         ],
//         'schoolDetailId' => ['nullable', 'exists:school_details,id'],
//     ]);
// }

//         if ($this->isParent()) {
//             $rules = array_merge($rules, [
//                 'childName' => ['nullable', 'string'],
//                 'nis' => [
//                     'nullable',
//                     'string',
//                     Rule::unique('childs')->where(fn ($q) =>
//                         $q->where('schoolDetailId', $this->input('schoolDetailId'))
//                     ),
//                 ],
//                 'schoolDetailId' => ['nullable', 'exists:school_details,id'],
//             ]);
//         }
//     }
    }

    return $rules;
}

}
