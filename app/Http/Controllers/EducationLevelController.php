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
    public function showByName(EducationLevelService $service, $name)
    {
        try {
            $educationLevel = $service->getByName($name);
            if (!$educationLevel) {
                return ResponseHelper::notFound('Data Not Found');
            }
            return ResponseHelper::success(new EducationLevelResource($educationLevel), 'Show Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display education level by name is failed ", $e, "[EDUCATION LEVEL SHOW]: ");
        }
    }

}
