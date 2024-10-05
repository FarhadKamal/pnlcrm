<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_name',
        'user_phone',
        'user_email',
        'password',
        'user_desg',
        'user_dept',
        'user_location',
        'user_signature',
        'is_admin',
        'is_active',
        'assign_to'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    public function department()
    {
        return $this->hasOne(Department::class, 'id', 'user_dept');
    }
    public function designation()
    {
        return $this->hasOne(Designation::class, 'id', 'user_desg');
    }
    public function location()
    {
        return $this->hasOne(SystemLocation::class, 'id', 'user_location');
    }
    public function clientInfo()
    {
        return $this->hasMany(Customer::class, 'assign_to', 'assign_to');
    }
}
