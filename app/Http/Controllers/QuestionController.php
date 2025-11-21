<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\QuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\Services\QuestionService;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(QuestionService $service)
    {
        try {
            $questions = $service->getAll();
            if($questions->isEmpty()) return ResponseHelper::notFound('Data Not Found');
            return ResponseHelper::success($questions, 'List of review questions');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Failed to fetch questions", $e, "[QUESTION INDEX]: ");
        }
    }

    public function store(QuestionRequest $request, QuestionService $service)
    {
        try {
            $validated = $request->validated();
            $question =$service->store($validated);
            return ResponseHelper::created(new QuestionResource($question), 'Question created successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Failed to create question", $e, "[QUESTION STORE]: ");
        }
    }

    public function show(QuestionService $service,$id)
    {
        try {
            $question = $service->show($id);
            if(!$question) return ResponseHelper::notFound('Data Not Found');
            return ResponseHelper::success(QuestionResource::make($question), 'Question retrieved');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Failed to fetch question", $e, "[QUESTION SHOW]: ");
        }
        //
    }


    public function update(QuestionRequest $request, QuestionService $service, $id)
    {
        try {
            $validated = $request->validated();
            $question = $service->update($validated, $id);
            if(!$question) return ResponseHelper::notFound('Data Not Found');
            return ResponseHelper::success(QuestionResource::make($question), 'Question updated successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Failed to update question", $e, "[QUESTION UPDATE]: ");
        }
        //
    }

    public function destroy(QuestionService $service, $id)
    {
        try {
            $question = $service->show($id);
            if(!$question) return ResponseHelper::notFound('Data Not Found');
            $service->softDelete($id);
            return ResponseHelper::success(null, 'Question deleted successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Failed to delete question", $e, "[QUESTION DELETE]: ");
        }
        //
    }
    public function trash(QuestionService $service) {
        try {
            $questions = $service->trash();
            if($questions->isEmpty()) {
                return ResponseHelper::notFound('Questions not found');
            }
            return ResponseHelper::success(QuestionResource::collection($questions), 'Question trashed items retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display question is failed ", $e, "[QUESTION TRASH]: ");
        }
    }
    public function restore(QuestionService $service, $id) {
        try {
            $question = $service->restore($id);
            if(!$question) {
                return ResponseHelper::notFound('Data Not Found');
            }
            return ResponseHelper::success(new QuestionResource($question), 'Question restored successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops restore question is failed ", $e, "[QUESTION RESTORE]: ");
        }
    }
}
