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
}
