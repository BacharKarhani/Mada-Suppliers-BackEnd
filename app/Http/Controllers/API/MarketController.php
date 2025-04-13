<?php

namespace App\Http\Controllers\API;

use App\Models\Market;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MarketController extends Controller
{
    public function index()
    {
        $markets = Market::all();
        return response()->json([
            'message' => 'Markets retrieved successfully.',
            'data' => $markets
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
}
