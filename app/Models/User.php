<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    // The attributes that are mass assignable
    protected $fillable = [
        'name', 'email', 'password', 'gender', 'age', 'phone_number', 'status', 'role_id'
    ];

    // The attributes that should be hidden for arrays
    protected $hidden = [
        'password', 'remember_token'
    ];

    // Cast the password attribute to 'hashed'
    protected $casts = [
        // 'password' => 'hashed'
    ];
}
