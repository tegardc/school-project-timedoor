<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'provinceId'];
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    public $timestamps = true;
    public function province()
    {
        return $this->belongsTo(Province::class, 'provinceId');
    }
    public function subDistrict()
    {
        return $this->hasMany(SubDistrict::class, 'subDistrictId');
    }
}
