<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolStatus extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name',
    ];
    public function schoolDetails()
    {
        return $this->hasMany(SchoolDetail::class);
    }
}
