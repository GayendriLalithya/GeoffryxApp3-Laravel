<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public function loadTeamMembers($workId)
    {
        $userId = auth()->id(); // Get the logged-in user ID

        // Call the stored procedure
        $teamMembers = DB::select('CALL GetTeamMembersByWork(?, ?)', [$workId, $userId]);

        return response()->json([
            'teamMembers' => $teamMembers
        ]);
    }

    public function updateTeamMemberStatus(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'team_id' => 'required|integer',
            'status' => 'required|string',
        ]);
    
        DB::table('team_members')
            ->where('user_id', $validated['user_id'])
            ->where('team_id', $validated['team_id'])
            ->update(['status' => $validated['status']]);
    
        return response()->json(['message' => 'Status updated successfully.']);
    }

}
