<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolValidation extends Model
{
    use HasFactory;
    public $timestamps = true;
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'userId',
        'schoolDetailId',
        'fileUrl'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    // Relasi ke SchoolDetail
    public function schoolDetail()
    {
        return $this->belongsTo(SchoolDetail::class, 'schoolDetailId');
    }

    // Relasi opsional ke Review
   public function review()
{
    return $this->belongsTo(Review::class, 'schoolDetailId', 'schoolDetailId');
}

}
