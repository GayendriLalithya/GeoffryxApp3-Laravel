<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserProject;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        // Get all projects for the logged-in user
        $projects = UserProject::where('user_id', Auth::id())->get();

        return view('user.projects', compact('projects'));
    }
}
