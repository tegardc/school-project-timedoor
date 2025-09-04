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
            // Buat user baru
            $user = User::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            if(isset($data['role'])) {
                $user->assignRole($data['role']);
            }

            return $user;
        });
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $loginField = str_contains($credentials['email'], '@') ? 'email' : 'username';
        if (!Auth::attempt([$loginField => $credentials['email'], 'password' => $credentials['password']])) {
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
