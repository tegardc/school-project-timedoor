<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\SubDistrictRequest;
use App\Http\Resources\SubDistrictResource;
use App\Models\SubDistrict;
use App\Services\SubDistrictService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SubdistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, SubDistrictService $service)
    {
        try {
            $districtName = $request->query('districtName');
            if (!$districtName) {
                return ResponseHelper::notFound('Data Not Found');
            }
            $cacheKey = 'subdistrict_' . md5($districtName);
            $subdistrict = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($service, $districtName) {
                return $service->getByDistrict($districtName);
            });
            // $subdistrict = $service->getByDistrict($districtName);
            if($subdistrict->isEmpty()) return ResponseHelper::notFound('Data Not Found');
            return ResponseHelper::success(SubDistrictResource::collection($subdistrict), 'Successfully Display Data');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display subdistrict is failed ", $e, "[SUBDISTRICT INDEX]: ");
        }
        //
    }
    // SubDistrictController.php
    public function getByDistrict($districtId)
    {
        try {
            $subDistricts = SubDistrict::where('districtId', $districtId)->get();
            if ($subDistricts->isEmpty()) {
                return ResponseHelper::notFound('Sub-districts not found');
            }
            return ResponseHelper::success(SubDistrictResource::collection($subDistricts), 'Sub-districts retrieved');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display subdistrict by district id is failed ", $e, "[SUBDISTRICT GETBYDISTRICT]: ");
        }
    }

    public function store(SubDistrictRequest $request, SubDistrictService $service)
    {
        try {
            $validated = $request->validated();
            $subDistrict = $service->store($validated);
            DB::commit();
            Cache::forget('subdistrict');
            return ResponseHelper::created(new SubDistrictResource($subDistrict), 'Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError("Oops created subdistrict is failed ", $e, "[SUBDISTRICT STORE]: ");
        }

    }

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
            return ResponseHelper::serverError("Oops updated subdistrict is failed ", $e, "[SUBDISTRICT UPDATE]: ");
        }
    }

    public function destroy(SubDistrictService $service, $id)
    {
        try {
            $subDistrict = SubDistrict::find($id);
            if (!$subDistrict) {
                return ResponseHelper::notFound('Sub District not found');
            }
            $service->softDelete($id);
            return ResponseHelper::success(null, 'Sub District moved to trash successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops deleted subdistrict is failed ", $e, "[SUBDISTRICT DESTROY]: ");
        }
        //
    }
    public function trash(SubDistrictService $service) {
        try {
            $subdistrict = $service->trash();
            if($subdistrict->isEmpty()) {
                return ResponseHelper::notFound('Sub Districts not found');
            }
            return ResponseHelper::success(SubDistrictResource::collection($subdistrict), 'Sub District trashed items retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display subdistrict is failed ", $e, "[SUBDISTRICT TRASH]: ");
        }
    }
    public function restore(SubDistrictService $service, $id) {
        try {
            $subdistrict = $service->restore($id);
            if (!$subdistrict) {
                return ResponseHelper::notFound('Data Not Found');
            }
            return ResponseHelper::success(new SubDistrictResource($subdistrict), 'Sub District restored successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops restore subdistrict is failed ", $e, "[SUBDISTRICT RESTORE]: ");
        }
    }
}
