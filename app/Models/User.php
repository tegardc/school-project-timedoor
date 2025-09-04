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
        'firstName',
        'lastName',
        'username',
        'email',
        'gender',
        'phoneNo',
        'password',
        // 'nis',
        // 'schoolDetailId',
        'image',
        'createdAt',
        'updatedAt'
    ];

   public function childs()
{
    return $this->belongsToMany(Child::class, 'user_child_school', 'userId', 'childId')
                ->withPivot('schoolDetailId')
                ->withTimestamps();
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
