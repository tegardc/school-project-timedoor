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
     public function storeStudent(array $data, int $userId): User
    {
        return DB::transaction(function () use ($data, $userId) {
            $user = User::findOrFail($userId);

            $user->update([
                'fullname'         => $data['fullname'],
                'dateOfBirth'      => $data['dateOfBirth'],
                'nisn'             => $data['nisn'],
                'schoolValidation'=> $data['schoolValidation'] ?? null,
            ]);

            // relasi ke sekolah
            if (!empty($data['schoolDetailId'])) {
                $user->childSchoolDetails()->attach($data['schoolDetailId'], [
                    'childId' => null
                ]);
            }

            return $user->fresh();
        });
    }

    public function storeParent(array $data, int $userId): Child
    {
        return DB::transaction(function () use ($data, $userId) {
            $child = Child::create([
                'userId'           => $userId,
                'name'             => $data['fullname'],
                'nisn'             => $data['nisn'],
                'relation'         => $data['relation'],
                'schoolValidation' => $data['schoolValidation'] ?? null,
                'schoolDetailId'   => $data['schoolDetailId'] ?? null,
            ]);

            return $child;
        });
    }
}
