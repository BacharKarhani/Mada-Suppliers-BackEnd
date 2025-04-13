<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorsOrSuppliers extends Model
{
    use HasFactory;
    
    protected $table = 'contracters_or_suppliers';

    protected $fillable = [
        'base',
        'market_id',
        'submarket_id',
        'geographic_capacity',
        'company_or_individual_name',
        'representative_name',
        'date_of_birth',
        'address',
        'email',
        'phone',
        'vat_registration_number',
        'type_of_business_registered_for',
        'owner_name'
    ];
}
