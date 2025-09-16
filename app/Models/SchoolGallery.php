<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolGallery extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [ 'schoolDetailId', 'imageUrl', 'isCover'];

    public function schoolDetails()
    {
        return $this->belongsTo(SchoolDetail::class,);
    }
}

    //
