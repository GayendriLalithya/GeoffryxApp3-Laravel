<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendingProfessional;
use App\Models\Referal;
use App\Models\Reference;
use App\Models\Notification;
use App\Models\Professional;
use App\Models\Work;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    public function referProfessional(Request $request)
    {
        \Log::info('Refer Professional Called', $request->all());

        $workId = $request->input('work_id');
        $selectedProfessionalId = $request->input('selected_professional_id');
        $referedProfId = $request->input('referred_professional_id'); // Get referred professional ID from input

        \Log::info('Work ID: ' . $workId);
        \Log::info('Selected Professional ID: ' . $selectedProfessionalId);
        \Log::info('Referred Professional ID: ' . $referedProfId);

        // Fetch the professionals
        $referredProf = Professional::find($referedProfId);
        $selectedProf = Professional::find($selectedProfessionalId);

        if (!$referredProf) {
            // return response()->json([
            //     'error' => "Referred Professional not found for ID: {$referedProfId}",
            // ], 404);
            return redirect()->back()->with('alert-error', "Referred Professional not found for ID: {$referedProfId}");
        }

        if (!$selectedProf) {
            // return response()->json([
            //     'error' => "Selected Professional not found for ID: {$selectedProfessionalId}",
            // ], 404);
            return redirect()->back()->with('alert-error', "Selected Professional not found for ID: {$selectedProfessionalId}");
        }

        // Validation: Check if ReferedProf is selecting themselves
        if ($referedProfId == $selectedProfessionalId) {
            // return response()->json([
            //     'error' => 'You cannot refer yourself.',
            // ], 400);
            return redirect()->back()->with('alert-error', 'You cannot refer yourself.');
        }

        // Validation
        if ($referredProf->type !== $selectedProf->type) {
            // return response()->json([
            //     'error' => 'You cannot refer a professional of a different type.',
            // ], 400);
            return redirect()->back()->with('alert-error', 'You cannot refer a professional of a different type.');
        }

        // Update the PendingProfessional record status for ReferedProf
        PendingProfessional::where('professional_id', $referedProfId)
            ->where('work_id', $workId)
            ->update(['professional_status' => 'Rejected']);

        // Create the Reference record
        $reference = Reference::create([
            'professional_id' => $selectedProfessionalId,
        ]);

        // Retrieve the ID of the newly created reference
        $referenceId = $reference->reference_id;

        // Create the Referral record
        Referal::create([
            'work_id' => $workId,
            'professional_id' => $referedProfId,
            'reference_id' => $referenceId,
            'status' => 'pending',
        ]);

        // Fetch the project owner user_id from the work table
        $work = Work::find($workId);
        if (!$work) {
            // return response()->json([
            //     'error' => "Work not found for ID: {$workId}",
            // ], 404);
            return redirect()->back()->with('alert-error', "Work not found for ID: {$workId}");
        }

        $projectOwnerId = $work->user_id;

        // Create a notification for the project owner
        Notification::create([
            'user_id' => $projectOwnerId,
            'title' => 'Project Request Referred',
            'message' => "{$referredProf->user->name} has referred {$selectedProf->user->name} for the project '{$work->name}'.",
            'status' => 'unread',
        ]);

        // return response()->json([
        //     'success' => 'Referral successfully created.',
        // ]);
        return redirect()->back()->with('alert-success', 'Referral successfully created.');
    }

    private function getProfessionalType($professionalId)
    {
        $professional = Professional::find($professionalId);

        if (!$professional) {
            // \Log::warning("Professional not found with ID: {$professionalId}");
            return null;
        }

        return [
            'id' => $professional->id,
            'type' => $professional->type,
            'name' => $professional->user->name ?? 'Unknown', // Ensure name is available
        ];
    }
}
