<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Address extends Model
{
    use HasFactory;
    public $timestamps = true;
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    protected $fillable = [
        'provinceId',
        'districtId',
        'subDistrictId',
        'village',
        'street',
        'postalCode',
        'latitude',
        'longitude',
    ];

     public function province()
    {
        return $this->belongsTo(Province::class,  'provinceId', 'id');
    }
    public function district()
    {
        return $this->belongsTo(District::class,  'districtId', 'id');
    }
    public function subdistrict()
    {
        return $this->belongsTo(SubDistrict::class,  'subDistrictId', 'id');
    }
    public  function schoolDetail(){
        return $this->hasMany(SchoolDetail::class, 'addressId');
    }
    //
}
