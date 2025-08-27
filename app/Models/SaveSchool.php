<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaveSchool extends Model
{
    use HasFactory;
    public $timestamps = true;
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    protected $fillable = [
        'userId',
        'schoolDetailId',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }
    public function schoolDetail()
    {
        return $this->belongsTo(SchoolDetail::class, 'schoolDetailId', 'id');
    }
    //
}
