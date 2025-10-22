<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolDetail extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = true;
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    public const DELETED_AT = 'deletedAt';
    protected $dates = ['deletedAt'];
    protected $fillable = [
        'name',
        'institutionCode',
        'schoolId',
        'statusId',
        'educationLevelId',
        'addressId',
        'ownershipStatus',
        'dateEstablishmentDecree',
        'operationalLicense',
        'dateOperationalLicense',
        'principal',
        'operator',
        'accreditationId',
        'educationProgramId',
        'curriculum',
        'tuitionFee',
        'numStudent',
        'numTeacher',
        'movie',
        'examInfo',
    ];
    public function child()
    {
        return $this->hasMany(Child::class);
    }
    /*******  8e9353a6-8544-41e4-a689-b2cee904b425  *******/
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
        return $this->belongsTo(EducationLevel::class, 'educationLevelId');
    }
    public function accreditation()
    {
        return $this->belongsTo(Accreditation::class, 'accreditationId');
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
    public function coverImage()
    {
        return $this->hasOne(SchoolGallery::class, 'schoolDetailId')->where('isCover', 1);
    }
    public function facilities()
    {
        return $this->belongsToMany(Facility::class,  'school_detail_facility', 'schoolDetailId', 'facilityId');
    }
    public function contacts()
    {
        return $this->hasMany(Contact::class, 'schoolDetailId');
    }
    public function educationExperiences()
    {
        return $this->hasMany(EducationExperience::class, 'schoolDetailId');
    }
    public function address()
    {
        return $this->belongsTo(Address::class, 'addressId');
    }
    public function educationProgram()
    {
        return $this->belongsTo(EducationProgram::class, 'educationProgramId');
    }
    public function siblingDetails()
    {
        return $this->hasMany(SchoolDetail::class, 'schoolId', 'schoolId')
            ->where('id', '<>', $this->id); // biar gak masuk dirinya sendiri
    }
}
