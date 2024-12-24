<?php

namespace App\Http\Controllers;

use App\Models\MemberTask;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MemberTaskController extends Controller
{
    /**
     * Store a new task for a team member.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'team_member_id' => 'required|exists:team_members,team_member_id',
            'team_id' => 'required|exists:team,team_id',
        ]);

        MemberTask::create([
            'description' => $validated['description'],
            'status' => 'not started',
            'team_member_id' => $validated['team_member_id'],
            'team_id' => $validated['team_id'],
        ]);

        // Update the associated work status
        $this->updateWorkStatusByTeamMember($validated['team_member_id']);

        return back()->with('alert-success', 'Task added successfully.');
    }

    /**
     * Update an existing task's status.
     */
    public function update(Request $request, $member_task_id)
    {
        $task = MemberTask::where('member_task_id', $member_task_id)->firstOrFail();

        $validated = $request->validate([
            'status' => 'required|in:not started,in progress,done',
        ]);

        $task->update(['status' => $validated['status']]);

        // Update the associated work status
        $this->updateWorkStatusByTeamMember($task->team_member_id);

        return back()->with('alert-success', 'Task status updated.');
    }

    /**
     * Delete a specific task.
     */
    public function destroy($member_task_id)
    {
        $task = MemberTask::where('member_task_id', $member_task_id)->firstOrFail();
        $teamMemberId = $task->team_member_id;

        $task->delete();

        // Update the associated work status
        $this->updateWorkStatusByTeamMember($teamMemberId);

        return back()->with('alert-success', 'Task deleted successfully.');
    }

    /**
     * Update the work status based on team member tasks and statuses.
     */
    private function updateWorkStatusByTeamMember($teamMemberId)
    {
        // Get the team ID and work ID for the team member
        $team = TeamMember::where('team_member_id', $teamMemberId)->firstOrFail()->team;
        $workId = $team->work_id;

        // Update the team member's status based on their tasks
        $totalTasks = MemberTask::where('team_member_id', $teamMemberId)->count();
        $completedTasks = MemberTask::where('team_member_id', $teamMemberId)->where('status', 'done')->count();
        $notStartedTasks = MemberTask::where('team_member_id', $teamMemberId)->where('status', 'not started')->count();

        if ($totalTasks === $completedTasks) {
            TeamMember::where('team_member_id', $teamMemberId)->update(['status' => 'completed']);
        } elseif ($totalTasks === $notStartedTasks) {
            TeamMember::where('team_member_id', $teamMemberId)->update(['status' => 'not started']);
        } else {
            TeamMember::where('team_member_id', $teamMemberId)->update(['status' => 'in progress']);
        }

        // Update the work status based on the team members' statuses
        $totalMembers = TeamMember::where('team_id', $team->team_id)->count();
        $completedMembers = TeamMember::where('team_id', $team->team_id)->where('status', 'completed')->count();
        $notStartedMembers = TeamMember::where('team_id', $team->team_id)->where('status', 'not started')->count();

        if ($totalMembers === $completedMembers) {
            Work::where('work_id', $workId)->update(['status' => 'completed']);
        } elseif ($totalMembers === $notStartedMembers) {
            Work::where('work_id', $workId)->update(['status' => 'not started']);
        } else {
            Work::where('work_id', $workId)->update(['status' => 'in progress']);
        }
    }

    /**
     * Get all teams with their members for the logged-in user.
     */
    public function getTeamsWithMembers()
    {
        $userId = Auth::id();

        // Fetch teams where the user is a member
        $teams = Team::with('work.client')->whereHas('teamMembers', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();

        // Fetch team members via stored procedure
        $teamMembersByTeamId = [];
        foreach ($teams as $team) {
            $teamMembersByTeamId[$team->team_id] = DB::select('CALL GetTeamMembersByTeamId(?)', [$team->team_id]);
        }

        return view('team-modal', compact('teams', 'teamMembersByTeamId'));
    }
}
