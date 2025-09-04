<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Http\Resources\UserResource;
use App\Models\Child;
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
 public function updateProfile(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {

            // update user basic info
            $user->fill([
                'firstName' => $data['firstName'] ?? $user->firstName,
                'lastName'  => $data['lastName'] ?? $user->lastName,
                'username'  => $data['username'] ?? $user->username,
                'email'     => $data['email'] ?? $user->email,
                'phoneNo'   => $data['phoneNo'] ?? $user->phoneNo,
                'gender'    => $data['gender'] ?? $user->gender,
                'image'     => $data['image'] ?? $user->image,
            ]);

            // ganti password kalau diminta
            if (!empty($data['new_password'])) {
                if (!Hash::check($data['current_password'], $user->password)) {
                    throw new \Exception('Current password is incorrect.');
                }
                $user->password = Hash::make($data['new_password']);
            }

            $user->save();

            // kalau student, update NIS dan sekolah
            if ($user->hasRole('student')) {
                if (!empty($data['nis']) || !empty($data['schoolDetailId'])) {
                    // misalnya untuk student kita simpan di tabel childs juga
                    $child = $user->childs()->first();
                    if (!$child) {
                        $child = new Child(['userId' => $user->id]);
                    }
                    if (!empty($data['nis'])) {
                        $child->nis = $data['nis'];
                    }
                    if (!empty($data['schoolDetailId'])) {
                        $child->schoolDetailId = $data['schoolDetailId'];
                    }
                    $child->name = $user->firstName . ' ' . $user->lastName;
                    $child->save();
                }
            }

            // kalau parent, update data anak
            if ($user->hasRole('parent')) {
                if (!empty($data['nis']) || !empty($data['childName'])) {
                    $child = $user->childs()->first();
                    if (!$child) {
                        $child = new Child(['userId' => $user->id]);
                    }
                    if (!empty($data['childName'])) {
                        $child->name = $data['childName'];
                    }
                    if (!empty($data['nis'])) {
                        $child->nis = $data['nis'];
                    }
                    if (!empty($data['schoolDetailId'])) {
                        $child->schoolDetailId = $data['schoolDetailId'];
                    }
                    $child->save();
                }
            }

            return $user;
        });
    }


}
