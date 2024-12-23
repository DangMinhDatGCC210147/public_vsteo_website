<?php

namespace App\Http\Controllers;

use App\Models\UserSessions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    public function start(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            UserSessions::create([
                'user_id' => auth()->id(),
                'session_start' => now(),
                'session_end' => null
            ]);
            return response()->json(['message' => 'Session started']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to start session', 'error' => $e->getMessage()], 500);
        }
    }

    public function end(Request $request)
    {
        // Find the last session of this user and update the end time
        $session = UserSessions::where('user_id', auth()->id())->latest()->first();
        if ($session) {
            $session->update(['session_end' => now()]);
        }
        return response()->json(['message' => 'Session ended']);

    }
}
