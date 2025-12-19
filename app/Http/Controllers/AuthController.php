<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\UpdateForgotPaswordRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\VerificationToken;
use App\Services\AuthService;
use App\Services\TokenService;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    protected $authService;
    protected $tokenService;

    public function __construct(AuthService $authService, TokenService $tokenService)
    {
        $this->authService = $authService;
        $this->tokenService = $tokenService;
    }

    public function register(UserRequest $request)
    {
        try {
            $this->authService->register($request->validated());
            return ResponseHelper::success(null, 'User registered success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to register user', $e, '[REGISTER]');
        }
    }

    public function login(Request $request)
    {
        try {
            $result = $this->authService->login($request);
            return ResponseHelper::success([
                'token' => $result['token'],
                'expiresAt' => $result['expiresAt']
            ], 'Login Successfully');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to login user', $e, '[LOGIN]');
        }
    }

    public function logout(Request $request, AuthService $service)
    {
        try {
            $service->logout($request);
            return ResponseHelper::success(null, 'Logged out successfully.');
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to logout user', $e, '[LOGOUT]');
        }
    }

    // FORGOT PASSWORD
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $payload = $request->validated();
            $this->authService->forgotPassword($payload['email']);

            return ResponseHelper::success(null, 'Forgot password process initiated. Please check your email.');
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to process forgot password', $e, '[FORGOT_PASSWORD]');
        }
    }

    public function updateForgotPasswordUser(UpdateForgotPaswordRequest $request)
    {
        try {
            $payload = $request->validated();

            DB::beginTransaction();

            $result = $this->tokenService->validateToken($payload['token'], VerificationToken::TYPE_PASSWORD_RESET);

            if (!$result) {
                DB::rollBack();
                return ResponseHelper::badRequest('Token sudah tidak valid atau telah digunakan.', null, '[INVALID_TOKEN]');
            }

            User::where('id', $result->user_id)
                ->update([
                    'password' => bcrypt($payload['password'])
                ]);

            $this->tokenService->markAsUsed($result);

            DB::commit();

            return ResponseHelper::success(null, 'Password berhasil direset.');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError('Failed to reset password', $e, '[RESET_PASSWORD]');
        }
    }
}
