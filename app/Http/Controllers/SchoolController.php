<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\SchoolResource;
use App\Http\Resources\SchoolResourceCollection;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SchoolController extends Controller
{
    public function index()
    {
        try {
            $schools = School::with(['province', 'district', 'subDistrict', 'schoolGallery'])->get();
            return ResponseHelper::success(SchoolResource::collection($schools), 'Display Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'provinceId' => 'required|exists:provinces,id',
                'districtId' => 'required|exists:districts,id',
                'subDistrictId' => 'required|exists:sub_districts,id',
                'schoolEstablishmentDecree' => 'nullable|string|max:255'
            ]);

            $school = School::create($validated);

            return ResponseHelper::success(
                new SchoolResource($school),
                'Created Data Success'
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
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
            return ResponseHelper::error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $school = School::find($id);
            if (!$school) {
                return ResponseHelper::notFound('Data Not Found');
            }
            $validated = $request->validated();
            $school->update($validated);
            return ResponseHelper::success(new SchoolResource($school), 'Update Success');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $school = School::find($id);
            if (!$school) {
                return ResponseHelper::notFound('Data Not Found');
            }
            $school->delete();
            return ResponseHelper::success('deleted successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }
}
