<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Work;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RatingController extends Controller
{
    public function submitRatings(Request $request)
{
    $validated = $request->validate([
        'work_id' => 'required|exists:work,work_id',
        'ratings' => 'required|array',
        'ratings.*.professional_id' => 'required|exists:professionals,professional_id',
        'ratings.*.rate' => 'required|in:1,2,3,4,5',
        'ratings.*.comment' => 'nullable|string',
    ]);

    DB::statement('CALL SubmitRatings(?, ?, ?)', [
        $validated['work_id'],
        auth()->user()->user_id, // Assuming authenticated user
        json_encode($validated['ratings']),
    ]);

    return redirect()->back()->with('success', 'Ratings submitted successfully!');
}
}