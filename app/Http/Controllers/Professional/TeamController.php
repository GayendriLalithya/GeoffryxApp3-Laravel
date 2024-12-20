<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TeamMember;
use App\Models\Work;
use App\Models\Team;

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

    private function updateWorkStatus($teamId)
    {
        // Get the associated work ID from the team
        $team = Team::find($teamId);
        if (!$team) {
            return; // Team not found, exit
        }
    
        $workId = $team->work_id;
    
        // Calculate member counts
        $totalMembers = TeamMember::where('team_id', $teamId)->count();
        $completedMembers = TeamMember::where('team_id', $teamId)->where('status', 'completed')->count();
        $notStartedMembers = TeamMember::where('team_id', $teamId)->where('status', 'not started')->count();
    
        // Determine the new work status
        $workStatus = 'in progress'; // Default to in progress
        if ($totalMembers === $completedMembers) {
            $workStatus = 'completed';
        } elseif ($totalMembers === $notStartedMembers) {
            $workStatus = 'not started';
        }
    
        // Update the work status
        $work = Work::find($workId);
        if ($work) {
            $work->status = $workStatus;
            $work->save();
        }
    }


    public function updateStatus(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'team_member_id' => 'required|exists:team_members,team_member_id',
                'status' => 'required|in:not started,in progress,halfway through,almost done,completed',
            ]);
        
            // Find the team member
            $teamMember = TeamMember::find($request->team_member_id);
        
            if (!$teamMember) {
                session()->flash('alert-error', 'Team member not found.');
                return back();
            }
        
            // Update the team member's status
            $teamMember->status = $request->status;
            $teamMember->save();
        
            // Update the work status for the associated team
            $teamId = $teamMember->team_id;
            $this->updateWorkStatus($teamId); // Call the helper method
        
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
