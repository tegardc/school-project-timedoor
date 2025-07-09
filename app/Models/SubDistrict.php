<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubDistrict extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'districtId'];
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    public const DELETED_AT = 'deletedAt';
    public $timestamps = true;
    protected $dates = ['deletedAt'];
    public function districts()
    {
        return $this->belongsTo(District::class, 'districtId');
    }
}
