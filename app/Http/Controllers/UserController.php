<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, UserService $service)
    {
        try {
            $perPage = $request->query('perPage',10);
            $user =$service->getAll($perPage);
            return  ResponseHelper::success(
                UserResource::collection($user),
                'Display Data Success'
            );
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display all user is failed ", $e, "[USER INDEX]: ");
        }
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
        try {
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
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display all user is failed ", $e, "[USER INDEX]: ");
        }
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
        try {
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
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops update user is failed ", $e, "[USER UPDATE]: ");
        }
    }
    public function destroy(UserService $service, Request $request)
    {
        try {
            $user = $request->user();
            $service->softDelete($user->id);
            return ResponseHelper::success('User deleted successfully');
         } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops deleted user is failed ", $e, "[USER DELETED]: ");
        }
    }

    public function deleteUser(UserService $service, $id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return ResponseHelper::notFound('Data Not Found');
            }
            $service->softDelete($id);
            return ResponseHelper::success('User deleted successfully');
         } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops deleted user is failed ", $e, "[USER DELETED]: ");
        }
    }

    public function trash(UserService $service) {
        try {
            $user = $service->trash();
            return ResponseHelper::success(UserResource::collection($user), 'User trashed items retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display user is failed ", $e, "[USER TRASH]: ");
        }
    }

    public function restore(UserService $service, $id) {
        try {
            $user = $service->restore($id);
            return ResponseHelper::success(new UserResource($user), 'User restored successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops restore user is failed ", $e, "[USER RESTORE]: ");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
}
