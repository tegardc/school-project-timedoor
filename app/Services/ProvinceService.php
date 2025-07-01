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
/*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Update the specified province resource in storage.
     *
     * @param array $validated The validated data for updating the province.
     * @param int $id The ID of the province to update.
     * @return Province|null The updated province instance, or null if not found.
     */

/*******  72d7a11c-0d46-4718-a08b-aa3f99b8459a  *******/
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
