<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolGallery extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['schoolId', 'schoolDetailId', 'imageUrl', 'isCover'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public function schoolDetails()
    {
        return $this->belongsTo(SchoolDetail::class,);
    }
}

    //
