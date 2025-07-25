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
        return $this->belongsTo(User::class, 'userId');
    }
    public function schoolDetails()
    {
        return $this->belongsTo(SchoolDetail::class, 'schoolDetailId');
    }
}
