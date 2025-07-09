<?php

namespace App\Services;

use App\Models\District;
use Illuminate\Support\Facades\DB;

class DistrictService extends BaseService
{
    public function __construct()
    {
        $this->modelClass = District::class;
    }

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
    // public function softDelete(int $id): ?District
    // {
    //     return DB::transaction(function () use ($id) {
    //         $district = District::find($id);
    //         if (!$district) {
    //             return null;
    //         }
    //         $district->delete();
    //         return $district;
    //     });
    // }
    // public function trash(){
    //     return District::onlyTrashed()
    //         ->orderByDesc('deletedAt')
    //         ->get();
    // }

    // public function restore(int $id): ?District
    // {
    //     return DB::transaction(function () use ($id) {
    //         $district = District::withTrashed()->find($id);
    //         if (!$district) {
    //             return null;
    //         }
    //         $district->restore();
    //         return $district;
    //     });
    // }
}
