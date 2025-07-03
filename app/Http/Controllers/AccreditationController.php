<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\AccreditationResource;
use App\Models\Accreditation;
use App\Services\AccreditationService;
use Illuminate\Http\Request;

class AccreditationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AccreditationService $service)
    {
        try {
            $accreditation = $service->getAll();
            return ResponseHelper::success(AccreditationResource::collection($accreditation), 'Display Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display all accreditation is failed ", $e, "[ACREDITATION INDEX]: ");
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
    public function show(AccreditationService $service, $id)
    {
        try {
            $accreditation = $service->getById($id);
            if (!$accreditation) {
                return ResponseHelper::notFound('Data Not Found');
            }
            return ResponseHelper::success(new AccreditationResource($accreditation), 'Show Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display accreditation is failed ", $e, "[ACREDITATION SHOW]: ");
            //throw $th;
        }
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Accreditation $accreditation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Accreditation $accreditation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Accreditation $accreditation)
    {
        //
    }
}
