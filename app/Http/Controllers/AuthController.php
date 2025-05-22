<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function register(Request $request)
    {
        try {
            $validateData = $request->validate([
                'firstName' => 'required|string',
                'lastName'  => 'required|string',
                'username'   => 'required|string|unique:users,username',
                'gender'     => 'required|in:male,female',
                'phoneNo'   => 'required|string',
                'email'      => 'required|email|unique:users,email',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$!%*?&])[A-Za-z\d@#$!%*?&]+$/'
                ],
                'confirm_password' => 'required|string||same:password',
                'role' => 'required|in:parent,student',
            ]);
            $validateData['password'] = Hash::make($validateData['password']);
            $newUser = User::create($validateData);
            $newUser->assignRole($request->role);
            $hours = (int) 4;
            $plainTextToken = $newUser->createToken($newUser->email, ['*'], now()->addHours($hours))->plainTextToken;

            return response()->json([
                'message' => 'User registered successfully.',
                'data'    => new UserResource($newUser),
                'token'   => $plainTextToken
            ]);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 422);
        } catch (\Exception $e) {
            return $this->errorResponse("Error Createng Data:" . $e->getMessage(), 500);
        }
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $loginField = str_contains($credentials['email'], '@') ? 'email' : 'username';
        if (!Auth::attempt([$loginField =>
        $credentials['email'], 'password' =>
        $credentials['password']])) {
            return $this->errorResponse("Invalid Credential or Account Disable", 400);
        }
        $user = Auth::user();
        $user->tokens()->delete();
        $hours = (int)4;
        $plainTextToken = $user->createToken($user->email, ['*'], now()->addHours($hours))->plainTextToken;
        $expiresAt = now()->addHours($hours)->toDateTimeString();

        return response()->json([
            'message' => 'Login successful.',
            'data' => new UserResource($user),
            'token' => $plainTextToken,
            'expiresAt' => $expiresAt
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.'
        ]);
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
