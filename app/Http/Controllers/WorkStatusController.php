<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\TeamMember;
use App\Models\Team;

class WorkStatusController extends Controller
{
    /**
     * Recalculate and update the work status for a specific work ID.
     */
    public function updateWorkStatus($workId)
    {
        // Find the work record
        $work = Work::find($workId);
        if (!$work) {
            return response()->json(['alert-error' => 'Work not found'], 404);
        }

        // Get the associated team(s) for this work
        $teams = Team::where('work_id', $workId)->pluck('team_id');

        // Calculate total team members, completed, and not started members
        $totalMembers = TeamMember::whereIn('team_id', $teams)->count();
        $completedMembers = TeamMember::whereIn('team_id', $teams)->where('status', 'completed')->count();
        $notStartedMembers = TeamMember::whereIn('team_id', $teams)->where('status', 'not started')->count();

        // Determine the work status
        if ($totalMembers === $completedMembers) {
            $work->status = 'completed';
        } elseif ($totalMembers === $notStartedMembers) {
            $work->status = 'not started';
        } else {
            $work->status = 'in progress';
        }

        // Save the updated status
        $work->save();

        return response()->json(['message' => 'Work status updated successfully', 'status' => $work->status]);
    }
}
