<?php

namespace App\Services;

use App\Models\Facility;
use Illuminate\Support\Facades\DB;

class FacilityService extends BaseService
{
    public function __construct()
    {
        $this->modelClass = Facility::class;
    }

    public function store(array $data): Facility
    {
        return DB::transaction(function () use ($data) {
            return Facility::create($data);
        });
    }

    public function update(array $data, int $id): ?Facility
    {
        return DB::transaction(function () use ($data, $id) {
            $facility = Facility::find($id);
            if (!$facility) return null;
            $facility->update($data);
            return $facility;
        });
    }

    public function getAll()
    {
        $facility = Facility::select([
            'id',
            'name',
        ]);
        return $facility->get();
    }

}
