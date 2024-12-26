<?php

namespace App\Http\Controllers\Admin;

use App\Models\VerifyRequest;
use App\Models\Notification;
use App\Models\Professional;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RequestController extends Controller
{
    public function acceptVerification($verify_id)
    {
        try {
            // Call the stored procedure
            DB::statement('CALL AcceptVerification(?)', [$verify_id]);

            // Flash success message for alert
            return redirect()->back()->with('alert-success', 'Request accepted, user notified, and added to professionals.');
        } catch (\Exception $e) {
            // Flash error message if something goes wrong
            return redirect()->back()->with('alert-error', 'An error occurred while processing the request: ' . $e->getMessage());
        }
    }

    // Reject verification request
    public function rejectVerification(Request $request, $verify_id)
    {
        try {
            // Debugging: Log incoming data
            Log::info('Reject request received', $request->all());

            // Validate the request
            $request->validate([
                'reason' => 'required|string|max:255', // Ensure reason is present
            ]);

            // Find the verification request by ID
            $verifyRequest = VerifyRequest::findOrFail($verify_id);

            // Update the status to 'rejected'
            $verifyRequest->status = 'rejected';
            $verifyRequest->save();

            // Save the rejection reason to the database or notifications
            Notification::create([
                'user_id' => $verifyRequest->user_id,
                'title' => 'Verification Rejected',
                'message' => 'Your professional account request has been rejected. Reason: ' . $request->input('reason'),
                'status' => 'unread',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // return response()->json(['status' => 'success', 'message' => 'Request rejected and user notified.']);
            return redirect()->back()->with('alert-success', 'Request rejected and user notified.');
        } catch (\Exception $e) {
            Log::error('Error rejecting verification: ' . $e->getMessage());
            // return response()->json(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
            return redirect()->back()->with('alert-error', 'An error occurred: ' . $e->getMessage());
        }
    }


}