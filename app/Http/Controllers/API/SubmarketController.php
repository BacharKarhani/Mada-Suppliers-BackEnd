<?php

namespace App\Http\Controllers\API;

use App\Models\Submarket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubmarketController extends Controller
{
    public function index()
    {
        // Eager load the 'market' and 'user' relationships
        $submarkets = Submarket::with(['market', 'user'])->get();

        // Return the response with the user's name included
        return response()->json([
            'message' => 'Submarkets retrieved successfully.',
            'data' => $submarkets->map(function ($submarket) {
                return [
                    'id' => $submarket->id,
                    'name' => $submarket->name,
                    'created_by' => $submarket->user ? $submarket->user->name : 'Unknown', // Display the user's name or 'Unknown'
                    'market' => [
                        'id' => $submarket->market->id,
                        'name' => $submarket->market->name,
                    ],
                    'market_id' => $submarket->market_id,
                    'created_at' => $submarket->created_at,
                    'updated_at' => $submarket->updated_at,
                ];
            })
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
