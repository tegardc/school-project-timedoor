<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Http\Resources\UserResource;
use App\Models\Child;
use App\Models\EducationExperience;
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
    public function getAll($perPage = null, ?string $keyword = null)
    {
        $query = User::select([
            'id',
            'fullname',
            'email',
            'phoneNo',
            'nisn',
            'gender',
            'image'
        ]);
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('fullname', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('phoneNo', 'like', "%{$keyword}%")
                    ->orWhere('nisn', 'like', "%{$keyword}%");
            });
        }
        return $query->paginate($perPage ?? 10);
    }

    // public function showUser(): User
    // {
    //     $user = Auth::user();

    //     $user->load([
    //         'educationExperiences.educationLevel',
    //         'educationExperiences.schoolDetail',
    //         'educationExperiences.educationProgram',
    //     ]);

    //     return $user;
    // }
    public function showUser(): User
    {
        $user = Auth::user();

        $user->load([
            'roles',
            'child.schoolDetail',
            'educationExperiences.schoolDetail',
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
                'status'    => $data['status']
            ]);

            if (!empty($data['schoolDetailId'])) {
                $user->childSchoolDetails()->sync([
                    $data['schoolDetailId'] => [
                        'childId' => null,
                        'createdAt' => now(),
                        'updatedAt' => now()
                    ]
                ]);
                $alreadyExists = EducationExperience::where('userId', $userId)
                    ->where('schoolDetailId', $data['schoolDetailId'])
                    ->exists();

                if (!$alreadyExists) {
                    EducationExperience::create([
                        'userId'          => $userId,
                        'schoolDetailId'  => $data['schoolDetailId'],
                        'status'          => $data['status'],
                        'document'        => $data['schoolValidation'] ?? null,
                    ]);
                }
            }


            return $user->fresh([
                'childSchoolDetails',
                'educationExperiences.schoolDetail',
            ]);
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
                    'schoolValidation' => $data['schoolValidation'] ?? null,
                    'schoolDetailId'  => $data['schoolDetailId'] ?? null,
                    'dateOfBirth'     => $data['dateOfBirth'] ?? $child->dateOfBirth,
                ]);
            } else {
                $child = Child::create([
                    'userId'          => $userId,
                    'fullname'        => $data['fullname'],
                    'nisn'            => $data['nisn'],
                    'relation'        => $data['relation'],
                    'schoolValidation' => $data['schoolValidation'] ?? null,
                    'schoolDetailId'  => $data['schoolDetailId'] ?? null,
                    'dateOfBirth'     => $data['dateOfBirth'],
                ]);
            }
            if (!empty($data['schoolDetailId'])) {
                $alreadyExists = EducationExperience::where('userId', $userId)
                    ->where('schoolDetailId', $data['schoolDetailId'])
                    ->exists();

                if (!$alreadyExists) {
                    EducationExperience::create([
                        'userId'         => $userId,
                        'schoolDetailId' => $data['schoolDetailId'],
                        'status'         => 'aktif',
                        'document'       => $data['schoolValidation'] ?? null,
                        'createdAt'      => now(),
                        'updatedAt'      => now(),
                    ]);
                }
            }

            return $child->fresh(['schoolDetail']);
        });
    }

    // public function updateProfile(User $user, array $data): User
    // {
    //     // update user (umum)
    //     $user->update([
    //         'fullname'    => $data['fullname'] ?? $user->fullname,
    //         'email'       => $data['email'] ?? $user->email,
    //         'phoneNo'     => $data['phoneNo'] ?? $user->phoneNo,
    //         'address'     => $data['address'] ?? $user->address,
    //         'image'       => $data['image'] ?? $user->image,
    //         'nisn'        => $data['nisn'] ?? $user->nisn,
    //         'dateOfBirth' => $data['dateOfBirth'] ?? $user->dateOfBirth,
    //     ]);
    //     if (!empty($data['password'])) {
    //         $user->update([
    //             'password' => $data['password']
    //         ]);
    //     }
    //     // kalau role parent & ada child data
    //     if ($user->hasRole('parent') && isset($data['child'])) {
    //         $childData = $data['child'];

    //         $child = $user->child()->updateOrCreate(
    //             ['userId' => $user->id], // kondisi berdasarkan parent
    //             [
    //                 'fullname'         => $childData['fullname'] ?? null,
    //                 'dateOfBirth'      => $childData['dateOfBirth'] ?? null,
    //                 'nisn'             => $childData['nisn'] ?? null,
    //                 'email'            => $childData['email'] ?? null,
    //                 'phoneNo'          => $childData['phoneNo'] ?? null,
    //                 'schoolDetailId'   => $childData['schoolDetailId'] ?? null,
    //                 'schoolValidation' => $childData['schoolValidation'] ?? null,
    //             ]
    //         );
    //     }
    //     return $user->load([
    //         'child.schoolDetail:id,name',
    //         'roles',
    //     ]);
    // }
    public function updateProfile(User $user, array $data): User
    {
        DB::transaction(function () use ($user, $data) {

            // Update user utama
            $updateData = [
                'fullname'    => $data['fullname'] ?? $user->fullname,
                'email'       => $data['email'] ?? $user->email,
                'phoneNo'     => $data['phoneNo'] ?? $user->phoneNo,
                'address'     => $data['address'] ?? $user->address,
                'image'       => $data['image'] ?? $user->image,
                'nisn'        => $data['nisn'] ?? $user->nisn,
                'dateOfBirth' => $data['dateOfBirth'] ?? $user->dateOfBirth,
            ];

            if (!empty($data['password'])) {
                $updateData['password'] = $data['password'];
            }

            $user->update($updateData);

            // Jika parent dan ada data anak
            if ($user->hasRole('parent') && isset($data['child'])) {
                $childData = $data['child'];

                $user->child()->updateOrCreate(
                    ['userId' => $user->id],
                    [
                        'fullname'    => $childData['fullname'] ?? null,
                        'dateOfBirth' => $childData['dateOfBirth'] ?? null,
                        'nisn'        => $childData['nisn'] ?? null,
                        'email'       => $childData['email'] ?? null,
                        'phoneNo'     => $childData['phoneNo'] ?? null,
                        'schoolDetailId' => $childData['schoolDetailId'] ?? $user->child->schoolDetailId ?? null,
                        'schoolValidation' => $childData['schoolValidation'] ?? null,
                        'status'      => $childData['status'] ?? 'aktif',
                    ]
                );
            }
        });

        // reload data terbaru dengan relasi
        return $user->fresh([
            'child',
            'child.schoolDetail:id,name',
            'roles',
        ]);
    }

    public function searchUsers(?string $keyword = null, $perPage = null)
    {
        $query = User::select([
            'id',
            'fullname',
            'email',
            'phoneNo',
            'nisn',
            'gender',
            'image',
        ]);

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('fullname', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('phoneNo', 'like', "%{$keyword}%")
                    ->orWhere('nisn', 'like', "%{$keyword}%");
            });
        }

        return $query->paginate($perPage ?? 10);
    }


    /**
     * Lengkapi profil untuk ROLE STUDENT
     */
    public function completeStudentProfile(array $data, int $userId): User
    {
        return DB::transaction(function () use ($data, $userId) {
            $user = User::findOrFail($userId);

            // Update data utama student
            $user->update([
                'fullname'         => $data['fullname'],
                'dateOfBirth'      => $data['dateOfBirth'] ?? null,
                'nisn'             => $data['nisn'] ?? null,
                'status'           => 'aktif',
                'schoolValidation' => $data['schoolValidation'] ?? null,
            ]);

            // Jika sekolah dikirim, buat atau update EducationExperience
            if (!empty($data['schoolDetailId'])) {
                $alreadyExists = EducationExperience::where('userId', $userId)
                    ->where('schoolDetailId', $data['schoolDetailId'])
                    ->exists();

                if (!$alreadyExists) {
                    EducationExperience::create([
                        'userId'          => $userId,
                        'schoolDetailId'  => $data['schoolDetailId'],
                        'status'          => 'aktif',
                        'document'        => $data['schoolValidation'] ?? null,
                    ]);
                }
            }

            return $user->fresh(['educationExperiences.schoolDetail']);
        });
    }

    /**
     * Lengkapi profil untuk ROLE PARENT
     */
    public function completeParentProfile(array $data, int $userId): Child
    {
        return DB::transaction(function () use ($data, $userId) {
            // Validasi field wajib
            $required = ['fullname', 'dateOfBirth', 'nisn', 'relation', 'schoolDetailId'];
            foreach ($required as $key) {
                if (!isset($data[$key])) {
                    throw new \Exception("Field {$key} wajib diisi.");
                }
            }

            // Buat atau update anak berdasarkan parent (userId)
            $child = Child::updateOrCreate(
                ['userId' => $userId],
                [
                    'fullname'         => $data['fullname'],
                    'dateOfBirth'      => $data['dateOfBirth'],
                    'nisn'             => $data['nisn'],
                    'relation'         => $data['relation'],
                    'schoolDetailId'   => $data['schoolDetailId'],
                    'schoolValidation' => $data['schoolValidation'] ?? null,
                ]
            );

            // Buat record EducationExperience jika belum ada
            if (!empty($data['schoolDetailId'])) {
                $alreadyExists = EducationExperience::where('userId', $userId)
                    ->where('schoolDetailId', $data['schoolDetailId'])
                    ->exists();

                if (!$alreadyExists) {
                    EducationExperience::create([
                        'userId'          => $userId,
                        'schoolDetailId'  => $data['schoolDetailId'],
                        'document'        => $data['schoolValidation'] ?? null,
                    ]);
                }
            }

            return Child::with(['schoolDetail', 'parent'])
                ->where('id', $child->id)
                ->first();
        });
    }

    /**
     * Review profile user sesuai role-nya
     */
    public function reviewProfile(): array
    {
        $user = Auth::user()->load([
            'roles',
            'child.schoolDetail',
            'educationExperiences.schoolDetail',
        ]);

        if ($user->hasRole('parent')) {
            $child = $user->child;
            return [
                'parentFullname'      => $user->fullname,
                'parentEmail'         => $user->email,
                'relationWithChild'   => $child->relation ?? '-',
                'childFullname'       => $child->fullname ?? '-',
                'childStatus'         => $child->status ?? 'siswa aktif',
                'schoolName'          => $child->schoolDetail->name ?? '-',
            ];
        }

        if ($user->hasRole('student')) {
            return [
                'fullname'     => $user->fullname,
                'email'        => $user->email,
                'nisn'         => $user->nisn,
                'status'       => $user->status ?? 'siswa aktif',
                'schoolName'   => optional($user->educationExperiences->last()->schoolDetail)->name ?? '-',
            ];
        }

        return [];
    }
}
