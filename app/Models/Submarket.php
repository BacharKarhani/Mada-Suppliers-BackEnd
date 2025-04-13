<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submarket extends Model
{
    protected $table = 'submarket';

    protected $fillable = [
        'name',
        'market_id',
        "created_by",
    ];

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
