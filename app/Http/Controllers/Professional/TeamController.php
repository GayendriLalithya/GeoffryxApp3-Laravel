<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TeamMember;

class TeamController extends Controller
{
    // public function loadTeamMembers($workId)
    // {
    //     $userId = auth()->id(); // Get the logged-in user ID

    //     // Call the stored procedure
    //     $teamMembers = DB::select('CALL GetTeamMembersByWork(?, ?)', [$workId, $userId]);

    //     return response()->json([
    //         'teamMembers' => $teamMembers
    //     ]);
    // }

    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'team_id' => 'required|exists:team,team_id',
            'team_member_id' => 'required|exists:team_members,team_member_id',
            'professional_type' => 'nullable|string',
            'member_status' => 'required|in:not stated,in progress,halfway through,almost done,completed',
        ]);

        $teamMember = TeamMember::where('team_member_id', $validated['team_member_id'])
            ->where('user_id', $validated['user_id'])
            ->where('team_id', $validated['team_id'])
            ->first();

        if ($teamMember) {
            $teamMember->status = $validated['member_status'];
            $teamMember->save();

            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Team member not found.']);
    }

}
