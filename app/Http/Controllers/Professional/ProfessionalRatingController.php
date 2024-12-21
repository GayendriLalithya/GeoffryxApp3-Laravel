<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\TeamMember;
use App\Models\Rating;
use App\Models\Professional;
use Illuminate\Support\Facades\DB;

class ProfessionalRatingController extends Controller
{
    public function showRatingPage($work_id)
    {
        // Fetch the work details
        $work = Work::findOrFail($work_id);

        // Fetch team members associated with the work
        $teamMembers = TeamMember::with('user.professional')
            ->whereHas('team', function ($query) use ($work_id) {
                $query->where('work_id', $work_id);
            })
            ->get();

        return view('rating.rate-professionals', compact('work', 'teamMembers'));
    }

    public function submitRating(Request $request)
    {
        $request->validate([
            'team_member_id' => 'required|exists:team_members,team_member_id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Save the rating
        $teamMember = TeamMember::findOrFail($request->team_member_id);
        $teamMember->rating = $request->rating;
        $teamMember->save();

        return redirect()->back()->with('alert-success', 'Rating submitted successfully!');
    }
}
