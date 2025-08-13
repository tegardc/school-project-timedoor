<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Http\Resources\UserResource;
use App\Models\User;
Use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            'gender',
            'image'
        ]);
        return $query->paginate($perPage??10);
    }
     public function updateUser(User $user, array $validated)
    {
        if (!empty($validated['current_password']) && !empty($validated['new_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return ResponseHelper::error("Current password is incorrect", 400);
            }
            $user->password = Hash::make($validated['new_password']);
        }

        $user->update($validated);
        $user->refresh();

        return $user;
    }

}
