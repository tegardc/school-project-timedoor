<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        return  ResponseHelper::success(
            UserResource::collection($user),
            'Display Data Success'
        );
        //
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

    public function show(Request $request)
    {
        $user = $request->user()->load([
            'roles',
            'childSchoolDetails.schools.province',
            'childSchoolDetails.schools.district',
            'childSchoolDetails.schools.subDistrict',
        ]);
        return ResponseHelper::success(
            new UserResource($user),
            'Show Data Success'
        );
    }
    //


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
    public function update(UserRequest $request)
    {
        $user = $request->user();
        $this->authorize('update', $user);
        $validated = $request->validated();
        if (!empty($validated['current_password']) && !empty($validated['new_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return $this->errorResponse("Correct Password Is Incorrect", 400);
            }
            $user->password = Hash::make($validated['new_password']);
        }

        $user->update($validated);
        $user->refresh();
        return ResponseHelper::success(
            new UserResource($user),
            'Update Success'
        );
    }
    public function destroy(Request $request)
    {
        $user = $request->user();
        $user->delete();
        return ResponseHelper::success('User deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
}
