<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Referal;
use Illuminate\Support\Facades\DB;

class ReferController extends Controller
{
//     public function acceptReferral($id)
// {
//     try {
//         // Use the $id directly as the referral ID
//         $referralId = $id;

//         // Validate and process the referral
//         if ($referralId) {
//             DB::statement('CALL sp_accept_referral(?)', [$referralId]);
//             return redirect()->back()->with('alert-success', 'Referral accepted successfully.');
//         } else {
//             return redirect()->back()->with('alert-error', 'Referral ID is missing.');
//         }
//     } catch (\Exception $e) {
//         \Log::error('Referral acceptance failed: ' . $e->getMessage());
//         return redirect()->back()->with('alert-error', 'Failed to accept the referral.');
//     }
// }

public function acceptReferral($id)
{
    try {
        DB::statement('CALL sp_accept_referral(?)', [$id]);
        // return response()->json(['success' => true, 'message' => 'Referral accepted successfully.']);
        return redirect()->back()->with('alert-success', 'Referral accepted successfully.');
    } catch (\Exception $e) {
        // return response()->json(['success' => false, 'message' => 'Failed to accept the referral.']);
        return redirect()->back()->with('alert-error', 'Failed to accept the referral.');
    }
}

// public function rejectReferral($id)
// {
//     try {
//         DB::statement('CALL sp_reject_referral(?)', [$id]);
//         return response()->json(['success' => true, 'message' => 'Referral rejected successfully.']);
//     } catch (\Exception $e) {
//         return response()->json(['success' => false, 'message' => 'Failed to reject the referral.']);
//     }
// }




    public function reject($id)
    {
        try {
            // Find the referral record
            $referral = Referal::find($id);

            if (!$referral) {
                return redirect()->back()->with('alert-error', 'Referral not found.');
            }

            // Update referral status to rejected
            $referral->status = 'rejected';
            $referral->save();

            return redirect()->back()->with('alert-success', 'Referral rejected successfully.');
        } catch (\Exception $e) {
            \Log::error('Referral rejection failed: ' . $e->getMessage());
            return redirect()->back()->with('alert-error', 'An error occurred while rejecting the referral.');
        }
    }
}
