<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Get a paginated list of users (5 per page)
     */
    public function index(Request $request)
    {
        $users = User::orderBy('created_at', 'desc')->paginate(5);
        return response()->json($users);
    }

    /**
     * Toggle user status between active/off
     */
    public function toggleStatus($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $user->status = $user->status === 'active' ? 'off' : 'active';
        $user->save();

        return response()->json([
            'message' => 'User status updated successfully.',
            'user' => $user
        ]);
    }

    /**
     * Accept a user (set status to active)
     */
    public function accept($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ($user->status === 'active') {
            return response()->json(['message' => 'User is already active.'], 400);
        }

        $user->status = 'active';
        $user->save();

        return response()->json([
            'message' => 'User accepted successfully.',
            'user' => $user
        ]);
    }

    /**
     * Decline a user (delete if status is inactive)
     */
    public function decline($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ($user->status === 'active') {
            return response()->json(['message' => 'Cannot decline an active user.'], 400);
        }

        $user->delete();

        return response()->json(['message' => 'User declined and deleted successfully.']);
    }

    public function inactiveUsers()
    {
        $users = User::where('status', 'inactive')->paginate(5);
        return response()->json($users);
    }
}
