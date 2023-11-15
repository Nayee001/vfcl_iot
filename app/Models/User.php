<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    // common user status
    const USER_STATUS = [
        'INACTIVE' => 0,
        'ACTIVE' => 1,
        'NEWUSER' => 2,
        'NOACTIVEDEVICE' => 3,
        'FIRSTTIMEPASSWORDCHANGED' => 4,
    ];

    //TERMS AND CONDITIONS :)
    const TERMS_AND_CONDITIONS = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'fname',
        'lname',
        'title',
        'email',
        'password',
        'phonenumber',
        'created_at',
        'updated_at',
        'status',
        'created_by',
        'terms_and_conditions',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Self Relation
    public function creater()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function locations()
    {
        return $this->belongsTo(Location::class, 'id','user_id');
    }

    public function locationNames()
    {
        return $this->belongsTo(LocationName::class, 'id','user_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id', 'id', 'id');
    }
}
