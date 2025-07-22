<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\SchoolDetailRequest;
use App\Http\Resources\SchoolDetailResource;
use App\Models\SchoolDetail;
use App\Models\SchoolGallery;
use App\Services\SchoolDetailService;
use Database\Seeders\SchoolDetailSeeder;
use Illuminate\Http\Request;
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
            'educationLevelName', 'statusName', 'accreditationCode', 'schoolName'
        ]);
        $perPage = $request->query('perPage',10);
        $schools = $service->filter($filters, $perPage);
        if($schools->isEmpty()) return ResponseHelper::notFound('School Detail Not Found');

        return ResponseHelper::success(
            SchoolDetailResource::collection($schools),
            'Display Data Successfully'
        );
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
    // public function getBySubDistrict($id)
    // {
    //     try {
    //         $schoolDetails = SchoolDetail::whereHas('schools', function ($query) use ($id) {
    //         $query->where('subDistrictId', $id);
    //     })->get();

    //     return ResponseHelper::success($schoolDetails, 'School details by sub-district retrieved');
    //     } catch (\Exception $e) {
    //         return ResponseHelper::serverError("Oops display school detail by sub district is failed", $e, "[SCHOOL DETAIL GETBYSUBDISTRICT]: ");
    //     }

    // }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $schools = SchoolDetail::with(['schools', 'status', 'educationLevel', 'accreditation', 'schoolGallery', 'reviews'])->find($id);
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

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
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
        //
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
    public function filter(Request $request, SchoolDetailService $service)
    {
        try {
            $filters = $request->only([
            'provinceId',
            'districtId',
            'subDistrictId',
            'educationLevelId',
            'statusId',
            'accreditationId',
            'schoolId'
        ]);

        $data = $service->filter($filters);
        if($data->isEmpty()) return ResponseHelper::notFound('School Detail Not Found');
        return ResponseHelper::success(SchoolDetailResource::collection($data), 'Filtered School Details');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops filter school detail is failed ", $e, "[SCHOOL DETAIL FILTER]: ");
        }
    }

    public function ranking()
    {
        try {
            $schools = SchoolDetail::with([
                'schoolGallery', 'reviews'])->withCount(['reviews as total_reviews'])->withAvg('reviews as average_rating', 'rating')->orderByDesc('average_rating')->orderByDesc('total_reviews')->get();

            if ($schools->isEmpty()) {
                return ResponseHelper::notFound('Schools not found');
            }
            return ResponseHelper::success(SchoolDetailResource::collection($schools), 'Ranking By Rating & Reviewers');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display rangking school detail is failed ", $e, "[SCHOOL DETAIL RANKING]: ");
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
    public function getBySubDistrict($id, SchoolDetailService $service)
{
    try {
        $schoolDetails = $service->getBySubDistrict($id);

        if ($schoolDetails->isEmpty()) {
            return ResponseHelper::notFound('School details not found for this sub-district');
        }

        return ResponseHelper::success(SchoolDetailResource::collection($schoolDetails), 'School details by sub-district retrieved');
    } catch (\Exception $e) {
        return ResponseHelper::serverError("Oops display school detail by sub district is failed", $e, "[SCHOOL DETAIL GETBYSUBDISTRICT]: ");
    }
}

}
