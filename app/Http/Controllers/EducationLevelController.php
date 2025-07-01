<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\EducationLevelResource;
use App\Models\EducationLevel;
use App\Services\EducationLevelService;
use Illuminate\Http\Request;

class EducationLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EducationLevelService $service)
    {
        try {
            $educationLevels = $service->getAll();
            return ResponseHelper::success(EducationLevelResource::collection($educationLevels), 'Display Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display all education level is failed ", $e, "[EDUCATION LEVEL INDEX]: ");
            //throw $th;
        }

        //
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(EducationLevelService $service, $id)
    {
        try {
            $educationLevel = $service->getById($id);
            if (!$educationLevel) {
                return ResponseHelper::notFound('Data Not Found');
            }
            return ResponseHelper::success(new EducationLevelResource($educationLevel), 'Show Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display education level by id is failed ", $e, "[EDUCATION LEVEL SHOW]: ");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EducationLevel $educationLevel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EducationLevel $educationLevel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EducationLevel $educationLevel)
    {
        //
    }
}
