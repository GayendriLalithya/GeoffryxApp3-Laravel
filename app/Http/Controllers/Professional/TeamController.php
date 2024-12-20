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
        try {
            // Validate the request
            $request->validate([
                'team_member_id' => 'required|exists:team_members,team_member_id',
                'status' => 'required|in:not stated,in progress,halfway through,almost done,completed',
            ]);

            // Find the team member
            $teamMember = TeamMember::find($request->team_member_id);

            if (!$teamMember) {
                session()->flash('alert-error', 'Team member not found.');
                return back();
            }

            // Update the status
            $teamMember->status = $request->status;
            $teamMember->save();

            // Success message
            session()->flash('alert-success', 'Status updated successfully!');
            return back();
        } catch (\Exception $e) {
            // Error message
            session()->flash('alert-error', 'An error occurred while updating the status. Please try again.');
            return back();
        }
    }


}
