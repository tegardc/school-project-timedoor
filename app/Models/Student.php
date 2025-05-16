<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PDO;

class Student extends Model
{
    use HasFactory;

    public function parent()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function school()
    {
        return $this->belongsTo(school_detail::class, 'school_detail_id');
    }
}
