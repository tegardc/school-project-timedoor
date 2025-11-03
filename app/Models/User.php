<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Http\Requests\ReviewRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guard_name = 'api';
    public $timestamps = true;
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    public const DELETED_AT = 'deletedAt';
    protected $dates = ['deletedAt'];
    protected $fillable = [
        'fullname',
        'email',
        'gender',
        'dateOfBirth',
        'phoneNo',
        'password',
        'nisn',
        // 'schoolDetailId',
        'status',
        'image',
        'schoolValidation',
        'address',
        'createdAt',
        'updatedAt'
    ];

    public function childs()
    {
        return $this->belongsToMany(Child::class, 'user_child_school', 'userId', 'childId')
            ->withPivot('schoolDetailId')
            ->withTimestamps();
    }
    public function child()
    {
        return $this->hasOne(Child::class, 'userId'); // satu user (parent) punya 1 child
    }

    public function children()
    {
        return $this->hasMany(Child::class, 'userId'); // kalau nanti butuh banyak anak
    }

    public function review()
    {
        return $this->hasMany(Review::class);
    }
    public function childSchoolDetails()
    {
        return $this->belongsToMany(SchoolDetail::class, 'user_child_school', 'userId', 'schoolDetailId')
            ->withPivot('childId')->withTimestamps('createdAt', 'updatedAt');;
    }
    public function educationExperiences()
    {
        return $this->hasMany(EducationExperience::class, 'userId');
    }
    public function schoolValidations()
    {
        return $this->hasMany(SchoolValidation::class, 'userId');
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'rememberToken',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'emailVerifiedAt' => 'datetime',
        'password' => 'hashed',
    ];
}
