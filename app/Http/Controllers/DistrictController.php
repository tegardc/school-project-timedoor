<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\DistrictRequest;
use App\Http\Resources\DistrictResource;
use App\Models\District;
use App\Services\DistrictService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DistrictController extends Controller
{
    public function index(Request $request, DistrictService $service)
    {
        try {
            $provinceName = $request->query('provinceName');
            if (!$provinceName) {
            return ResponseHelper::notFound('Province name is required');
}
            $cacheKey = 'district_' . md5($provinceName);
            $district = Cache::remember($cacheKey, now()->addMinutes(60), function () use ($service, $provinceName) {
                return $service->getByProvince($provinceName);
            });
            // $district = $service->getByProvince($provinceName);
             if ($district->isEmpty()) {
            return ResponseHelper::notFound('Districts not found for the given province name');
        }
            return ResponseHelper::success(DistrictResource::collection($district), 'Successfully Display Data');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display district is failed ", $e, "[DISTRICT INDEX]: ");
        }

        //
    }
    public function getByProvince($provinceId)
    {
        try {
            //code...
            $districts = District::where('provinceId', $provinceId)->get();
            if ($districts->isEmpty()) {
                return ResponseHelper::notFound('Districts not found for the given province id');
            }
            return ResponseHelper::success(DistrictResource::collection($districts), 'Districts retrieved');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display district by province id is failed ", $e, "[DISTRICT GETBYPROVINCE]: ");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(DistrictRequest $request, DistrictService $service)
    {
        try {
            $validated = $request->validated();
            $district = $service->store($validated);
            DB::commit();
            Cache::flush();
            return ResponseHelper::created(new DistrictResource($district), 'Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError("Oops create district is failed ", $e, "[DISTRICT STORE]: ");
        }

        //
    }

    public function show(District $district)
    {
        //
    }

    public function edit(District $district)
    {
        //
    }

    public function update(DistrictRequest $request, DistrictService $service, $id)
    {
        try {
            $validated = $request->validated();
            $district = $service->update($validated, $id);
            if (!$district) {
                return ResponseHelper::notFound('Data Not Found');
            }
            Cache::flush();
            return ResponseHelper::success(new DistrictResource($district), 'District Update Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops update district is failed ", $e, "[DISTRICT UPDATE]: ");
        }
    }

    public function destroy(DistrictService $service, $id)
    {
        try {
            $district = District::find($id);
            if (!$district) {
                return ResponseHelper::notFound('District not found');
            }

            $service->softDelete($id);

            return ResponseHelper::success(null, 'District deleted successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops delete district is failed ", $e, "[DISTRICT DESTROY]: ");
        }
    }
    public function trash(DistrictService $service) {
        try {
            $district = $service->trash();
            if($district->isEmpty()) {
                return ResponseHelper::notFound('Districts not found');
            }
            return ResponseHelper::success(DistrictResource::collection($district), 'District trashed items retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display district is failed ", $e, "[DISTRICT TRASH]: ");
        }
    }
    public function restore(DistrictService $service, $id) {
        try {
            $district = $service->restore($id);
            if (!$district) {
                return ResponseHelper::notFound('Data Not Found');
            }
            return ResponseHelper::success(new DistrictResource($district), 'District restored successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops restore district is failed ", $e, "[DISTRICT RESTORE]: ");
        }
    }
}
