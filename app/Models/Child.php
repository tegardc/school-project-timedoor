<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PDO;

class Child extends Model
{
    use HasFactory;

    public function parent()
    {
        return $this->belongsTo(User::class, 'userId');
    }
    public function school()
    {
        return $this->belongsTo(school_detail::class, 'schoolDetailId');
    }
}
