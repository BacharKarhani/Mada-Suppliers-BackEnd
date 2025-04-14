<?php

namespace App\Http\Controllers\API;

use App\Models\Market;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MarketController extends Controller
{
public function index(Request $request)
    {
        // Default values for pagination
        $perPage = $request->input('limit', 5); // Default to 6 items per page if not provided
        $page = $request->input('page', 1); // Default to the first page if not provided

        // Eager load the 'user' relationship and paginate the results
        $markets = Market::with('user')
            ->paginate($perPage, ['*'], 'page', $page);

        // Return the response with pagination data
        return response()->json([
            'message' => 'Markets retrieved successfully.',
            'data' => $markets->map(function ($market) {
                return [
                    'id' => $market->id,
                    'name' => $market->name,
                    'created_by' => $market->user ? $market->user->name : 'Unknown',
                    'created_at' => $market->created_at,
                    'updated_at' => $market->updated_at,
                ];
            }),
            'pagination' => [
                'current_page' => $markets->currentPage(),
                'total_pages' => $markets->lastPage(),
                'total_items' => $markets->total(),
            ]
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
