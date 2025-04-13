<?php

namespace App\Http\Controllers\API;

use App\Models\Submarket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubmarketController extends Controller
{
    public function index()
    {
        $submarkets = Submarket::with('market')->get();

        return response()->json([
            'message' => 'Submarkets retrieved successfully.',
            'data' => $submarkets
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
}
