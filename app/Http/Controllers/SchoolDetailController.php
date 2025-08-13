<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\SchoolDetailRequest;
use App\Http\Resources\SchoolDetailResource;
use App\Models\Review;
use App\Models\SchoolDetail;
use App\Models\SchoolGallery;
use App\Services\SchoolDetailService;
use Database\Seeders\SchoolDetailSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class SchoolDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, SchoolDetailService $service)
{
    try {
        $filters = $request->only([
            'provinceName', 'districtName', 'subDistrictName',
            'educationLevelName', 'statusName', 'accreditationCode', 'search', 'sortBy', 'sortDirection'
        ]);
        $perPage = $request->query('perPage',12);

        $cacheKey = 'school_details_' . md5(json_encode($filters) . "_$perPage");

        $schools = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($service, $filters, $perPage) {
            return $service->filter($filters, $perPage);
        });
        $schools = $service->filter($filters, $perPage);
        if($schools->isEmpty()){
            return ResponseHelper::notFound('School Detail Not Found');
        }

        $schoolDetailsTransform = SchoolDetailResource::collection($schools);
        return ResponseHelper::success(
                [ 'datas' => $schoolDetailsTransform,
                'meta' => [
                    'current_page' => $schoolDetailsTransform->currentPage(),
                    'last_page' => $schoolDetailsTransform->lastPage(),
                    'limit' => $schoolDetailsTransform->perPage(),
                    'total' => $schoolDetailsTransform->total(),
                ]
            ], 'Display Data Success');
    } catch (\Exception $e) {
        return ResponseHelper::serverError("Oops displayed school details list is failed", $e, "[SCHOOL DETAIL INDEX]: ");
    }
}



    /**
     * Store a newly created resource in storage.
     */
    public function store(SchoolDetailRequest $request, SchoolDetailService $service)
    {
        try {
            $validated = $request->validated();
            $schoolDetail = $service->store($validated);
            return ResponseHelper::created(new SchoolDetailResource($schoolDetail), 'Created Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops created school detail is failed", $e, "[SCHOOL DETAIL STORE]: ");
        }
        //
    }


    public function show($id)
    {
        try {
            $schools = SchoolDetail::with(['schools', 'status', 'educationLevel', 'accreditation', 'schoolGallery', 'reviews','facilities','contacts'])->find($id);
            if (!$schools) {
                return ResponseHelper::notFound('Data Not Found');
            }
            $totalReviews = $schools->reviews->count();
            $averageRating = round($schools->reviews->avg('rating'), 1);
            return ResponseHelper::success(new SchoolDetailResource($schools), 'Show Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display school detail is failed ", $e, "[SCHOOL DETAIL SHOW]: ");
        }
        //
    }


    // SchoolDetailController.php
    public function update(SchoolDetailRequest $request, $id, SchoolDetailService $service)
    {
        try {
            $validated = $request->validated();
            $school = $service->update($validated, $id);
            if (!$school) {
                return ResponseHelper::notFound('Data Not Found');
            }
            return ResponseHelper::success(
                new SchoolDetailResource($school),
                'Update Data Success'
            );
        } catch (\Exception $e) {
             return ResponseHelper::serverError("Oops update school detail is failed", $e, "[SCHOOL DETAIL UPDATE]: ");
        }
    }

    public function destroy(SchoolDetailService $service, $id)
    {
        try {
            $schools = SchoolDetail::find($id);
            if (!$schools) {
                return ResponseHelper::notFound('Data Not Found');
            }
            $service->softDelete($id);
            return ResponseHelper::success(null,'School Detail moved to trash successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops deleted school detail is failed ", $e, "[SCHOOL DETAIL DESTROY]: ");
        }
    }

    public function trash(SchoolDetailService $service) {
        try {
            $schools = $service->trash();
            if($schools->isEmpty()) {
                return ResponseHelper::notFound('Schools not found');
            }
            return ResponseHelper::success(SchoolDetailResource::collection($schools), 'School detail trashed items retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display school detail is failed ", $e, "[SCHOOL DETAIL TRASH]: ");
        }
    }

    public function restore(SchoolDetailService $service, $id) {
        try {
            $schools = $service->restore($id);
            if (!$schools) {
                return ResponseHelper::notFound('Data Not Found');
            }
            return ResponseHelper::success(new SchoolDetailResource($schools), 'School detail restored successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops restore school detail is failed ", $e, "[SCHOOL DETAIL RESTORE]: ");
        }
    }

    //Ranking School with filter
     public function ranking(Request $request, SchoolDetailService $service)
{
    try {
        $filters = $request->only([
            'provinceName',
            'districtName',
            'subDistrictName',
            'educationLevelName',
            'statusName',
            'accreditationCode'
        ]);

        $schools = $service->ranking($filters);
        if ($schools->isEmpty()) {
            return ResponseHelper::notFound('No ranking data found.');
        }
        return ResponseHelper::success(SchoolDetailResource::collection($schools), 'Ranking By Rating & Reviewers');
    } catch (\Exception $e) {
        return ResponseHelper::serverError("Oops display ranking school detail failed", $e, "[SCHOOL DETAIL RANKING]: ");
    }
}
    public function getSchoolDetailBySchoolId(SchoolDetailService $service, $schoolId)
    {
        try {
            $schools = $service->getSchoolDetailBySchoolId($schoolId);
            if($schools->isEmpty()) return ResponseHelper::notFound('School Detail Not Found');
            return ResponseHelper::success(SchoolDetailResource::collection($schools)->values(), 'School details by school retrieved');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display school detail by school is failed", $e, "[SCHOOL DETAIL GETBYSCHOOL]: ");
        }
    }
    public function updateFeatured(Request $request, SchoolDetailService $service)
{
    $validated = $request->validate([
        'featured_ids' => 'required|array|max:4',
        'featured_ids.*' => 'integer|exists:school_details,id',
    ]);

    try {
        $result = $service->setFeaturedSchools($validated['featured_ids']);
        return ResponseHelper::success($result, 'Featured schools updated successfully');
    } catch (\Exception $e) {
        return ResponseHelper::error($e->getMessage(), 400);
    }
}
public function getFeaturedSchools(SchoolDetailService $service)
{
    try {
        $featuredSchools = $service->getFeaturedSchools();
        return ResponseHelper::success($featuredSchools, 'Featured schools retrieved successfully');
    } catch (\Exception $e) {
        return ResponseHelper::error($e->getMessage(), 400);
    }
}



}


