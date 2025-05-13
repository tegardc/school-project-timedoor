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
        'province_id',
        'district_id',
        'sub_district_id',
        'operational_license',
        'exam_info'
    ];

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
        return $this->belongsTo(SubDistrict::class);
    }
}
