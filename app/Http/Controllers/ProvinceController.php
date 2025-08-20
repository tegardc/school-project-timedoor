<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ProvinceRequest;
use App\Http\Resources\ProvinceResource;
use App\Models\Province;
use App\Services\ProvinceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ProvinceService $service)
    {
        try {
            $perPage = $request->query('perPage',10);
            $province = $service->getAll($perPage);
            if($province->isEmpty()){
                return ResponseHelper::notFound('Province Not Found');
            }
            return ResponseHelper::success(ProvinceResource::collection($province), 'Success Display List Province');
            } catch (\Exception $e) {
            dd($e->getMessage());
            return ResponseHelper::serverError("Oops display all facilities failed", $e, "[FACILITY INDEX]: ");
}

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProvinceRequest $request, ProvinceService $service)
    {
        try {
            $validated = $request->validated();
            $province = $service->store($validated);

            return ResponseHelper::created(new ProvinceResource($province), 'Province Created Successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops create province is failed ", $e, "[PROVINCE STORE]: ");
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            //code...
            $province = Province::find($id);
            if (!$province) {
                return ResponseHelper::notFound('Province Not Found');
            }
            return ResponseHelper::success($province, 'Province Found');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display province by id is failed ", $e, "[PROVINCE SHOW]: ");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProvinceRequest $request, ProvinceService $service, $id)
    {
        try {
            $validated = $request->validated();
            $province = $service->update($validated, $id);
            if (!$province) {
                return ResponseHelper::notFound('Data Not Found');
            }

            return ResponseHelper::success(new ProvinceResource($province), 'Province Updated Successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops update province is failed ", $e, "[PROVINCE UPDATE]: ");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProvinceService $service, $id)
    {
        try {
            //code...
            $province = Province::find($id);
            if (!$province) {
                return ResponseHelper::notFound('Province Not Found');
            }
            $service->softDelete($id);
            return ResponseHelper::success(null, 'Province moved to trash successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops delete province is failed ", $e, "[PROVINCE DESTROY]: ");
        }
    }
    public function trash(ProvinceService $service) {
        try {
            $province = $service->trash();
            if($province->isEmpty()) {
                return ResponseHelper::notFound('Provinces not found');
            }
            return ResponseHelper::success(ProvinceResource::collection($province), 'Province trashed items retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display province is failed ", $e, "[PROVINCE TRASH]: ");
        }
    }
    public function restore(ProvinceService $service, $id) {
        try {
            $province = $service->restore($id);
            if(!$province) {
                return ResponseHelper::notFound('Data Not Found');
            }
            return ResponseHelper::success(new ProvinceResource($province), 'Province Restored Successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops restore province is failed ", $e, "[PROVINCE RESTORE]: ");
        }
    }
}
