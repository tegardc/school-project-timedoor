<?php

namespace App\Services;

use App\Models\EducationProgram;

class EducationProgramService extends BaseService
{
    public function __construct()
    {
        $this->modelClass = EducationProgram::class;

    }
    public function getAll()
    {
        return EducationProgram::all();
    }

    public function store(array $data): EducationProgram
    {
        return EducationProgram::create($data);
    }

    public function update(int $id, array $data): EducationProgram
    {
        $program = EducationProgram::findOrFail($id);
        $program->update($data);

        return $program;
    }

    public function destroy(int $id): bool
    {
        $program = EducationProgram::findOrFail($id);
        return $program->delete();
    }
}
