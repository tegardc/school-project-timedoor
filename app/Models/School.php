<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory;
    public $timestamps = true;

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    protected $fillable = [
        'name',
        'description',
        'provinceId',
        'districtId',
        'subDistrictId',
        'schoolEstablishmentDecree',
        'createdAt',
        'updatedAt'

    ];

    public function schoolDetais()
    {
        return $this->hasMany(SchoolDetail::class,'schoolId');
    }

    public function province()
    {
        return $this->belongsTo(Province::class,  'provinceId', 'id');
    }
    public function district()
    {
        return $this->belongsTo(District::class,  'districtId', 'id');
    }
    public function subDistrict()
    {
        return $this->belongsTo(SubDistrict::class,  'subDistrictId', 'id');
    }
    public function schoolGallery()
    {
        return $this->hasMany(SchoolGallery::class, 'schoolId');
    }
    public function coverImage()
    {
        return $this->hasOne(SchoolGallery::class, 'schoolId')->where('isCover', 1);
    }
}
