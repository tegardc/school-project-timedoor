<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    public $timestamps = true;
    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
