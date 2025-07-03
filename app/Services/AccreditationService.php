<?php

namespace App\Services;

use App\Models\Accreditation;
use Illuminate\Support\Facades\DB;

class AccreditationService
{
    public function getAll()
    {
        $accreditation = Accreditation::select([
            'id',
            'code'
        ]);
        return $accreditation->get();
    }
    public function getById($id)
    {
        $accreditation = Accreditation::select([
            'id',
            'code'
        ]);
        return $accreditation->find($id);
    }
}
