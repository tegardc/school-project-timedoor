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
            $user = $service->getAll($perPage);
            $userTransform = UserResource::collection($user);
            return ResponseHelper::success([
                'datas' => $userTransform,
                'meta' => [
                    'current_page' => $userTransform->currentPage(),
                    'last_page' => $userTransform->lastPage(),
                    'limit' => $userTransform->perPage(),
                    'total' => $userTransform->total(),
                ],'Display User Success'
            ]);
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
            $user = $request->user();
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
    public function update(UserRequest $request, UserService $service)
    {
        try {
            $user = $request->user();
            // $this->authorize('update', $user);
            $validated = $request->validated();
            $user = $service->updateUser($user, $validated);
            return ResponseHelper::success(
                new UserResource($user),
                'Update Success'
            );
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops update user is failed ", $e, "[USER UPDATE]: ");
        }
    }
    //User Delete Akun Sendiri
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

    //Admin Delete Akun User
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
