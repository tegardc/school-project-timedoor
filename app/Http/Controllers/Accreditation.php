<?php

namespace App\Models;

use GuzzleHttp\Promise\Create;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accreditation extends Model
{
    use HasFactory;
    public $timestamps = true;
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    protected $fillable = [
        'code',
    ];
    /**
     * Get the school details associated with the accreditation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function schoolDetails()
    {
        return $this->hasMany(SchoolDetail::class);
    }
}
