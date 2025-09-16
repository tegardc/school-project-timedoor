<?php

namespace App\Models;

use App\Http\Controllers\SchoolDetailController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationLevel extends Model
{
    use HasFactory;
    public $timestamps = true;
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    protected $fillable = ['name'];

    public function schoolDetails()
    {
        return $this->hasMany(SchoolDetail::class);
    }
    public function experiences()
{
    return $this->hasMany(EducationExperience::class, 'educationLevelId');
}

}
