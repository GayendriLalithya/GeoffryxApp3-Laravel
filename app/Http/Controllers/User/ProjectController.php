<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserProject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PendingProfessional;
use App\Models\TeamMember;
use App\Models\Professional;
use App\Models\Team;

class ProjectController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); // Get the logged-in user's ID

        // Fetch all projects created by the logged-in user
        $projects = UserProject::where('user_id', $userId)->get();

        // Fetch team information based on work_id for these projects
        $teams = [];
        foreach ($projects as $project) {
            $team = Team::with(['work.client'])
                        ->where('work_id', $project->work_id)
                        ->first(); // Assuming one team per work_id
            if ($team) {
                $teams[] = $team;
            }
        }

        return view('user.projects', compact('projects', 'teams'));
    }

    public function manageProjects()
    {
        $userId = Auth::id(); // Get the logged-in user's ID
    
        // Fetch team IDs where the user is a member
        $teamIds = TeamMember::where('user_id', $userId)->pluck('team_id');
    
        // Fetch teams and related work records
        $teams = Team::with(['work.client']) // Eager load both work and its client
                     ->whereIn('team_id', $teamIds)
                     ->get();
    
        // Fetch professionals for each work ID
        foreach ($teams as $team) {
            if ($team->work) {
                $workId = $team->work->work_id;
                $team->work->professionals = DB::table('pending_professional')
                    ->join('users', 'pending_professional.user_id', '=', 'users.user_id')
                    ->join('professionals', 'pending_professional.professional_id', '=', 'professionals.professional_id')
                    ->where('pending_professional.work_id', $workId)
                    ->select(
                        'users.name as professional_name',
                        'professionals.type as professional_type',
                        'pending_professional.professional_status'
                    )
                    ->get();
            }
        }
    
        return view('pages.professional.manage_projects', compact('teams'));
    }
}
