<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectRequestController extends Controller
{
    public function index()
    {
        // Step 1: Get logged-in user ID
        $userId = Auth::id();

        // Step 2: Get the professional ID from the professionals table
        $professional = DB::table('professionals')
            ->where('user_id', $userId)
            ->select('professional_id')
            ->first();

        if (!$professional) {
            return redirect()->back()->with('alert-error', 'Professional profile not found.');
        }

        // Step 3: Get pending work IDs from the pending_professional table
        $pendingWorks = DB::table('pending_professional')
            ->where('professional_id', $professional->professional_id)
            ->where('professional_status', 'pending')
            ->pluck('work_id');

        // Step 4: Get project details from the view_user_projects view using the work IDs
        $projects = DB::table('view_user_projects')
            ->whereIn('work_id', $pendingWorks)
            ->orderBy('created_at', 'desc')
            ->get();

        // Return view with projects
        return view('pages.professional.project_request', compact('projects'));
    }
}
