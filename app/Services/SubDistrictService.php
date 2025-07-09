<?php

namespace App\Services;

use App\Models\SubDistrict;
use Illuminate\Support\Facades\DB;

class SubDistrictService extends BaseService
{
     public function __construct()
    {
        $this->modelClass = SubDistrict::class;
    }

    public function store(array $validated): SubDistrict
    {
        return DB::transaction(function () use ($validated) {
            $subDistrict = SubDistrict::create($validated);
            return $subDistrict;
        });
    }
    public function update(array $validated, int $id): ?SubDistrict
    {
        return DB::transaction(function () use ($validated, $id) {
            $subDistrict = SubDistrict::find($id);
            if (!$subDistrict) {
                return null;
            }
            $subDistrict->update($validated);
            return $subDistrict;
        });
    }
    public function getByDistrict(string $districtName)
    {
        return SubDistrict::whereHas('districts', function ($query) use ($districtName)  {
            $query->where('name','like',"%{$districtName}%");
        })->get();

    }
//     public function softDelete(int $id): ?SubDistrict
//     {
//         return DB::transaction(function () use ($id) {
//             $subDistrict = SubDistrict::find($id);
//             if (!$subDistrict) {
//                 return null;
//             }
//             $subDistrict->delete();
//             return $subDistrict;
//         });
//     }
//    public function trash()
// {
//     return SubDistrict::onlyTrashed()
//         ->orderByDesc('deletedAt')
//         ->get();
// }

//     public function restore(int $id): ?SubDistrict
//     {
//         return DB::transaction(function () use ($id) {
//             $subDistrict = SubDistrict::withTrashed()->find($id);
//             if (!$subDistrict) {
//                 return null;
//             }
//             $subDistrict->restore();
//             return $subDistrict;
//         });
//     }

}
