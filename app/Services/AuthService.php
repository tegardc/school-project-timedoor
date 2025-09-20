<?php

namespace App\Services;

use App\Models\Child;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    // public function register(array $data): User
    // {
    //     return DB::transaction(function () use ($data) {
    //         $user = User::create([
    //             'firstName' => $data['firstName'],
    //             'lastName' => $data['lastName'],
    //             'username' => $data['username'],
    //             // 'gender' => $data['gender'],
    //             'phoneNo' => $data['phoneNo'],
    //             'email' => $data['email'],
    //             'password' => Hash::make($data['password']),
    //         ]);

    //         $user->assignRole($data['role']);
    //         // Jika student
    //         if ($data['role'] === 'student') {
    //             $user->update([
    //                 'nis' => $data['nis'],
    //             ]);
    //             if (!empty($data['schoolDetailId'])) {
    //                  $user->childSchoolDetails()->attach($data['schoolDetailId'], [
    //                      'childId' => null,
    //             ]);
    //         }
    //             // $user->childSchoolDetails()->attach($data['schoolDetailId'], [
    //             //     'childId' => null,
    //             // ]);
    //         }

    //         // Jika parent
    //         if ($data['role'] === 'parent') {
    //             $child = Child::create([
    //                 'userId' => $user->id,
    //                 'name' => $data['childName'],
    //                 'nis' => $data['nis'],
    //             ]);

    //             // $user->childSchoolDetails()->attach($data['schoolDetailId'], [
    //             //     'childId' => $child->id,
    //             // ]);
    //             if (!empty($data['schoolDetailId'])) {
    //                  $user->childSchoolDetails()->attach($data['schoolDetailId'], [
    //                   'childId' => $child->id,
    //                  ]);
    //             }
    //         }

    //         return $user;
    //     });
    // }
    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {
            if ($data['role'] === 'student') {
                // Student disimpan di tabel users
                $user = User::create([
                    'fullname'          => $data['fullname'],
                    'email'             => $data['email'],
                    'gender'            => $data['gender'] ?? null,
                    'dateOfBirth'       => $data['dateOfBirth'] ?? null,
                    'phoneNo'           => $data['phoneNo'] ?? null,
                    'address'           => $data['address'] ?? null,
                    'nisn'              => $data['nisn'] ?? null,
                    'studentValidation' => $data['studentValidation'] ?? null,
                    'password'          => Hash::make($data['password']),
                ]);

                if (!empty($data['schoolDetailId'])) {
                    $user->childSchoolDetails()->attach($data['schoolDetailId'], [
                        'childId' => null
                    ]);
                }
                return $user;
            }

            if ($data['role'] === 'parent') {
                // Parent tetap disimpan di tabel users untuk login
                $user = User::create([
                    'fullname'    => $data['fullname'],
                    'email'       => $data['email'],
                    'phoneNo'     => $data['phoneNo'] ?? null,
                    'address'     => $data['address'] ?? null,
                    'password'    => Hash::make($data['password']),
                ]);

                // Simpan child
                $child = Child::create([
                    'userId'           => $user->id,
                    'schoolDetailId'   => $data['schoolDetailId'] ?? null,
                    'nisn'             => $data['nisn'] ?? null,
                    'name'             => $data['childName'],
                    'relation'         => $data['relation'] ?? 'Orang Tua',
                    'schoolValidation' => $data['schoolValidation'] ?? null,
                ]);

                if (!empty($data['schoolDetailId'])) {
                    $user->childSchoolDetails()->attach($data['schoolDetailId'], [
                        'childId' => $child->id
                    ]);
                }

                return $user;
            }

            throw new \Exception("Role tidak valid. Harus 'student' atau 'parent'.");
        });
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'login' => ['Invalid credential or account disabled.'],
            ]);
        }
        $user = Auth::user();
        $user->tokens()->delete();

        $hours = 4;
        $plainTextToken = $user->createToken($user->email, ['*'], now()->addHours($hours))->plainTextToken;
        return [
            'token' => $plainTextToken,
            'expiresAt' => now()->addHours($hours)->toDateTimeString(),
        ];
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }
}
