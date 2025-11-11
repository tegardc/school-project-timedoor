<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // ambil role pertama (karena user hanya punya satu role aktif dalam flow ini)
        $role = $this->roles->pluck('name')->first();

        // === Untuk Role STUDENT ===
        if ($role === 'student') {
            return [
                'id'        => $this->id,
                'image'     => $this->image,
                'fullname'  => $this->fullname,
                'nisn'      => $this->nisn,
                'status'    => 'aktif', // default aktif
                'schoolDetail' => optional($this->educationExperiences->last()?->schoolDetail)->name,
                'email'     => $this->email,
                'birthdate' => $this->dateOfBirth,
                'phoneNo'   => $this->phoneNo,
                'address'   => $this->address,
                'role'      => $role,
                'riwayatPendidikan' => $this->educationExperiences->map(function ($edu) {
                    return [
                        'id'           => $edu->id,
                        'schoolDetail' => optional($edu->schoolDetail)->name,
                        'status'       => $edu->status,
                    ];
                }),
            ];
        }

        // === Untuk Role PARENT ===
        if ($role === 'parent') {
            return [
                'id'        => $this->id,
                'image'     => $this->image,
                'fullname'  => $this->fullname,
                'relation'  => optional($this->children->first())->relation,
                'email'     => $this->email,
                'phoneNo'   => $this->phoneNo,
                'address'   => $this->address,
                'role'      => $role,
                'child' => $this->children->map(function ($child) {
                    return [
                        'fullname'   => $child->fullname,
                        'nisn'       => $child->nisn,
                        'status'     => $child->status,
                        'birthdate'  => $child->dateOfBirth,
                        'schoolDetail' => optional($child->schoolDetail)->name,

                        'riwayatPendidikan' => $child->educationExperiences->map(function ($edu) {
                            return [
                                'id'           => $edu->id,
                                'schoolDetail' => optional($edu->schoolDetail)->name,
                                'status'       => $edu->status,
                            ];
                        }),
                    ];
                }),
            ];
        }

        // === Default (kalau tidak ada role) ===
        return [
            'fullname' => $this->fullname,
            'email' => $this->email,
            'role' => $role,
        ];
    }
}
