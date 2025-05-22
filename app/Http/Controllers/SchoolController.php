<?php

namespace App\Http\Controllers;

use App\Http\Resources\SchoolResource;
use App\Http\Resources\SchoolResourceCollection;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::with(['province', 'district', 'subDistrict'])->get();

        return new SchoolResourceCollection($schools);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'provinceId' => 'required|exists:provinces,id',
            'districtId' => 'required|exists:districts,id',
            'subDistrictId' => 'required|exists:sub_districts,id',
            'schoolEstablishmentDecree' => 'nullable|string|max:255'
        ]);

        $school = School::create([
            'name' => $validated['name'],
            'provinceId' => $validated['provinceId'],
            'districtId' => $validated['districtId'],
            'subDistrictId' => $validated['subDistrictId'],
            'schoolEstablishmentDecree' => $validated['schoolEstablishmentDecree'] ?? null,

        ]);

        return (new SchoolResource($school))->additional([
            'message' => 'Add Data Success'
        ]);
    }

    public function show($id)
    {
        $school = School::with(['province', 'district', 'subDistrict'])->findOrFail($id);
        return (new SchoolResource($school))->additional([
            'message' => 'Show Data Success'
        ]);
    }

    public function update(Request $request, $id)
    {
        $school = School::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'provinceId' => 'sometimes|exists:provinces,id',
            'districtId' => 'sometimes|exists:districts,id',
            'subDistrictId' => 'sometimes|exists:sub_districts,id',
            'schoolEstablishmentDecree' => 'nullable|string|max:255'
        ]);

        $school->update([
            'name' => $validated['name'] ?? $school->name,
            'provinceId' => $validated['provinceId'] ?? $school->provinceId,
            'districtId' => $validated['districtId'] ?? $school->districtId,
            'subDistrictId' => $validated['subDistrictId'] ?? $school->subDistrictId,
            'schoolEstablishmentDecree' => $validated['schoolEstablishmentDecree'] ?? $school->schoolEstablishmentDecree
        ]);

        return response()->json([
            'message' => 'School updated successfully',
            'data' => $school
        ]);
    }

    public function destroy($id)
    {
        $school = School::findOrFail($id);
        $school->delete();
        return response()->json([
            'message' => 'School deleted successfully'
        ]);
    }
}
