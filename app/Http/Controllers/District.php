<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class District extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['name', 'provinceId'];
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    public const DELETED_AT = 'deletedAt';
    public $timestamps = true;
    protected $dates = ['deletedAt'];
    public function province()
    {
        return $this->belongsTo(Province::class, 'provinceId');
    }
    public function subDistrict()
    {
        return $this->hasMany(SubDistrict::class, 'districtId');
    }
}
