<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Http\Resources\UserResource;
use App\Models\Child;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
//
 public function updateProfile(array $data): void
{
    $user = Auth::user();

    DB::transaction(function () use ($user,  $data) {
        $user->update([
            'firstName' => $data['firstName'] ?? $user->firstName,
            'lastName'  => $data['lastName'] ?? $user->lastName,
            'email'     => $data['email'] ?? $user->email,
            'gender'    => $data['gender'] ?? $user->gender,
            'phoneNo'   => $data['phoneNo'] ?? $user->phoneNo,
            'image'     => $data['image'] ?? $user->image,
            'address'   => $data['address'] ?? $user->address,
        ]);

    });

    $user->refresh();

}
    public function showUser(): User
    {
        $user = Auth::user();

        // load relasi educationExperiences + relasi2 di dalamnya
        $user->load([
            'educationExperiences.educationLevel',
            'educationExperiences.schoolDetail',
            'educationExperiences.educationProgram',
        ]);

        return $user;
    }
}
