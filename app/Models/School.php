<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'provinceId',
        'districtId',
        'subDistrictId',
        'schoolEnstablishmentDecree',
    ];
    public function school_detail()
    {
        return $this->hasMany(school_detail::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
    public function district()
    {
        return $this->belongsTo(District::class);
    }
    public function subDistrict()
    {
        return $this->belongsTo(SubDistrict::class,  'subDistrictId', 'id');
    }
}
