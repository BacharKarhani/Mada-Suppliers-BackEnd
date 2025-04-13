<?php

namespace App\Http\Controllers\API;

use App\Models\Market;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MarketController extends Controller
{
    public function index()
    {
        // Eager load the 'user' relationship
        $markets = Market::with('user')->get();

        // Return the response with the user's name included
        return response()->json([
            'message' => 'Markets retrieved successfully.',
            'data' => $markets->map(function ($market) {
                return [
                    'id' => $market->id,
                    'name' => $market->name,
                    'created_by' => $market->user ? $market->user->name : 'Unknown', // Show the user name or 'Unknown'
                    'created_at' => $market->created_at,
                    'updated_at' => $market->updated_at,
                ];
            })
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $market = Market::create([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Market created successfully.',
            'data' => $market
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $market = Market::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $market->update([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Market updated successfully.',
            'data' => $market
        ]);
    }

    public function destroy($id)
    {
        $market = Market::findOrFail($id);
        $market->delete();

        return response()->json([
            'message' => 'Market deleted successfully.'
        ]);
    }


    public function storeByUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $user = $request->user(); // Get user from token

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $market = Market::create([
            'name' => $request->name,
            'created_by' => $user->id,  // Assign created_by from the authenticated user
        ]);

        return response()->json([
            'message' => 'Market created by user.',
            'data' => $market
        ]);
    }
}
