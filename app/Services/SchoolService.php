<?php

namespace App\Services;

use App\Models\School;
use Illuminate\Support\Facades\DB;

class SchoolService extends BaseService
{
    public function __construct()
    {
        $this->modelClass = School::class;
    }
    public function store(array $validated): School
    {
        return DB::transaction(function () use ($validated) {
            $school = School::create($validated);
            return $school;
        });
    }
    public function update(array $validated, int $id): ?School
    {
        return DB::transaction(function () use ($validated, $id) {
            $school = School::find($id);
            if (!$school) {
                return null;
            }
            $school->update($validated);
            return $school;
        });
    }

    public function getAll($perPage = null){
        $query = School::select([
            'id',
            'name',
            'provinceId',
            'districtId',
            'subDistrictId',
            'schoolEstablishmentDecree'
        ])->with([
            'province:id,name',
            'district:id,name',
            'subDistrict:id,name'
        ]);
        return $query->paginate($perPage??10);
    }

}
