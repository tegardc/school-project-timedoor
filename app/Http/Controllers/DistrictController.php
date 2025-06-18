<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\DistrictRequest;
use App\Http\Resources\DistrictResource;
use App\Models\District;
use App\Services\DistrictService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $district = District::all();
        return ResponseHelper::success(DistrictResource::collection($district), 'Successfully Display Data');

        //
    }
    public function getByProvince($provinceId)
    {
        $districts = District::where('provinceId', $provinceId)->get();
        return ResponseHelper::success(DistrictResource::collection($districts), 'Districts retrieved');
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
    public function store(DistrictRequest $request, DistrictService $service)
    {
        try {
            $validated = $request->validated();
            $district = $service->store($validated);
            DB::commit();
            return ResponseHelper::created(new DistrictResource($district), 'Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::error($e->getMessage());
        }

        //
    }

    /**
     * Display the specified resource.
     */
    public function show(District $district)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(District $district)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DistrictRequest $request, DistrictService $service, $id)
    {
        try {
            $validated = $request->validated();
            $district = $service->update($validated, $id);
            if (!$district) {
                return ResponseHelper::notFound('Data Not Found');
            }
            return ResponseHelper::success(new DistrictResource($district), 'District Update Success');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $district = District::find($id);
        if (!$district) {
            return ResponseHelper::notFound('District not found');
        }

        $district->delete();

        return ResponseHelper::success(null, 'District deleted successfully');
    }
}
