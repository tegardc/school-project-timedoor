<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\EducationProgramRequest;
use App\Http\Resources\EducationProgramResource;
use App\Models\EducationProgram;
use App\Services\EducationProgramService;
use Illuminate\Http\Request;

class EducationProgramController extends Controller
{
    protected EducationProgramService $service;

    public function __construct(EducationProgramService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->service->getAll();
            return ResponseHelper::success($data, 'Display Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display all program is failed ", $e, "[PROGRAM INDEX]: ");
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
       public function store(EducationProgramRequest $request)
    {
        try {
            $program = $this->service->store($request->validated());
            return ResponseHelper::success($program, 'Store Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops create program is failed ", $e, "[PROGRAM STORE]: ");
        }
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(EducationProgram $educationProgram)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EducationProgram $educationProgram)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(EducationProgramRequest $request, int $id)
    {
        try {
            $program = $this->service->update($id, $request->validated());
            return ResponseHelper::success($program, 'Update Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops update program is failed ", $e, "[PROGRAM UPDATE]: ");
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->service->softDelete($id);
            return ResponseHelper::success(null, 'Delete Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops delete program is failed ", $e, "[PROGRAM DESTROY]: ");
        }
    }

    public function trash(EducationProgramService $service) {
        try {
            $program = $service->trash();
            if($program->isEmpty()) {
                return ResponseHelper::notFound('Programs not found');
            }
            return ResponseHelper::success(EducationProgramResource::collection($program), 'Program trashed items retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display program is failed ", $e, "[PROGRAM TRASH]: ");
        }
    }
    public function restore(EducationProgramService $service, $id) {
        try {
            $program = $service->restore($id);
            if(!$program) {
                return ResponseHelper::notFound('Data Not Found');
            }
            return ResponseHelper::success(new EducationProgramResource($program), 'Program restored successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops restore program is failed ", $e, "[PROGRAM RESTORE]: ");
        }
    }
}
