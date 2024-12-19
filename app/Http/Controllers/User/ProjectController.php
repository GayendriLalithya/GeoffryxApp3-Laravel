<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserProject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\TeamMember;
use App\Models\Team;

class ProjectController extends Controller
{
    public function index()
    {
        // Get all projects for the logged-in user
        $projects = UserProject::where('user_id', Auth::id())->get();

        return view('user.projects', compact('projects'));
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

        return view('pages.professional.manage_projects', compact('teams'));
    }
}
