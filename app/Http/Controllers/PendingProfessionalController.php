<?php

namespace App\Http\Controllers;

use App\Models\PendingProfessional;
use Illuminate\Http\Request;

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
        return redirect()->back()->with('success', 'Pending professional removed successfully.');
    }
}
