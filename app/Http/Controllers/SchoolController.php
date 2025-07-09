<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\SchoolRequest;
use App\Http\Resources\SchoolResource;
use App\Http\Resources\SchoolResourceCollection;
use App\Models\School;
use App\Services\SchoolService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Psr\Http\Message\RequestInterface;

class SchoolController extends Controller
{
    public function index(Request $request, SchoolService $service)
    {
        try {
            $perPage = $request->query('perPage',10);

            $schools = $service->getAll($perPage);
            return ResponseHelper::success(SchoolResource::collection($schools), 'Display Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display all school is failed ", $e, "[SCHOOL INDEX]: ");
        }
    }
    // public function index(Request $request, SchoolService $service)
    // {
    //     try {

    //         $schools = School::with(['province', 'district', 'subDistrict', 'schoolGallery'])->get();
    //         return ResponseHelper::success(SchoolResource::collection($schools), 'Display Data Success');
    //     } catch (\Exception $e) {
    //         return ResponseHelper::serverError("Oops display all school is failed ", $e, "[SCHOOL INDEX]: ");
    //     }
    // }

    public function store(SchoolRequest $request, SchoolService $service)
    {
        try {
            $validated = $request->validated();
            $school = $service->store($validated);
            return ResponseHelper::success(
                new SchoolResource($school),
                'Created Data Success'
            );
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops created school is failed ", $e, "[SCHOOL STORE]: ");
        }
    }

    public function show($id)
    {
        try {
            $school = School::with(['province', 'district', 'subDistrict'])->findOrFail($id);
            return response()->json([
                'message' => 'Show Data Success',
                'data' => new SchoolResource($school)
            ]);
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display school is failed ", $e, "[SCHOOL SHOW]: ");
        }
    }

    public function update(SchoolRequest $request, $id, SchoolService $service)
    {
        try {
            $validated = $request->validated();

            $school = $service->update($validated, $id);
            if(!$school){
                return ResponseHelper::notFound('Data Not Found');
            }
            return ResponseHelper::success(new SchoolResource($school), 'Update Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops updated school is failed ", $e, "[SCHOOL UPDATE]: ");
        }
    }

    public function destroy(SchoolService $service, $id)
    {
        try {
            $school = School::find($id);
            if (!$school) {
                return ResponseHelper::notFound('Data Not Found');
            }
            $service->softDelete($id);
            return ResponseHelper::success([],'deleted successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops deleted school is failed ", $e, "[SCHOOL DELETED]: ");
        }
    }
    public function trash(SchoolService $service) {
        try {
            $school = $service->trash();
            return ResponseHelper::success(SchoolResource::collection($school), 'School trashed items retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display school is failed ", $e, "[SCHOOL TRASH]: ");
        }
    }

    public function restore(SchoolService $service, $id) {
        try {
            $school = $service->restore($id);
            return ResponseHelper::success(new SchoolResource($school), 'School restored successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops restore school is failed ", $e, "[SCHOOL RESTORE]: ");
        }
    }
}
