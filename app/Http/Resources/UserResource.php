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

            'child' => $this->when(
                $this->hasRole('parent'),
                function () {
                    return $this->children->map(function ($child) {
                        return [
                            'id'              => $child->id,
                            'fullname'        => $child->fullname,
                            'dateOfBirth'     => $child->dateOfBirth,
                            'nisn'            => $child->nisn,
                            'email'           => $child->email,
                            'phoneNo'         => $child->phoneNo,
                            'schoolValidation'=> $child->schoolValidation,
                            'schoolDetail'    => $child->schoolDetail
                                ? [
                                    'id'   => $child->schoolDetail->id,
                                    'name' => $child->schoolDetail->name,
                                ]
                                : null,
                        ];
                    });
                }
            ),
        ];
    }
}
