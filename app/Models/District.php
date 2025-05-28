<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'provinceId'];
    public $timestamps = false;
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
    public function subDistricts()
    {
        return $this->hasMany(SubDistrict::class);
    }
}
