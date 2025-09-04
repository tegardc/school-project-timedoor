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
    $isParent  = $this->hasRole('parent');

    return [
        'id'        => $this->id,
        'firstName' => $this->firstName,
        'lastName'  => $this->lastName,
        'username'  => $this->username,
        'email'     => $this->email,
        'gender'    => $this->gender,
        'phoneNo'   => $this->phoneNo,
        'image'     => $this->image,
        'roles'     => $this->getRoleNames(),

        // Student → ambil dari tabel users
        // Parent  → ambil dari child pertama
        'nis'       => $isStudent
            ? $this->nis
            : optional($this->childs->first())->nis,

        'childName' => $isParent
            ? optional($this->childs->first())->name
            : null,

        'schoolDetails' => $this->childSchoolDetails->map(function ($detail) {
            return [
                'id'   => $detail->id,
                'name' => $detail->name,
            ];
        }),
    ];
}

}
