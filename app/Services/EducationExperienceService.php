<?php

namespace App\Services;

use App\Models\EducationExperience;
use Illuminate\Support\Facades\Auth;

class EducationExperienceService extends BaseService
{
    public function __construct()
    {
        $this->modelClass = EducationExperience::class;
    }

    public function index()
    {
        return EducationExperience::with([
            'educationLevel',
            'schoolDetail',
            'educationProgram'
        ])
            ->where('userId', Auth::id())
            ->get();
    }

    public function store(array $data): EducationExperience
    {
        $user = Auth::user();
        $data['userId'] = $user->id;

        return EducationExperience::create($data);
    }

    public function show(int $id): EducationExperience
    {
        return EducationExperience::with([
            'educationLevel',
            'schoolDetail',
            'educationProgram'
        ])
            ->where('userId', Auth::id())
            ->findOrFail($id);
    }

    public function update(int $id, array $data): EducationExperience
    {
        $experience = EducationExperience::where('userId', Auth::id())->findOrFail($id);
        $experience->update($data);
        return $experience;
    }

    public function destroy(int $id): bool
    {
        $experience = EducationExperience::where('userId', Auth::id())->findOrFail($id);
        return $experience->delete();
    }
    public function getEducationExperienceByUser()
    {
        $user = Auth::user();
        return EducationExperience::with([
            'educationLevel',
            'schoolDetail',
            'educationProgram'
        ])->where('userId', $user->id)->get();
    }
}
