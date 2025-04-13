<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    protected $table = 'market';
    protected $fillable = ['name'];
    public function submarkets()
    {
        return $this->hasMany(Submarket::class);
    }
}
