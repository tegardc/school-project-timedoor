<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Child;
use App\Services\UserService;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Profiler\Profile;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, UserService $service)
    {
        try {
            $perPage = $request->query('perPage', 10);
            $keyword = $request->query('search');
            $user = $service->getAll($perPage, $keyword);
            $userTransform = UserResource::collection($user);
            return ResponseHelper::success(
                [
                    'datas' => $userTransform,
                    'meta' => [
                        'current_page' => $userTransform->currentPage(),
                        'last_page' => $userTransform->lastPage(),
                        'limit' => $userTransform->perPage(),
                        'total' => $userTransform->total(),
                    ],
                ],
                $keyword
                    ? "Display search results for '{$keyword}' successfully"
                    : "Display user success"
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

    public function show(Request $request, UserService $service)
    {
        try {
            $user = $service->showUser();
            return ResponseHelper::success(
                new UserResource($user),
                'Show Data Success'
            );
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display all user is failed ", $e, "[USER INDEX]: ");
        }
    }
    public function search(Request $request, UserService $userService)
    {
        $keyword = $request->query('search');
        $perPage = $request->query('perPage', 10);

        $users = $userService->searchUsers($keyword, $perPage);

        return ResponseHelper::success($users, 'Search Success');
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

    public function update(UpdateProfileRequest $request, UserService $profileService)
    {
        try {
            $user = Auth::user();
            $data = $request->validated();

            // handle password update
            if ($request->filled('new_password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return ResponseHelper::error('Password saat ini salah', 422);
                }

                $data['password'] = Hash::make($request->new_password);
            }

            $updatedUser = $profileService->updateProfile($user, $data);

            return ResponseHelper::success(new UserResource($updatedUser), 'Profil berhasil diperbarui');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops update profile failed", $e, "[PROFILE UPDATE]: ");
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

    public function trash(UserService $service)
    {
        try {
            $user = $service->trash();
            return ResponseHelper::success(UserResource::collection($user), 'User trashed items retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display user is failed ", $e, "[USER TRASH]: ");
        }
    }

    public function restore(UserService $service, $id)
    {
        try {
            $user = $service->restore($id);
            return ResponseHelper::success(new UserResource($user), 'User restored successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops restore user is failed ", $e, "[USER RESTORE]: ");
        }
    }
    // public function profileStore(ProfileRequest $request, UserService $service)
    // {
    //     try {
    //         $user = Auth::user();

    //         if ($user->hasRole('student')) {
    //             $profile = $service->updateStudent($request->validated(), $user->id);
    //         } elseif ($user->hasRole('parent')) {
    //             $profile = $service->updateParent($request->validated(), $user->id);
    //         } else {
    //             return ResponseHelper::error("Role tidak valid untuk melengkapi data diri.");
    //         }

    //         return ResponseHelper::success($profile, 'Data diri berhasil disimpan');
    //     } catch (\Exception $th) {
    //         return ResponseHelper::serverError("Oops store profile is failed ", $th, "[PROFILE STORE]: ");
    //     }
    // }
    public function profileStore(ProfileRequest $request, UserService $service)
    {
        try {
            $user = Auth::user();
            $data = $request->validated();

            if ($user->hasRole('student')) {
                $profile = $service->completeStudentProfile($data, $user->id);
            } elseif ($user->hasRole('parent')) {
                $profile = $service->completeParentProfile($data, $user->id);
            } else {
                return ResponseHelper::error('Role tidak valid.');
            }

            return ResponseHelper::success(new ProfileResource($profile->fresh()), 'Profil berhasil disimpan.');
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Gagal menyimpan profil.', $e, '[PROFILE STORE]');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
}
