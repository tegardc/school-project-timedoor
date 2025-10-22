<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = true;
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    public const DELETED_AT = 'deletedAt';
    protected $dates = ['deletedAt'];
    protected $fillable = [
        'name',
        // 'description',
        'schoolEstablishmentDecree',
        'createdAt',
        'updatedAt'

    ];

    public function schoolDetails()
    {
        return $this->hasMany(SchoolDetail::class,'schoolId');
    }



}
