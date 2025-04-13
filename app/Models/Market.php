<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    protected $table = 'market';
    
    protected $fillable = ['name',"created_by"];
    public function submarkets()
    {
        return $this->hasMany(Submarket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
