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
    public function index()
    {
        $province = Province::all();
        return ResponseHelper::success(ProvinceResource::collection($province), 'Success Display List Province');
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
            return ResponseHelper::error($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $province = Province::find($id);

        if (!$province) {
            return ResponseHelper::notFound('Province Not Found');
        }

        return ResponseHelper::success($province, 'Province Found');
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
            return ResponseHelper::error($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $province = Province::find($id);

        if (!$province) {
            return ResponseHelper::notFound('Province Not Found');
        }

        $province->delete();
        return ResponseHelper::success(null, 'Province Deleted Successfully');
    }
}
