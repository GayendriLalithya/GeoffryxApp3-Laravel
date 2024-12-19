<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Professional;
use App\Models\Notification;

class ReferRequestController extends Controller
{
    public function index(Request $request)
{
    // Retrieve the work ID from the query string
    $workId = $request->query('workId');

    // Fetch professionals (modify query as needed for filtering)
    $professionals = Professional::all(); // Adjust this to filter by requirements if needed

    // Set the tab variable
    $tab = 'professional';

    // Pass the data to the view
    return view('pages.customer.professional', compact('professionals', 'workId'));
}


    public function confirm(Request $request)
    {
        $referedProfId = Auth::user()->professional->professional_id; // Referring professional
        $selectedProfId = $request->input('selected_professional_id'); // Selected professional
        $workId = $request->input('work_id');

        // Prevent referring self
        if ($referedProfId == $selectedProfId) {
            return back()->with('error', 'You cannot refer yourself.');
        }

        try {
            // Call stored procedure to handle the referral
            DB::statement('CALL InsertReferral(?, ?, ?)', [$referedProfId, $selectedProfId, $workId]);

            // Notify the customer about the referral
            $customerId = DB::table('work')->where('work_id', $workId)->value('user_id');
            $referedProfName = Professional::where('professional_id', $referedProfId)->value('name');
            $selectedProfName = Professional::where('professional_id', $selectedProfId)->value('name');
            $projectName = DB::table('work')->where('work_id', $workId)->value('name');

            Notification::create([
                'user_id' => $customerId,
                'title' => 'Project Request Refered',
                'message' => "{$referedProfName} referred {$selectedProfName} for the {$projectName}.",
                'status' => 'unread',
            ]);

            return redirect()->route('notifications.index')->with('success', 'Professional referred successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
