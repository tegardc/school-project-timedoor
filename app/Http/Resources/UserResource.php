<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
   public function toArray(Request $request): array
{
    // $isStudent = $this->hasRole('student');
    // $isParent  = $this->hasRole('parent');

    return [
        'id'        => $this->id,
        'firstName' => $this->firstName,
        'lastName'  => $this->lastName,
        // 'username'  => $this->username,
        'email'     => $this->email,
        'gender'    => $this->gender,
        'phoneNo'   => $this->phoneNo,
        'image'     => $this->image,
        'address'   => $this->address,
        'educationExperiences' => $this->educationExperiences->map(function ($exp) {
            return [
                'id'                => $exp->id,
                'role'              => $exp->role,
                'degree'            => $exp->degree,
                'startDate'         => $exp->startDate,
                'endDate'           => $exp->endDate,
                'educationLevel'    => optional($exp->educationLevel)->name,
                'schoolDetail'      => optional($exp->schoolDetail)->name,
                'educationProgram'  => optional($exp->educationProgram)->name,
            ];
        }),
        // 'roles'     => $this->getRoleNames(),
        'createdAt' => $this->createdAt,

        // 'nis'       => $isStudent
        //     ? $this->nis
        //     : optional($this->childs->first())->nis,

        // 'childName' => $isParent
        //     ? optional($this->childs->first())->name
        //     : null,

        // 'schoolDetails' => $this->childSchoolDetails->map(function ($detail) {
        //     return [
        //         'id'   => $detail->id,
        //         'name' => $detail->name,
        //     ];
        // }),
    ];
}

}
