<?php

namespace App\Services;

use App\Models\SchoolStatus;
use Illuminate\Support\Facades\DB;

class SchoolStatusService
{
    public function getAll()
    {
        $schoolStatus = SchoolStatus::select([
            'id',
            'name'
        ]);
        return $schoolStatus->get();
    }

    public function getById($id)
    {
        $schoolStatus = SchoolStatus::select([
            'id',
            'name'
        ]);
        return $schoolStatus->find($id);
    }
}
