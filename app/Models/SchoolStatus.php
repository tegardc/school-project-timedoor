<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolStatus extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];
    public function school_detail()
    {
        return $this->hasMany(school_detail::class);
    }
}
