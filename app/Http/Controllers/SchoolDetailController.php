<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\SchoolDetailRequest;
use App\Http\Resources\SchoolDetailResource;
use App\Models\school_detail;
use App\Models\SchoolGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SchoolDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $schools = school_detail::with(['schools', 'status', 'education_level', 'accreditation', 'schoolGallery'])->get();

            return ResponseHelper::success(SchoolDetailResource::collection($schools), 'Display Data Successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
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
    public function store(SchoolDetailRequest $request)
    {
        try {
            $validated = $request->validated();
            $schoolDetail = school_detail::create($validated);
            foreach ($validated['imageUrl'] as $imageUrl) {
                SchoolGallery::create(['schoolDetailId' => $schoolDetail->id, 'schoolId' => $schoolDetail->schoolId, 'imageUrl' => $imageUrl,]);
            }
            $schoolDetail->load(['schoolGallery']);
            return ResponseHelper::created(new SchoolDetailResource($schoolDetail), 'Created Success');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $schools = school_detail::with(['schools', 'status', 'education_level', 'accreditation', 'schoolGallery', 'reviews'])->find($id);
            if (!$schools) {
                return ResponseHelper::notFound('Data Not Found');
            }
            $totalReviews = $schools->reviews->count();
            $averageRating = round($schools->reviews->avg('rating'), 1);
            return ResponseHelper::success(new SchoolDetailResource($schools), 'Show Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(school_detail $school_detail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SchoolDetailRequest $request, $id)
    {
        try {
            $schools = school_detail::find($id);
            if (!$schools) {
                return ResponseHelper::notFound('Data Not Found');
            }
            $validated = $request->validated();
            $schools->update($validated);
            if (!empty($validated['imageUrl'])) {
                SchoolGallery::where('schoolDetailId', $schools->id)->delete();

                foreach ($validated['imageUrl'] as $index => $imageUrl) {
                    SchoolGallery::create([
                        'schoolId' => $schools->schoolId,
                        'schoolDetailId' => $schools->id,
                        'imageUrl' => $imageUrl,
                        'isCover' => $index === 0 ? 1 : 0
                    ]);
                }
            }
            DB::commit();
            $schools->load(['schoolGallery']);
            return ResponseHelper::success(
                new SchoolDetailResource($schools),
                'Update Data Success'
            );
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
        try {
            $schools = school_detail::find($id);
            if (!$schools) {
                return ResponseHelper::notFound('Data Not Found');
            }
            $schools->delete();
            return ResponseHelper::success('Deleted Successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
        //
    }
    public function ranking()
    {
        try {
            $schools = school_detail::with(['schoolGallery', 'reviews'])->withCount(['reviews as total_reviews'])->withAvg('reviews as average_rating', 'rating')->orderByDesc('average_rating')->orderByDesc('total_reviews')->get();

            return ResponseHelper::success(SchoolDetailResource::collection($schools), 'Ranking By Rating & Reviewers');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }
}
