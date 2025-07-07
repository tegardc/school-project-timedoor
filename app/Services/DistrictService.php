<?php

namespace App\Services;

use App\Models\District;
use Illuminate\Support\Facades\DB;

class DistrictService
{

    public function store(array $validated): District
    {
        return DB::transaction(function () use ($validated) {
            $district = District::create($validated);
            return $district;
        });
    }
    public function update(array $validated, int $id): ?District
    {
        return DB::transaction(function () use ($validated, $id) {
            $district = District::find($id);
            if (!$district) {
                return null;
            }
            $district->update($validated);
            return $district;
        });
    }
    public function getAll($perPage = null)
    {
        $query = District::select([
            'id',
            'name',
            'provinceId'
        ])->with([
            'province:id,name'
        ]);
        return $query->paginate($perPage??10);
    }

    public function getByProvince(string $provinceName){
        return District::whereHas('province', function ($query) use ($provinceName)  {
            $query->where('name','like',"%{$provinceName}%");
        })->get();
    }
}
