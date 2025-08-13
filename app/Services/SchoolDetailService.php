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
        $facilityIds = $validated['facilityIds'] ?? [];
        unset($validated['facilityIds']);

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
        if (!empty($validated['contacts'])) {
            $schoolDetail->contacts()->createMany($validated['contacts']);
        }

        if (!empty($facilityIds)) {
            $schoolDetail->facilities()->attach($facilityIds);
        }


        $schoolDetail->load(['schoolGallery', 'facilities','contacts']);

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

        $facilityIds = $validated['facilityIds'] ?? null;
        unset($validated['facilityIds']);

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

        if (is_array($facilityIds)) {
            $schoolDetail->facilities()->sync($facilityIds);
        }
        if (!empty($validated['contacts'])) {
            $schoolDetail->contacts()->delete();
            $schoolDetail->contacts()->createMany($validated['contacts']);
        }

        $schoolDetail->load(['schoolGallery', 'facilities','contacts']);

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
        // 'telpNo',
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
        'facilities:id,name',
        'contacts'
    ])->withCount('reviews')
    ->withAvg('reviews', 'rating');

    $query = $this->applyFilters($query, $filters);

    return $query->paginate($perPage);
    }

    private function applyFilters($query, array $filters)
    {

        if(!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                ->orWhere('institutionCode', 'like', '%' . $filters['search'] . '%')
                ->orWhereHas('schools', function ($q2) use ($filters) {
                    $q2->where('name', 'like', '%' . $filters['search'] . '%');
                });
            });
        }

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
        if(!empty($filters['sortBy'])) {
            $sortField = $filters['sortBy'];
            $sortDirection = $filters['sortDirection'] ?? 'asc';

            $allowedSortFields = [
                'name',
                'institutionCode',
                'numStudent',
                'numTeacher',
                'tuitionFee',
                'reviews_avg_rating',
                'reviews_count',
                'createdAt',
            ];

            if (in_array($sortField, $allowedSortFields)) {
                $query->orderBy($sortField, $sortDirection);
            }
            // if($perPage === 'all'){
            //     return $query->get();
            // }

        }else{
            $query->orderByDesc('createdAt');
        }
        return $query;

        // return $query->get();
    }
    public function getSchoolDetailBySchoolId($schoolId)
    {
        return SchoolDetail::where('schoolId', $schoolId)->get();
    }

public function ranking(array $filters = [])
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
    return $query->take(10)->get();

}

}
