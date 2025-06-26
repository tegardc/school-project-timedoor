<?php

namespace App\Services;

use App\Models\Province;
use Illuminate\Support\Facades\DB;

class ProvinceService
{

    public function store(array $validated): Province
    {
        return DB::transaction(function () use ($validated) {
            $province = Province::create($validated);
            return $province;
        });
    }
    public function update(array $validated, int $id): ?Province
    {
        return DB::transaction(function () use ($validated, $id) {
            $province = Province::find($id);
            if (!$province) {
                return null;
            }
            $province->update($validated);
            return $province;
        });
    }
    public function getAll($perPage = null){
        $query = Province::select([
            'id',
            'name',
        ]);
        return $query->paginate($perPage??10);

    }
    // public function destroy(array $validated, int $id): ?Province
    // {
    //     return DB::transaction(function () use ($validated, $id) {
    //         $province = Province::find($id);
    //         if (!$province) {
    //             return null;
    //         }
    //         $province->delete($validated);
    //         return $province;
    //     });
    // }
}
