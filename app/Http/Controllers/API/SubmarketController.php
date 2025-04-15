<?php

namespace App\Http\Controllers\API;

use App\Models\Submarket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubmarketController extends Controller
{
    public function index(Request $request)
{
    // Check if pagination is disabled via query param (e.g., ?paginate=false)
    $shouldPaginate = filter_var($request->query('paginate', true), FILTER_VALIDATE_BOOLEAN);

    if ($shouldPaginate) {
        $perPage = 5;
        $submarkets = Submarket::with(['market', 'user'])->paginate($perPage);
        $data = $submarkets->getCollection();
    } else {
        $submarkets = Submarket::with(['market', 'user'])->get();
        $data = $submarkets;
    }

    // Format the data
    $formattedData = $data->map(function ($submarket) {
        return [
            'id' => $submarket->id,
            'name' => $submarket->name,
            'created_by' => $submarket->user ? $submarket->user->name : 'Unknown',
            'market' => [
                'id' => $submarket->market->id,
                'name' => $submarket->market->name,
            ],
            'market_id' => $submarket->market_id,
            'created_at' => $submarket->created_at,
            'updated_at' => $submarket->updated_at,
        ];
    });

    // If pagination is disabled, no pagination meta is returned
    if (!$shouldPaginate) {
        return response()->json([
            'message' => 'Submarkets retrieved successfully.',
            'data' => $formattedData,
        ]);
    }

    // If pagination is enabled, include pagination meta
    return response()->json([
        'message' => 'Submarkets retrieved successfully.',
        'data' => $formattedData,
        'current_page' => $submarkets->currentPage(),
        'last_page' => $submarkets->lastPage(),
        'per_page' => $submarkets->perPage(),
        'total' => $submarkets->total(),
    ]);
}


    public function show($id) 
    {
        // Find the submarket by its ID
        $submarket = Submarket::with(['market', 'user'])->findOrFail($id);

        // Return the submarket data as a response
        return response()->json([
            'message' => 'Submarket retrieved successfully.',
            'data' => [
                'id' => $submarket->id,
                'name' => $submarket->name,
                'created_by' => $submarket->user ? $submarket->user->name : 'Unknown',
                'market' => [
                    'id' => $submarket->market->id,
                    'name' => $submarket->market->name,
                ],
                'market_id' => $submarket->market_id,
                'created_at' => $submarket->created_at,
                'updated_at' => $submarket->updated_at,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'market_id' => 'required|exists:market,id'
        ]);

        $submarket = Submarket::create([
            'name' => $request->name,
            'market_id' => $request->market_id
        ]);

        return response()->json([
            'message' => 'Submarket created successfully.',
            'data' => $submarket
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $submarket = Submarket::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'market_id' => 'required|exists:market,id'
        ]);

        $submarket->update([
            'name' => $request->name,
            'market_id' => $request->market_id
        ]);

        return response()->json([
            'message' => 'Submarket updated successfully.',
            'data' => $submarket
        ]);
    }

    public function destroy($id)
    {
        $submarket = Submarket::findOrFail($id);
        $submarket->delete();

        return response()->json([
            'message' => 'Submarket deleted successfully.'
        ]);
    }

    public function storeByUser(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string',
            'market_id' => 'required|exists:market,id', // Change this from 'markets' to 'market'
        ]);
    
        // Get the authenticated user
        $user = $request->user();
    
        // Create the submarket associated with the authenticated user
        $submarket = Submarket::create([
            'name' => $request->name,
            'market_id' => $request->market_id,
            'created_by' => $user->id, // Use the authenticated user's ID to set `created_by`
        ]);
    
        // Return the response with the created submarket data
        return response()->json([
            'message' => 'Submarket created by user.',
            'data' => $submarket
        ]);
    }
}
