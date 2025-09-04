<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PDO;

class Child extends Model
{
    use HasFactory;
    protected $table = 'childs';
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    protected $fillable = ['name', 'userId', 'nis', 'schoolDetailId'];
    public $timestamps = true;

    public function parent()
    {
         return $this->belongsToMany(User::class, 'user_child_school', 'childId', 'userId')
                    ->withPivot('schoolDetailId')
                    ->withTimestamps();
    }
    public function schoolDetails()
    {
        return $this->belongsToMany(SchoolDetail::class, 'user_child_school', 'childId', 'schoolDetailId')
                    ->withPivot('userId')
                    ->withTimestamps();
    }
}
