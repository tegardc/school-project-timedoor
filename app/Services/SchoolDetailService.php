<?php

namespace App\Services;

use App\Models\Child;
use App\Models\SchoolDetail;
use App\Models\SchoolGallery;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SchoolDetailService
{
    public function store(array $validated): SchoolDetail
    {
        return DB::transaction(function () use ($validated) {
            $schoolDetail = SchoolDetail::create($validated);
            if (!empty($validated['imageUrl'])) {
                foreach ($validated['imageUrl'] as $imageUrl) {
                    SchoolGallery::create([
                        'schoolDetailId' => $schoolDetail->id,
                        'schoolId' => $schoolDetail->schoolId,
                        'imageUrl' => $imageUrl,
                    ]);
                }
            }

            $schoolDetail->load(['schoolGallery']);

            return $schoolDetail;
        });
    }
    public function update(array $validated, int $id): ?SchoolDetail
    {
        return DB::transaction(function () use ($validated, $id) {
            $schoolDetail = SchoolDetail::find($id);
            if (!$schoolDetail) {
                return null;
            }
            $schoolDetail->update($validated);
            if (!empty($validated['imageUrl'])) {
                SchoolGallery::where('schoolDetailId', $schoolDetail->id)->delete();
                foreach ($validated['imageUrl'] as $index => $imageUrl) {
                    SchoolGallery::create([
                        'schoolDetailId' => $schoolDetail->id,
                        'schoolId' => $schoolDetail->schoolId,
                        'imageUrl' => $imageUrl,
                        'isCover' => $index === 0 ? 1 : 0
                    ]);
                }
            }

            $schoolDetail->load(['schoolGallery']);

            return $schoolDetail;
        });
    }
    public function filter(array $filters)
    {
        $query = SchoolDetail::query();

        if (!empty($filters['provinceId'])) {
            $query->whereHas('schools', function ($q) use ($filters) {
                $q->where('provinceId', $filters['provinceId']);
            });
        }

        if (!empty($filters['districtId'])) {
            $query->whereHas('schools', function ($q) use ($filters) {
                $q->where('districtId', $filters['districtId']);
            });
        }

        if (!empty($filters['subDistrictId'])) {
            $query->whereHas('schools', function ($q) use ($filters) {
                $q->where('subDistrictId', $filters['subDistrictId']);
            });
        }

        if (!empty($filters['educationLevelId'])) {
            $query->where('educationLevelId', $filters['educationLevelId']);
        }

        if (!empty($filters['statusId'])) {
            $query->where('statusId', $filters['statusId']);
        }

        if (!empty($filters['accreditationId'])) {
            $query->where('accreditationId', $filters['accreditationId']);
        }
        if (!empty($filters['schoolId'])) {
            $query->where('schoolId', $filters['schoolId']);
        }
        return $query->with(['schoolGallery', 'schools'])->get();
    }
    public function getAll($perPage = null)
{
    $query = SchoolDetail::select([
        'id',
        'name',
        'schoolId',
        'statusId',
        'educationLevelId',
        'accreditationId',
        'telpNo'
    ])
    ->with([
        'schools:id,name,provinceId,districtId,subDistrictId',
        'status:id,name',
        'educationLevel:id,name',
        'accreditation:id,code',
        'schoolGallery:id,schoolDetailId,imageUrl,isCover'
    ]);

    if ($perPage) {
        return $query->paginate($perPage);
    }

    return $query->get();
}

}
