<?php

namespace App\Services;

use App\Models\Child;
use App\Models\SchoolDetail;
use App\Models\SchoolGallery;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SchoolDetailService extends BaseService
{

    public function __construct()
    {
        $this->modelClass = SchoolDetail::class;
    }
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
    public function filter(array $filters = [], $perPage = 10)
    {
        $query = SchoolDetail::select([
        'id',
        'name',
        'institutionCode',
        'schoolId',
        'statusId',
        'educationLevelId',
        'dateEstablishmentDecree',
        'operationalLicense',
        'dateOperationalLicense',
        'accreditationId',
        'telpNo',
        'operator',
        'ownershipStatus',
        'curriculum',
        'principal',
        'tuitionFee',
        'numStudent',
        'numTeacher',
        'examInfo',
        'movie',

    ])
    ->with([
        'schools:id,name,provinceId,districtId,subDistrictId',
        'status:id,name',
        'educationLevel:id,name',
        'accreditation:id,code',
        'schoolGallery:id,schoolDetailId,imageUrl,isCover',
    ])->withCount('reviews')
    ->withAvg('reviews', 'rating');

        if (!empty($filters['provinceName'])) {
            $query->whereHas('schools.province', function ($q) use ($filters) {
                $q->where('name', 'like' , '%' . $filters['provinceName'] . '%');
            });
        }

        if (!empty($filters['districtName'])) {
            $query->whereHas('schools.district', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['districtName'] . '%');
            });
        }

        if (!empty($filters['subDistrictName'])) {
            $query->whereHas('schools.subDistrict', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['subDistrictName'] . '%');
            });
        }

        if (!empty($filters['educationLevelName'])) {
            $query->whereHas('educationLevel', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['educationLevelName'] . '%');
            });
        }

        if (!empty($filters['statusName'])) {
            $query->whereHas('status', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['statusName'] . '%');
        });
        }

        if (!empty($filters['accreditationCode'])) {
            $query->whereHas('accreditation', function ($q) use ($filters) {
                $q->where('code', 'like', '%' . $filters['accreditationCode'] . '%');
        });
        }
        // if (!empty($filters['schoolName'])) {
        //     $query->whereHas('schools', function ($q) use ($filters) {
        //         $q->where('name', 'like', '%' . $filters['schoolName'] . '%');
        // });
        // }
        return $query->paginate($perPage);
    }
    public function getSchoolDetailBySchoolId($schoolId)
    {
        return SchoolDetail::where('schoolId', $schoolId)->get();

    }
    public function getBySubDistrict($subDistrictId)
{
    return SchoolDetail::whereHas('schools', function ($query) use ($subDistrictId) {
        $query->where('subDistrictId', $subDistrictId);
    })
    ->with([
        'schools:id,name,provinceId,districtId,subDistrictId',
        'status:id,name',
        'educationLevel:id,name',
        'accreditation:id,code',
        'schoolGallery:id,schoolDetailId,imageUrl,isCover',
        'reviews'
    ])
    ->withCount('reviews')
    ->withAvg('reviews', 'rating')
    ->get();
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
    return $query->paginate($perPage??10);
}
public function ranking(array $filters = [], $perPage = null)
{
    $query = SchoolDetail::select([
       'id',
        'name',
        'institutionCode',
        'schoolId',
        'statusId',
        'educationLevelId',
        'dateEstablishmentDecree',
        'operationalLicense',
        'dateOperationalLicense',
        'accreditationId',
        'telpNo',
        'operator',
        'ownershipStatus',
        'curriculum',
        'principal',
        'tuitionFee',
        'numStudent',
        'numTeacher',
        'examInfo',
        'movie',
    ])
    ->with([
        'schools:id,name,provinceId,districtId,subDistrictId',
        'status:id,name',
        'educationLevel:id,name',
        'accreditation:id,code',
        'schoolGallery:id,schoolDetailId,imageUrl,isCover',
        'reviews'
    ])
    ->withCount('reviews')
    ->withAvg('reviews', 'rating')
    ->orderByDesc('reviews_avg_rating')
    ->orderByDesc('reviews_count');

    if (!empty($filters['provinceName'])) {
        $query->whereHas('schools.province', function ($q) use ($filters) {
            $q->where('name', 'like' , '%' . $filters['provinceName'] . '%');
        });
    }

    if (!empty($filters['districtName'])) {
        $query->whereHas('schools.district', function ($q) use ($filters) {
            $q->where('name', 'like', '%' . $filters['districtName'] . '%');
        });
    }
    if(!empty($filters['subDistrictName'])) {
        $query->whereHas('schools.subDistrict', function ($q) use ($filters) {
            $q->where('name', 'like', '%' . $filters['subDistrictName'] . '%');
        });
    }
    if(!empty($filters['educationLevelName'])) {
        $query->whereHas('educationLevel', function ($q) use ($filters) {
            $q->where('name', 'like', '%' . $filters['educationLevelName'] . '%');
        });
    }
    if(!empty($filters['statusName'])) {
        $query->whereHas('status', function ($q) use ($filters) {
            $q->where('name', 'like', '%' . $filters['statusName'] . '%');
        });
    }
    if(!empty($filters['accreditationCode'])) {
        $query->whereHas('accreditation', function ($q) use ($filters) {
            $q->where('code', 'like', '%' . $filters['accreditationCode'] . '%');
        });
    }
    return $query->paginate($perPage);

}

}
