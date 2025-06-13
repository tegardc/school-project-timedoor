<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolDetail extends Model
{
    use HasFactory;
    public $timestamps = true;
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    protected $fillable = [
        'name',
        'institutionCode',
        'schoolId',
        'statusId',
        'educationLevelId',
        'ownershipStatus',
        'dateEstablishmentDecree',
        'operationalLicense',
        'dateOperationalLicense',
        'principal',
        'operator',
        'accreditationId',
        'curriculum',
        'telpNo',
        'tuitionFee',
        'numStudent',
        'numTeacher',
        'movie',
        'examInfo',
        'createdAt',
        'updatedAt'
    ];
    public function child()
    {
        return $this->hasMany(Child::class);
    }
    public function schools()
    {
        return $this->belongsTo(School::class, 'schoolId');
    }
    public function status()
    {
        return $this->belongsTo(SchoolStatus::class, 'statusId');
    }
    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }
    public function accreditation()
    {
        return $this->belongsTo(Accreditation::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'schoolDetailId')
            ->where('status', Review::STATUS_APPROVED);
    }
    public function schoolGallery()
    {
        return $this->hasMany(SchoolGallery::class, 'schoolDetailId');
    }
}
