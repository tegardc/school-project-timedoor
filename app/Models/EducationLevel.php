<?php

namespace App\Models;

use App\Http\Controllers\SchoolDetailController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationLevel extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function schoolDetails()
    {
        return $this->hasMany(SchoolDetail::class);
    }
}
