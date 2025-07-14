<?php

namespace App\Services;

use App\Models\Province;
use Illuminate\Support\Facades\DB;

class ProvinceService extends BaseService
{
    public function __construct()
    {
        $this->modelClass = Province::class;
    }

    public function store(array $validated): Province
    {
        return DB::transaction(function () use ($validated) {
            $province = Province::create($validated);
            return $province;
        });
    }
    /**
     * Update the specified province resource in storage.
     *
     * @param array $validated The validated data for updating the province.
     * @param int $id The ID of the province to update.
     * @return Province|null The updated province instance, or null if not found.
     */

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
