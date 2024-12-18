<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

    public function acceptWork(Request $request)
    {
        $userId = Auth::id(); // Get the logged-in user ID
        $workId = $request->input('work_id');

        try {
            // Call the stored procedure
            DB::statement('CALL AcceptWorkAndAddToTeam(?, ?)', [$workId, $userId]);

            // Redirect back with a success alert
            return redirect()->route('user.dashboard', ['tab' => 'manage_projects'])
                             ->with('alert-success', 'Work accepted and added to the team successfully.');
        } catch (Exception $e) {
            // Log the exception for debugging
            Log::error('Error accepting work: ' . $e->getMessage());

            // Redirect back with an error alert
            return redirect()->back()->with('alert-error', 'An unexpected error occurred while processing the request. Please try again.');
        }
    }
}
