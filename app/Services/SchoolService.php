<?php

namespace App\Services;

use App\Models\School;
use Illuminate\Support\Facades\DB;

class SchoolService
{
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

}
