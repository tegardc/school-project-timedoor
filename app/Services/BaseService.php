<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseService
{
    protected string $modelClass;

    public function softDelete(int $id): ?Model
    {
        return DB::transaction(function () use ($id) {
            $model = $this->modelClass::find($id);
            if (!$model) return null;
            $model->delete();
            return $model;
        });
    }

    public function restore(int $id): ?Model
    {
        return DB::transaction(function () use ($id) {
            $model = $this->modelClass::onlyTrashed()->find($id);
            if (!$model) return null;
            $model->restore();
            return $model;
        });
    }

    public function trash()
    {
        return $this->modelClass::onlyTrashed()->orderByDesc('deletedAt')->get();
    }

    public function forceDelete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $model = $this->modelClass::onlyTrashed()->find($id);
            if (!$model) return false;
            return $model->forceDelete();
        });
    }
}
