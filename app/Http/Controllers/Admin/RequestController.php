<?php 

namespace App\Http\Controllers\Admin;

use App\Models\VerifyRequest;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    // Accept verification request
    public function acceptVerification($verify_id)
    {
        try {
            // Find the verification request by ID
            $verifyRequest = VerifyRequest::findOrFail($verify_id);

            // Update the status to 'verified'
            $verifyRequest->status = 'verified';
            $verifyRequest->save();

            // Create notification for the user
            Notification::create([
                'user_id' => $verifyRequest->user_id,
                'title' => 'Account Verified',
                'message' => 'Your professional account request has been approved by Geoffryx. Welcome to Geoffryx professionals!',
                'status' => 'unread', // Status unread initially
            ]);

            // Flash success message for alert
            return redirect()->back()->with('alert-success', 'Request accepted and user notified.');
        } catch (\Exception $e) {
            // Flash error message if something goes wrong
            return redirect()->back()->with('alert-error', 'An error occurred while processing the request.');
        }
    }

    // Reject verification request
    public function rejectVerification(Request $request, $verify_id)
    {
        try {
            // Find the verification request by ID
            $verifyRequest = VerifyRequest::findOrFail($verify_id);

            // Update the status to 'rejected'
            $verifyRequest->status = 'rejected';
            $verifyRequest->save();

            // Get the rejection reason from the request
            $rejectionReason = $request->input('reason');

            // Create notification for the user with the rejection reason
            Notification::create([
                'user_id' => $verifyRequest->user_id,
                'title' => 'Verification Rejected',
                'message' => 'Sorry :( Your professional account request has been rejected by Geoffry. Reason: ' . $rejectionReason,
                'status' => 'unread', // Status unread initially
            ]);

            // Flash success message for alert
            return redirect()->back()->with('alert-success', 'Request rejected and user notified.');
        } catch (\Exception $e) {
            // Flash error message if something goes wrong
            return redirect()->back()->with('alert-error', 'An error occurred while processing the request.');
        }
    }
}
