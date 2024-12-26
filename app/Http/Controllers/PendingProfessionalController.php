<?php

namespace App\Http\Controllers;

use App\Models\PendingProfessional;
use Illuminate\Http\Request;
use App\Models\Professional;
use App\Models\Work;
use Illuminate\Support\Facades\DB;

class PendingProfessionalController extends Controller
{
    /**
     * Delete a pending professional from the table.
     *
     * @param int $pending_prof_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($pending_prof_id)
    {
        // Find the pending professional by ID
        $pendingProfessional = PendingProfessional::findOrFail($pending_prof_id);

        // Delete the record
        $pendingProfessional->delete();

        // Redirect back with a success message
        return redirect()->back()->with('alert-success', 'Pending professional removed successfully.');
    }

    public function add(Request $request)
{
    $validatedData = $request->validate([
        'professional_id' => 'required|exists:professionals,professional_id',
        'work_id' => 'required|exists:work,work_id',
    ]);

    $loggedInUserId = auth()->id();
    $professionalId = $validatedData['professional_id'];
    $workId = $validatedData['work_id'];

    try {// Retrieve work
        $work = Work::find($workId);
        if (!$work) {
            return back()->with('alert-error', 'Work not found.');
        }

        $userId = $work->user_id;

        // Check if user is trying to add themselves
        $professional = Professional::where('professional_id', $professionalId)->first();
        if ($professional && $professional->user_id === $loggedInUserId) {
            return back()->with('alert-error', 'Cannot select yourself as a professional for the project.');
        }

        // Check for existing pending or rejected record
        $existingRecord = PendingProfessional::where('work_id', $workId)
            ->where('professional_id', $professionalId)
            ->first();

        if ($existingRecord) {
            if ($existingRecord->professional_status === 'pending') {
                return back()->with('alert-error', 'This professional is already pending for this project.');
            } elseif ($existingRecord->professional_status === 'rejected') {
                return back()->with('alert-error', 'This professional has previously rejected this project.');
            }
        }

        // If no existing record found, create new one
        PendingProfessional::create([
            'user_id' => $userId,
            'professional_id' => $professionalId,
            'work_id' => $workId,
            'professional_status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('alert-success', 'Professional added to pending list successfully');
    } catch (\Illuminate\Database\QueryException $e) {
            // Handle duplicate entry error
            if ($e->getCode() == 23000) { // SQLSTATE[23000]: Integrity constraint violation
                return back()->with('alert-error', 'This professional is already assigned to this project.');
            }

            // Handle other database errors
            return back()->with('alert-error', 'An unexpected database error occurred.');
        } catch (\Exception $e) {
            // Handle general exceptions
            return back()->with('alert-error', 'An unexpected error occurred.');
        }
    }
}