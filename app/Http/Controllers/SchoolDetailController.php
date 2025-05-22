<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchoolDetailRequest;
use App\Http\Resources\SchoolDetailResource;
use App\Models\school_detail;
use Illuminate\Http\Request;

class SchoolDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schools = school_detail::with(['schools', 'status', 'education_level', 'accreditation'])->get();

        return response()->json([
            "message" => "Display Success",
            "data" => SchoolDetailResource::collection($schools)
        ]);
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
    public function store(SchoolDetailRequest $request)
    {
        $this->authorize('create', school_detail::class);
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $validated = $request->validated();
        $schoolDetail = school_detail::create($validated);
        return response()->json([
            "message" => "Add Data Success",
            "data" => new SchoolDetailResource($schoolDetail)
        ]);
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $schools = school_detail::with(['schools', 'status', 'education_level', 'accreditation'])->findOrFail($id);

        return response()->json([
            "message" => "Show Data Success",
            "data" => new SchoolDetailResource($schools)
        ]);
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(school_detail $school_detail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SchoolDetailRequest $request, $id)
    {
        $schools = school_detail::findOrFail($id);
        $validated = $request->validated();
        $schools->update($validated);

        return response()->json([
            "message" => "Update Data Success",
            "data" => new SchoolDetailResource($schools)
        ]);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $schools = school_detail::findOrFail($id);
        $schools->delete();

        return response()->json([
            "message" => "Deleleted Data Success",
            "data" => []
        ]);
        //
    }
}
