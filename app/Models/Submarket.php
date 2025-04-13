<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submarket extends Model
{
    protected $table = 'submarket';

    protected $fillable = [
        'name',
        'market_id',
    ];

    public function market()
    {
        return $this->belongsTo(Market::class);
    }
}
