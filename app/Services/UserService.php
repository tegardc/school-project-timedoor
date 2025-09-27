<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Http\Resources\UserResource;
use App\Models\Child;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    public function __construct()
    {
        $this->modelClass = User::class;
    }
    public function getAll($perPage = null)
    {
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
        return $query->paginate($perPage ?? 10);
    }

    public function showUser(): User
    {
        $user = Auth::user();

        $user->load([
            'educationExperiences.educationLevel',
            'educationExperiences.schoolDetail',
            'educationExperiences.educationProgram',
        ]);

        return $user;
    }
    public function updateStudent(array $data, int $userId): User
    {
        return DB::transaction(function () use ($data, $userId) {
            $user = User::findOrFail($userId);

            $user->update([
                'fullname'         => $data['fullname'],
                'dateOfBirth'      => $data['dateOfBirth'],
                'nisn'             => $data['nisn'],
                'schoolValidation' => $data['schoolValidation'] ?? null,
            ]);

            if (!empty($data['schoolDetailId'])) {
                $user->childSchoolDetails()->sync([
                    $data['schoolDetailId'] => ['childId' => null, 'createdAt' => now(), 'updatedAt' => now()]
                ]);
            }

            return $user->fresh();
        });
    }



    public function updateParent(array $data, int $userId): Child
{
    return DB::transaction(function () use ($data, $userId) {
        $child = Child::where('userId', $userId)->first();

        if ($child) {
            $child->update([
                'fullname'        => $data['fullname'],
                'nisn'            => $data['nisn'],
                'relation'        => $data['relation'],
                'schoolValidation'=> $data['schoolValidation'] ?? null,
                'schoolDetailId'  => $data['schoolDetailId'] ?? null,
                'dateOfBirth'     => $data['dateOfBirth'] ?? $child->dateOfBirth,
            ]);
        } else {
            $child = Child::create([
                'userId'          => $userId,
                'fullname'        => $data['fullname'],
                'nisn'            => $data['nisn'],
                'relation'        => $data['relation'],
                'schoolValidation'=> $data['schoolValidation'] ?? null,
                'schoolDetailId'  => $data['schoolDetailId'] ?? null,
                'dateOfBirth'     => $data['dateOfBirth'],
            ]);
        }

        return $child;
    });
}

   public function updateProfile(User $user, array $data): User
{
    // update user (umum)
    $user->update([
        'fullname'    => $data['fullname'] ?? $user->fullname,
        'email'       => $data['email'] ?? $user->email,
        'phoneNo'     => $data['phoneNo'] ?? $user->phoneNo,
        'address'     => $data['address'] ?? $user->address,
        'image'       => $data['image'] ?? $user->image,
        'nisn'        => $data['nisn'] ?? $user->nisn,
        'dateOfBirth' => $data['dateOfBirth'] ?? $user->dateOfBirth,
    ]);
      if (!empty($data['password'])) {
        $user->update([
            'password' => Hash::make($data['password']),
        ]);
    }


    // kalau role parent & ada child data
    if ($user->hasRole('parent') && isset($data['child'])) {
        $childData = $data['child'];

        $child = $user->child()->updateOrCreate(
            ['userId' => $user->id], // kondisi berdasarkan parent
            [
                'fullname'         => $childData['fullname'] ?? null,
                'dateOfBirth'      => $childData['dateOfBirth'] ?? null,
                'nisn'             => $childData['nisn'] ?? null,
                'email'            => $childData['email'] ?? null,
                'phoneNo'          => $childData['phoneNo'] ?? null,
                'schoolDetailId'   => $childData['schoolDetailId'] ?? null,
                'schoolValidation' => $childData['schoolValidation'] ?? null,
            ]
        );
    }

    // load relasi
    return $user->load([
        'child.schoolDetail:id,name',
        'roles',
    ]);
}

}
