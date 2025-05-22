<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class school_detail extends Model
{
    use HasFactory;
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
        'examInfo'
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
        return $this->belongsTo(SchoolStatus::class);
    }
    public function education_level()
    {
        return $this->belongsTo(EducationLevel::class);
    }
    public function accreditation()
    {
        return $this->belongsTo(Accreditation::class);
    }
}
