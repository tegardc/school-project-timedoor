<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Testing\Fluent\Concerns\Has;

class EducationExperience extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = true;
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    public const DELETED_AT = 'deletedAt';
    public $dates = ['deletedAt'];
    protected $fillable = [
        'userId',
        'relation',
        'educationLevelId',
        'schoolDetailId',
        'educationProgramId',
        'degree',
        'startDate',
        'endDate',
    ];
  public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class, 'educationLevelId');
    }

    public function schoolDetail()
    {
        return $this->belongsTo(SchoolDetail::class, 'schoolDetailId');
    }

    public function educationProgram()
    {
        return $this->belongsTo(EducationProgram::class, 'educationProgramId');
    }
    //
}
