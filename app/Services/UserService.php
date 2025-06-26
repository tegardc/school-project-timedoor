<?php

namespace App\Services;

use App\Models\User;
Use Illuminate\Support\Facades\DB;

class UserService
{
    public function getAll($perPage = null){
        $query = User::select([
            'id',
            'firstName',
            'lastName',
            'username',
            'email',
            'phoneNo',
            'nis',
            'gender'
        ]);
        return $query->paginate($perPage??10);
    }
}
