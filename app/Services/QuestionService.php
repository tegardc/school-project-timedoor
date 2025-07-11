<?php

namespace App\Services;

use App\Models\Question;
use Illuminate\Support\Facades\DB;

class QuestionService extends BaseService
{
    public function __construct()
    {
        $this->modelClass = Question::class;
    }
    public function store(array $validated): Question
    {
        return DB::transaction(function () use ($validated) {
            $question = Question::create($validated);
            return $question;
        });
    }
    public function getAll(){
        $query = Question::select([
            'id',
            'question'
        ]);
        return $query->get();
    }
    public function update(array $validated, int $id): ?Question
    {
        return DB::transaction(function () use ($validated, $id) {
            $question = Question::find($id);
            if (!$question) {
                return null;
            }
            $question->update($validated);
            return $question;
        });
    }

    public function show(int $id): ?Question
    {
        return Question::find($id);
        if(!$question) return null;
        return $question;
    }
}
