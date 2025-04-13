<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ContractersOrSuppliers;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'contracters_or_suppliers_id',
        'user_id',
        'text',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contractorOrSupplier()
    {
        return $this->belongsTo(ContractorsOrSuppliers::class, 'contracters_or_suppliers_id');
    }
}
