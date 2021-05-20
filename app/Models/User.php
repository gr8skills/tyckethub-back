<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    const AVATAR = '/images/avatar.png';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'uid',
        'bio',
        'address',
        'state_id',
        'bio',
        'verification_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected$appends = [];

//    public function getRoleAttribute()
//    {
//        return $this->roles()->first()->name;
//    }

    public function isVerified()
    {
        return $this->email_verified_at !== null && is_a($this->email_verified_at, 'DateTime');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user_pivot', 'user_id', 'role_id');
    }

    public function paymentCards()
    {
        return $this->hasMany(PaymentCard::class, 'user_id');
    }

    public static function generateUId()
    {
        return uniqid(random_int(1, 10), true);
    }

    public static function generateVerificationToken()
    {
        return Str::random(64);
    }
}
