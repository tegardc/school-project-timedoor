<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\EducationExperienceRequest;
use App\Http\Resources\EducationExperienceResource;
use App\Models\EducationExperience;
use App\Services\EducationExperienceService;
use Illuminate\Http\Request;

class EducationExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected EducationExperienceService $service;

    public function __construct(EducationExperienceService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $experiences = $this->service->index();
        return ResponseHelper::success(EducationExperienceResource::collection($experiences),  'Display Data Successfully');
    }

    public function store(EducationExperienceRequest $request)
    {
        try {
            $experience = $this->service->store($request->validated());
            return ResponseHelper::success(new EducationExperienceResource($experience), 'Insert Data Success');

        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops create experience is failed ", $e, "[EXPERIENCE STORE]: ");
        }
    }

    public function show(int $id)
    {
        try {
            $experience = $this->service->show($id);
            return ResponseHelper::success(new EducationExperienceResource($experience), 'Detail Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display experience is failed ", $e, "[EXPERIENCE SHOW]: ");
        }
    }

    public function update(EducationExperienceRequest $request, int $id)
    {
        try {
            $experience = $this->service->update($id, $request->validated());
            return ResponseHelper::success(new EducationExperienceResource($experience), 'Update Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops update experience is failed ", $e, "[EXPERIENCE UPDATE]: ");
        }
    }

    public function destroy($id)
    {
        try {
            $experience = EducationExperience::findOrFail($id);
            if(!$experience) return ResponseHelper::notFound('Data Not Found');
            $this->service->softDelete($id);
            return ResponseHelper::success(null, 'Delete Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops delete experience is failed ", $e, "[EXPERIENCE DESTROY]: ");
        }
    }
    public function getExperienceByUser()
    {
        try {
            $experiences = $this->service->getEducationExperienceByUser();
            return ResponseHelper::success(EducationExperienceResource::collection($experiences), 'Display Data Successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display all experience is failed ", $e, "[EXPERIENCE INDEX]: ");
        }
    }

    public function trash(EducationExperienceService $service) {
        try {
            $experience = $service->trash();
            if($experience->isEmpty()) {
                return ResponseHelper::notFound('Experiences not found');
            }
            return ResponseHelper::success(EducationExperienceResource::collection($experience), 'Experience trashed items retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display experience is failed ", $e, "[EXPERIENCE TRASH]: ");
        }
    }
    public function restore(EducationExperienceService $service, $id) {
        try {
            $experience = $service->restore($id);
            if(!$experience) {
                return ResponseHelper::notFound('Data Not Found');
            }
            return ResponseHelper::success(new EducationExperienceResource($experience), 'Experience restored items retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display experience is failed ", $e, "[EXPERIENCE RESTORE]: ");
        }
    }
}
