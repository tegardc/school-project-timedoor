<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accreditation extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function schoolDetails()
    {
        return $this->hasMany(SchoolDetail::class);
    }
}
