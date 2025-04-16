<?php

namespace App\Exports;

use App\Models\ContractorsOrSuppliers;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContractorsOrSuppliersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Get all contractors or suppliers, but exclude 'id', 'created_at', and 'updated_at' columns
        return ContractorsOrSuppliers::all(['base', 'market_id', 'submarket_id', 'geographic_capacity', 'company_or_individual_name', 'representative_name', 'date_of_birth', 'address', 'email', 'phone', 'vat_registration_number', 'type_of_business_registered_for', 'owner_name']);
    }

    public function headings(): array
    {
        // Define the headings without 'id', 'created_at', and 'updated_at'
        return [
            'Base',
            'Market ID',
            'Submarket ID',
            'Geographic Capacity',
            'Company/Individual Name',
            'Representative Name',
            'Date of Birth',
            'Address',
            'Email',
            'Phone',
            'VAT Registration Number',
            'Type of Business Registered For',
            'Owner Name',
        ];
    }
}

