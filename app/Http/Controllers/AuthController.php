<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Child;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    public function register(UserRequest $request)
    {
        try {
            $this->authService->register($request->validated());
            return ResponseHelper::success([], 'User registered success');
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
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to login user', $e, '[LOGIN]');
        }
    }



    public function logout(Request $request, AuthService $service)
    {
        try {
            $service->logout($request);
            return ResponseHelper::success([],'Logged out successfully.');
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to logout user', $e, '[LOGOUT]');
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
