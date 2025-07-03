<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolStatus extends Model
{
    use HasFactory;
    public $timestamps = true;
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    protected $fillable = [
        'name',
    ];
    public function schoolDetails()
    {
        return $this->hasMany(SchoolDetail::class);
    }
}
