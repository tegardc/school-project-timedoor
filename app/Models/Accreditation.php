<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accreditation extends Model
{
    use HasFactory;
    public function school_detail()
    {
        return $this->hasMany(school_detail::class);
    }
}
