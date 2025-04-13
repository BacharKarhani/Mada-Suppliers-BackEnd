<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContractorsOrSuppliers;

class ContractorsOrSuppliersController extends Controller
{
    // ✅ Add new contractor or supplier (admin or user)
    public function store(Request $request)
    {
        $data = $request->validate([
            'base' => 'required',
            'market_id' => 'required|integer',
            'submarket_id' => 'required|integer',
            'geographic_capacity' => 'required',
            'company_or_individual_name' => 'required|string',
            'representative_name' => 'required|string',
            'date_of_birth' => 'required|date',
            'address' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'vat_registration_number' => 'nullable|string',
            'type_of_business_registered_for' => 'required|string',
            'owner_name' => 'required|string',
        ]);

        $record = ContractorsOrSuppliers::create($data);

        return response()->json([
            'message' => 'Contractor or supplier added successfully.',
            'data' => $record
        ], 201);
    }

    // ✅ Update contractor or supplier (admin only)
    public function update(Request $request, $id)
    {
        $user = $request->user();
        if ($user->role_id !== 1) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $contractor = ContractorsOrSuppliers::find($id);

        if (!$contractor) {
            return response()->json(['message' => 'Not found.'], 404);
        }

        $contractor->update($request->all());

        return response()->json([
            'message' => 'Updated successfully.',
            'data' => $contractor
        ]);
    }

    // ✅ Delete contractor or supplier (admin only)
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if ($user->role_id !== 1) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $contractor = ContractorsOrSuppliers::find($id);

        if (!$contractor) {
            return response()->json(['message' => 'Not found.'], 404);
        }

        $contractor->delete();

        return response()->json(['message' => 'Deleted successfully.']);
    }

    // ✅ Get paginated list (everyone)
    public function index(Request $request)
    {
        $contractors = ContractorsOrSuppliers::paginate(5);
        return response()->json($contractors);
    }

    public function filter(Request $request)
    {
        $query = ContractorsOrSuppliers::query();
    
        // Optional filtering by market
        if ($request->filled('market_id')) {
            $query->where('market_id', $request->market_id);
        }
    
        // Optional filtering by submarket
        if ($request->filled('submarket_id')) {
            $query->where('submarket_id', $request->submarket_id);
        }
    
        $results = $query->get();
    
        if ($results->isEmpty()) {
            return response()->json([
                'message' => 'No data found with this filter'
            ], 404);
        }
    
        return response()->json($results);
    }
    
    
}
