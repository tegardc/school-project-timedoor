<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\SubDistrictRequest;
use App\Http\Resources\SubDistrictResource;
use App\Models\SubDistrict;
use App\Services\SubDistrictService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubdistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subdistrict = SubDistrict::all();
        return ResponseHelper::success(SubDistrictResource::collection($subdistrict), 'Successfully Display Data');
        //
    }
    // SubDistrictController.php
    public function getByDistrict($districtId)
    {
        $subDistricts = SubDistrict::where('districtId', $districtId)->get();
        return ResponseHelper::success(SubDistrictResource::collection($subDistricts), 'Sub-districts retrieved');
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
    public function store(SubDistrictRequest $request, SubDistrictService $service)
    {
        try {
            $validated = $request->validated();
            $subDistrict = $service->store($validated);
            DB::commit();
            return ResponseHelper::created(new SubDistrictResource($subDistrict), 'Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::error($e->getMessage());
        }



        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Subdistrict $subdistrict)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subdistrict $subdistrict)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubDistrictRequest $request, SubDistrictService $service, $id)
    {
        try {
            $validated = $request->validated();
            $subDistrict = $service->update($validated, $id);
            if (!$subDistrict) {
                return ResponseHelper::notFound('Data Not Found');
            }
            return ResponseHelper::success(new SubDistrictResource($subDistrict), 'Sub District Update Success');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }

        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $subDistrict = Subdistrict::find($id);
        if (!$subDistrict) {
            return ResponseHelper::notFound('Sub District not found');
        }

        $subDistrict->delete();

        return ResponseHelper::success(null, 'Sub District deleted successfully');
        //
    }
}
