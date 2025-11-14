<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\SaveSchoolRequest;
use App\Http\Requests\SchoolDetailRequest;
use App\Http\Resources\SchoolDetailResource;
use App\Http\Resources\SchoolTemplateResource;
use App\Models\Review;
use App\Models\SaveSchool;
use App\Models\School;
use App\Models\SchoolDetail;
use App\Models\SchoolGallery;
use App\Services\SchoolDetailService;
use Database\Seeders\SchoolDetailSeeder;
use GuzzleHttp\Psr7\Response;
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
                'provinceName',
                'districtName',
                'subDistrictName',
                'educationLevelName',
                'statusName',
                'accreditationCode',
                'search',
                'sortBy',
                'sortDirection'
            ]);
            $perPage = $request->query('perPage', 12);

            // $cacheKey = 'school_details_' . md5(json_encode($filters) . "_$perPage");

            // $schools = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($service, $filters, $perPage) {
            //     return $service->filter($filters, $perPage);
            // });
            $schools = $service->filter($filters, $perPage);
            if ($schools->isEmpty()) {
                return ResponseHelper::success([], 'Data Not Found');
            }

            $schoolDetailsTransform = SchoolTemplateResource::collection($schools);
            return ResponseHelper::success(
                [
                    'datas' => $schoolDetailsTransform,
                    'meta' => [
                        'current_page' => $schoolDetailsTransform->currentPage(),
                        'last_page' => $schoolDetailsTransform->lastPage(),
                        'limit' => $schoolDetailsTransform->perPage(),
                        'total' => $schoolDetailsTransform->total(),
                    ]
                ],
                'Display Data Success'
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
            return ResponseHelper::created(null, 'Created Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops created school detail is failed", $e, "[SCHOOL DETAIL STORE]: ");
        }
        //
    }


    public function show($id)
    {
        try {
            $schools = SchoolDetail::with(['schools', 'status', 'educationLevel', 'accreditation', 'schoolGallery', 'reviews', 'facilities', 'contacts'])->find($id);
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
                null,
                'Update Data Success'
            );
            // return ResponseHelper::success(
            //     new SchoolDetailResource($school),
            //     'Update Data Success'
            // );
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
            return ResponseHelper::success(null, 'School Detail moved to trash successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops deleted school detail is failed ", $e, "[SCHOOL DETAIL DESTROY]: ");
        }
    }

    public function trash(SchoolDetailService $service)
    {
        try {
            $schools = $service->trash();
            if ($schools->isEmpty()) {
                return ResponseHelper::notFound('Schools not found');
            }
            return ResponseHelper::success(SchoolDetailResource::collection($schools), 'School detail trashed items retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display school detail is failed ", $e, "[SCHOOL DETAIL TRASH]: ");
        }
    }

    public function restore(SchoolDetailService $service, $id)
    {
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
            if ($schools->isEmpty()) return ResponseHelper::notFound('School Detail Not Found');
            return ResponseHelper::success(SchoolDetailResource::collection($schools)->values(), 'School details by school retrieved');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display school detail by school is failed", $e, "[SCHOOL DETAIL GETBYSCHOOL]: ");
        }
    }
    public function updateHighlight(Request $request, SchoolDetailService $service)
    {
        $validated = $request->validate([
            'highlight_id' => 'required|Integer|exists:school_details,id',
        ]);

        try {
            $result = $service->setHighlightedSchools($validated['highlight_id']);
            return ResponseHelper::success($result, 'Highlighted schools updated successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops update highlighted school detail is failed", $e, "[SCHOOL DETAIL HIGHLIGHT]: ");
        }
    }
    public function highlight(SchoolDetailService $service)
    {
        $data = $service->getHighlightedSchools();

        if ($data->isEmpty()) {
            return ResponseHelper::error('Data Not Found');
        }
        return ResponseHelper::success(
            SchoolDetailResource::collection($data),
            'Highlighted schools retrieved successfully'
        );
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
            return ResponseHelper::serverError("Oops update featured school detail is failed", $e, "[SCHOOL DETAIL FEATURED]: ");
        }
    }
    public function featured(SchoolDetailService $service)
    {
        $data = $service->getFeaturedSchools();

        if ($data->isEmpty()) {
            return ResponseHelper::error('Data Not Found');
        }
        return ResponseHelper::success(
            SchoolTemplateResource::collection($data),
            'Featured schools retrieved successfully'
        );
    }

    public function saveSchool(SaveSchoolRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = $request->user();
            $schoolDetailId = $validated['schoolDetailId'];

            $saveSchool = SaveSchool::where([
                'userId' => $user->id,
                'schoolDetailId' => $schoolDetailId
            ])->first();
            if ($saveSchool) {
                $saveSchool->delete();
                return ResponseHelper::success('Unsaved School detail successfully');
            }
            SaveSchool::create([
                'userId' => $user->id,
                'schoolDetailId' => $schoolDetailId
            ]);
            $schoolDetail = SchoolDetail::find($schoolDetailId);
            return ResponseHelper::success(new SchoolTemplateResource($schoolDetail), "School detail saved successfully");
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops save school detail is failed ", $e, "[SCHOOL DETAIL SAVE]: ");
        }
    }
    public function showSaved(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return ResponseHelper::error('Unauthorized', 401);
            }
            $savedSchools = SaveSchool::with('schoolDetail')->where('userId', $user->id)->get()->map(function ($save) {
                return $save->schoolDetail;
            });
            return ResponseHelper::success(SchoolTemplateResource::collection($savedSchools), 'Saved schools retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display saved school detail is failed ", $e, "[SCHOOL DETAIL SAVED]: ");
            //throw $th;
        }
    }
    public function topSchools(SchoolDetailService $service)
    {
        $schools = $service->getTopSchools(5);
        return SchoolTemplateResource::collection($schools);
    }
    public function recommendedSchools(Request $request, SchoolDetailService $service)
    {
        $criteria = $request->only(['provinceId', 'districtId', 'educationLevelId']);
        $schools = $service->getRecommendedSchools($criteria, 5);
        return SchoolTemplateResource::collection($schools);
    }
    public function searchByName(Request $request, SchoolDetailService $schoolDetailService)
    {
        $request->validate([
            'keyword' => 'required|string|min:2'
        ]);

        $schools = $schoolDetailService->searchSchoolByName($request->keyword, 3);

        return ResponseHelper::success($schools, 'Pencarian sekolah berhasil');
    }
    public function setRecommendation(Request $request, SchoolDetailService $schoolService)
    {
        $validated = $request->validate([
            'recommendationIds' => 'required|array',
            'recommendationIds.*' => 'integer|exists:school_details,id',
        ]);

        try {
            $schools = $schoolService->setRecommendedSchools($validated['recommendationIds']);
            return response()->json([
                'message' => 'Sekolah rekomendasi berhasil diperbarui.',
                'data' => $schools
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function getRecommendation(SchoolDetailService $schoolService)
    {
        $schools = $schoolService->getRecommendedSchools();
        return response()->json([
            'message' => 'Daftar sekolah rekomendasi.',
            'data' => SchoolTemplateResource::collection($schools)
        ]);
    }

}
