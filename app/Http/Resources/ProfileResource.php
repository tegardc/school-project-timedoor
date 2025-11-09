<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request)
    {
        $data = $this->resource;

        if ($data instanceof \App\Models\User) {

            if (method_exists($data, 'hasRole') && $data->hasRole('student')) {
                return [
                    'fullname'     => $data->fullname,
                    'email'        => $data->email,
                    'nisn'         => $data->nisn,
                    'status'       => $data->status ?? 'aktif',
                    'schoolDetail' => $data->educationExperiences->first()?->schoolDetail?->name ?? null,
                ];
            }

            if (method_exists($data, 'hasRole') && $data->hasRole('parent')) {
                $child = $data->child;
                return [
                    'fullname'     => $data->fullname,
                    'email'        => $data->email,
                    'relation'     => $child?->relation,
                    'childname'    => $child?->fullname,
                    'nisn'         => $child?->nisn,
                    'status'       => $child?->status ?? 'aktif',
                    'schoolDetail' => $child?->schoolDetail?->name ?? null,
                ];
            }
        }

        if ($data instanceof \App\Models\Child) {
            $parent = $data->parent;

            return [
                'fullname'     => $parent?->fullname,
                'email'        => $parent?->email,
                'relation'     => $data->relation,
                'childname'    => $data->fullname,
                'nisn'         => $data->nisn,
                'status'       => $data->status ?? 'aktif',
                'schoolDetail' => $data->schoolDetail?->name ?? null,
            ];
        }

        return [
            'fullname' => $data->fullname ?? null,
            'email'    => $data->email ?? null,
        ];
    }
}
