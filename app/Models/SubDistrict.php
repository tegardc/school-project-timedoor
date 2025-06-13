<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubDistrict extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'districtId'];
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    public $timestamps = true;
    public function districts()
    {
        return $this->belongsTo(District::class, 'districtId');
    }
}
