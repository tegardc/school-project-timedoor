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
        $isStudent = $this->hasRole('student');
        $isParent = $this->hasRole('parent');
        return   [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'username' => $this->username,
            'email' => $this->email,
            'gender' => $this->gender,
            'phoneNo' => $this->phoneNo,
            'image' => $this->image,
            'roles' => $this->getRoleNames(),
            'nis' => $isStudent ? $this->nis : optional($this->childs->first())->nis,
            'childName' => $isParent ? optional($this->childs->first())->name : null,
            'schoolDetails' => $this->childSchoolDetails->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'name' => $detail->name,
                    // 'school' => [
                    //     'id' => optional($detail->schools)->id,
                    //     'name' => optional($detail->schools)->name,
                    //     'province' => optional($detail->schools->province)->name,
                    //     'district' => optional($detail->schools->district)->name,
                    //     'subDistrict' => optional($detail->schools->subDistrict)->name,
                    // ]
                ];
            }),
        ];
        // $role = $this->getRoleNames()->first();

        // if ($role === 'student') {
        //     $base['nis'] = $this->nis;
        //     $base['schoolDetailId'] = $this->schoolDetailId;
        // }

        // if ($role === 'parent') {
        //     $base['children'] = $this->children ? $this->children->map(function ($child) {
        //         return [
        //             'id' => $child->id,
        //             'name' => $child->name,
        //             'nis' => $child->nis,
        //             'schoolDetailId' => $child->schoolDetailId,
        //         ];
        //     }) : [];



    }
}
