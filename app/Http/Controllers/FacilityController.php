<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\FacilityRequest;
use App\Http\Resources\FacilityResource;
use App\Models\Facility;
use App\Services\FacilityService;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
      public function index(Request $request, FacilityService $service)
    {
        try {
            $facilities = $service->getAll();
            if ($facilities->isEmpty()) return ResponseHelper::notFound('Facility Not Found');
            return ResponseHelper::success(FacilityResource::collection($facilities), 'Display Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display all facilities failed", $e, "[FACILITY INDEX]: ");
        }
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
    public function store(FacilityRequest $request, FacilityService $service)
    {
        try{
            $validated = $request->validated();
            $facility = $service->store($validated);
            return ResponseHelper::created(new FacilityResource($facility), 'Created Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops create facility is failed ", $e, "[FACILITY STORE]: ");
        }
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            $facility = Facility::find($id);
            if (!$facility) return ResponseHelper::notFound('Facility Not Found');
            return ResponseHelper::success(new FacilityResource($facility), 'Display Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display facility by id is failed ", $e, "[FACILITY SHOW]: ");
        }
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facility $facility)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FacilityRequest $request, FacilityService $service, $id)
    {
        try{
            $validated = $request->validated();
            $facility = $service->update($validated, $id);
            if (!$facility) return ResponseHelper::notFound('Facility Not Found');
            return ResponseHelper::success(new FacilityResource($facility), 'Update Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops update facility is failed ", $e, "[FACILITY UPDATE]: ");
        }
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FacilityService $service, $id)
    {
        try{
            $facility = Facility::find($id);
            if (!$facility) return ResponseHelper::notFound('Facility Not Found');
            $service->softDelete($id);
            return ResponseHelper::success(null, 'Facility moved to trash successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops delete facility is failed ", $e, "[FACILITY DESTROY]: ");
        }
        //
    }
    public function trash(FacilityService $service) {
        try {
            $facility = $service->trash();
            if($facility->isEmpty()) {
                return ResponseHelper::notFound('Facilities not found');
            }
            return ResponseHelper::success(FacilityResource::collection($facility), 'Facility trashed items retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display facility is failed ", $e, "[FACILITY TRASH]: ");
        }
    }
    public function restore(FacilityService $service,$id)
    {
        try {
            $facility = $service->restore($id);
            if(!$facility) {
                return ResponseHelper::notFound('Data Not Found');
            }
            return ResponseHelper::success(new FacilityResource($facility), 'Facility Restored Successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops restore facility is failed ", $e, "[FACILITY RESTORE]: ");
        }
    }
}
