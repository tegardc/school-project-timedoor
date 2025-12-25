<?php

namespace App\Services;

use App\Jobs\ForgotPasswordJob;
use App\Jobs\VerificationEmailJob;
use App\Models\Child;
use App\Models\User;
use App\Models\VerificationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    protected $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

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
        DB::beginTransaction();

        explode(' ', trim($data['fullname']));

        $user = User::create([
            'fullname' => $data['fullname'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (!empty($data['role'])) {
            $user->assignRole($data['role']);
        }

        $this->sendVerificationAccount($user);

        DB::commit();

        return $user;
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $currentUser = User::where('email', $credentials['email'])->first();

        if ($currentUser && !$currentUser->emailVerifiedAt) {
            throw ValidationException::withMessages([
                'login' => ['Please verify your email before logging in.'],
            ]);
        }

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

    public function forgotPassword(string $email)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new \Exception('Akun dengan email tersebut tidak ditemukan.');
        }

        $baseUrl = config('app.frontend_url');

        $resultToken = $this->tokenService->createToken($user, VerificationToken::TYPE_PASSWORD_RESET, 60);
        $createFrontendUrl = $baseUrl . '/reset-password?token=' . $resultToken . '&email=' . urlencode($email);

        ForgotPasswordJob::dispatch($user->email, $createFrontendUrl);

        return true;
    }

    public function sendVerificationAccount(User $user)
    {
        $baseUrl = config('app.frontend_url');

        $resultToken = $this->tokenService->createToken($user, VerificationToken::TYPE_EMAIL_VERIFICATION, 1440);
        $createFrontendUrl = $baseUrl . '/verify-account?token=' . $resultToken . '&email=' . urlencode($user->email);

        VerificationEmailJob::dispatch($user->email, $createFrontendUrl, $user->fullname);

        return true;
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }
}
