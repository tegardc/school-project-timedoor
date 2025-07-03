<?php

namespace App\Models;

use GuzzleHttp\Promise\Create;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accreditation extends Model
{
    use HasFactory;
    public $timestamps = true;
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    protected $fillable = [
        'code',
    ];
/*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Get the school details associated with the accreditation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

/*******  41c89c58-5fb9-4c17-86a1-5f6e554556cb  *******/
    public function schoolDetails()
    {
        return $this->hasMany(SchoolDetail::class);
    }
}
