<?php

namespace App\Models;

use App\Http\Controllers\SchoolDetailController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationLevel extends Model
{
    use HasFactory;

    public function school_detail()
    {
        return $this->hasMany(school_detail::class);
    }
}
