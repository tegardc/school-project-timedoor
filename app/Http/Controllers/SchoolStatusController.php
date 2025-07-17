<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\SchoolStatusResource;
use App\Models\SchoolStatus;
use App\Services\SchoolStatusService;
use Illuminate\Http\Request;

class SchoolStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SchoolStatusService $service)
    {
        try {
            //code...
            $schoolStatus = $service->getAll();
            if($schoolStatus->isEmpty()) return ResponseHelper::notFound('Data Not Found');
            return ResponseHelper::success(SchoolStatusResource::collection($schoolStatus), 'Display Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display all school status is failed ", $e, "[SCHOOL STATUS INDEX]: ");

        }
        //
    }

    /**
     * Show the form for creating a new resource.
     */

    /**
     * Display the specified resource.
     */
    public function show(SchoolStatusService $service,$id)
    {
        try {
            //code...
            $schoolStatus = $service->getById($id);
            if (!$schoolStatus) {
                return ResponseHelper::notFound('Data Not Found');
            }
            return ResponseHelper::success(new SchoolStatusResource($schoolStatus), 'Show Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display school status by id is failed ", $e, "[SCHOOL STATUS SHOW]: ");
        }

        //
    }

}
