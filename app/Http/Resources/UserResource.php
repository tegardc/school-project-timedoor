<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'fullname'    => $this->fullname,
            'email'       => $this->email,
            'phoneNo'     => $this->phoneNo,
            'address'     => $this->address,
            'image'       => $this->image,
            'dateOfBirth' => $this->dateOfBirth,
            'nisn'        => $this->nisn,
            'roles'       => $this->roles->pluck('name'),

            // === Jika user adalah parent ===
            'child' => $this->when(
                $this->hasRole('parent'),
                fn() => $this->children->map(function ($child) {
                    return [
                        'id'              => $child->id,
                        'fullname'        => $child->fullname,
                        'dateOfBirth'     => $child->dateOfBirth,
                        'nisn'            => $child->nisn,
                        'email'           => $child->email,
                        'phoneNo'         => $child->phoneNo,
                        'schoolValidation' => $child->schoolValidation,
                        'schoolDetail'    => $child->schoolDetail
                            ? [
                                'id'     => $child->schoolDetail->id,
                                'name'   => $child->schoolDetail->name,
                            ]
                            : null,
                        'riwayatPendidikan' => optional($child->educationExperiences)->map(function ($edu) {
                            return [
                                'id'     => $edu->schoolDetail->id ?? null,
                                'name'   => $edu->schoolDetail->name ?? null,
                                'status' => $edu->status ?? null,
                            ];
                        }),
                    ];
                })
            ),

            'schoolDetails' => $this->when(
                $this->hasRole('student'),
                fn() => $this->childSchoolDetails->map(function ($school) {
                    return [
                        'id'     => $school->id,
                        'name'   => $school->name,
                    ];
                })
            ),

            'riwayatPendidikan' => $this->when(
                $this->hasRole('student'),
                fn() => $this->educationExperiences->map(function ($edu) {
                    return [
                        'id'     => $edu->schoolDetail->id ?? null,
                        'name'   => $edu->schoolDetail->name ?? null,
                        'status' => $edu->status,
                    ];
                })
            ),
        ];
    }
}
