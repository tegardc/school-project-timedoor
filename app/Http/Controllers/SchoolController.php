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
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'required|exists:districts,id',
            'sub_district_id' => 'required|exists:sub_districts,id',
            'operational_license' => 'nullable|string|max:255',
            'telp_no' => 'nullable|string|max:20',
            'exam_info' => 'nullable|string',
        ]);

        $school = School::create([
            'name' => $validated['name'],
            'province_id' => $validated['province_id'],
            'district_id' => $validated['district_id'],
            'sub_district_id' => $validated['sub_district_id'],
            'operational_license' => $validated['operational_license'],
            'exam_info' => $validated['exam_info'],
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
            'province_id' => 'sometimes|exists:provinces,id',
            'district_id' => 'sometimes|exists:districts,id',
            'sub_district_id' => 'sometimes|exists:sub_districts,id',
            'operational_license' => 'nullable|string|max:255',
            'exam_info' => 'nullable|string',
        ]);

        $school->update([
            'name' => $validated['name'] ?? $school->name,
            'province_id' => $validated['province_id'] ?? $school->province_id,
            'district_id' => $validated['district_id'] ?? $school->district_id,
            'sub_district_id' => $validated['sub_district_id'] ?? $school->sub_district_id,
            'operational_license' => $validated['operational_license'] ?? $school->operational_license,
            'exam_info' => $validated['exam_info'] ?? $school->exam_info,
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
