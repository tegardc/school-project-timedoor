<?php

namespace App\Services;

use App\Models\EducationLevel;
use Illuminate\Support\Facades\DB;

class EducationLevelService
{
    public function getAll()
    {
        $educationLevel = EducationLevel::select([
            'id',
            'name'
        ]);
        return $educationLevel->get();
    }
    public function getById($id)
    {
        $educationLevel = EducationLevel::select([
            'id',
            'name'
        ]);
        return $educationLevel->find($id);
    }
}
