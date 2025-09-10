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
//      public function updateUser(User $user, array $validated)
// {
//     // === handle password ===
//     if (!empty($validated['current_password']) && !empty($validated['new_password'])) {
//         if (!Hash::check($validated['current_password'], $user->password)) {
//             return ResponseHelper::error("Current password is incorrect", 400);
//         }
//         $user->password = Hash::make($validated['new_password']);
//     }

//     // === update data user biasa ===
//     $dataUser = collect($validated)->except(['schoolDetailId', 'nis', 'childName'])->toArray();
//     $user->update($dataUser);

//     // Kalau parent, buat child baru + attach pivot
//    // Kalau parent
// if (!empty($validated['schoolDetailId'])) {
//     $user->childSchoolDetails()->syncWithoutDetaching([$validated['schoolDetailId']]);
// }



//     $user->refresh();
//     return $user;
// }
 public function updateProfile(array $data): void
{
    $user = Auth::user(); // user yang login
    $role = $user->getRoleNames()->first(); // pakai Spatie roles

    DB::transaction(function () use ($user, $role, $data) {
        // 1. Update data dasar user
        $user->update([
            'firstName' => $data['firstName'] ?? $user->firstName,
            'lastName'  => $data['lastName'] ?? $user->lastName,
            'username'  => $data['username'] ?? $user->username,
            'email'     => $data['email'] ?? $user->email,
            'gender'    => $data['gender'] ?? $user->gender,
            'phoneNo'   => $data['phoneNo'] ?? $user->phoneNo,
            'image'     => $data['image'] ?? $user->image
        ]);

      if ($role === 'student') {
            if (isset($data['nis'])) {
                $user->update(['nis' => $data['nis']]);
            }

            if (isset($data['schoolDetailId'])) {
                $user->childSchoolDetails()->sync([
                    $data['schoolDetailId'] => [
                        'childId' => null,
                    ],
                ]);
            }
        }

        // parent
        if ($role === 'parent') {
            if (isset($data['childName']) && isset($data['nis'])) {
                $child = Child::updateOrCreate(
                    ['nis' => $data['nis']],
                    [
                        'name'   => $data['childName'],
                        'userId' => $user->id,
                    ]
                );

                if (isset($data['schoolDetailId'])) {
                    $user->childSchoolDetails()->sync([
                        $data['schoolDetailId'] => [
                            'childId' => $child->id,
                        ],
                    ]);
                }
            }
        }
    });

    $user->refresh()->load('roles', 'childSchoolDetails');

}
}
