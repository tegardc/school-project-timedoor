<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Testing\Fluent\Concerns\Has;

class Facility extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = true;
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    public const DELETED_AT = 'deletedAt';
    protected $dates = ['deletedAt'];
    protected $fillable = ['name'];

    public function schoolDetails()
    {
        return $this->belongsToMany(SchoolDetail::class, 'school_detail_facility', 'facilityId', 'schoolDetailId');
    }

    //
}
