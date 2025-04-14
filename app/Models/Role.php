<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // The attributes that are mass assignable
    protected $fillable = [
        'name', // Assuming the role table has a 'name' field
    ];

    // Define the inverse relationship with User
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
