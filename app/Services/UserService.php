<?php

namespace App\Services;

use App\Models\User;
Use Illuminate\Support\Facades\DB;

class UserService extends BaseService
{
    public function __construct()
    {
        $this->modelClass = User::class;
    }
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
