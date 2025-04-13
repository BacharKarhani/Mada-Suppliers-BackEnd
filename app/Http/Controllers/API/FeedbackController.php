<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    // Create feedback
    public function store(Request $request)
    {
        $data = $request->validate([
            'contracters_or_suppliers_id' => 'required|exists:contracters_or_suppliers,id',
            'text' => 'required|string',
        ]);

        $feedback = Feedback::create([
            'contracters_or_suppliers_id' => $data['contracters_or_suppliers_id'],
            'user_id' => $request->user()->id,
            'text' => $data['text'],
        ]);

        return response()->json([
            'message' => 'Feedback submitted successfully.',
            'data' => $feedback
        ], 201);
    }

    // Get all feedback for a contractor or supplier
    public function getByContractor($id)
    {
        $feedbacks = Feedback::with(['user', 'contractorOrSupplier'])
            ->where('contracters_or_suppliers_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        $formatted = $feedbacks->map(function ($feedback) {
            return [
                'id' => $feedback->id,
                'text' => $feedback->text,
                'user_name' => $feedback->user->name ?? 'Unknown',
                'contractor_or_supplier_name' => $feedback->contractorOrSupplier->company_or_individual_name ?? 'N/A',
            ];
        });

        return response()->json([
            'data' => $formatted
        ]);
    }

    // Delete feedback (only for admin)
    public function destroy($id)
{
    $feedback = Feedback::find($id);

    if (!$feedback) {
        return response()->json(['error' => 'Feedback not found.'], 404);
    }

    $feedback->delete();

    return response()->json(['message' => 'Feedback deleted successfully.']);
}

}
