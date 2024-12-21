<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RatingController extends Controller
{
    public function submitRatings(Request $request)
{
    $ratings = $request->input('ratings');
    $workId = $request->input('work_id');
    $userId = Work::where('work_id', $workId)->value('user_id'); // Get user ID from work table

    foreach ($ratings as $rating) {
        Rating::create([
            'professional_id' => $rating['professional_id'],
            'work_id' => $workId,
            'user_id' => $userId,
            'rate' => $rating['rate'],
            'comment' => $rating['comment'],
        ]);
    }

    return response()->json(['success' => true, 'message' => 'Ratings submitted successfully.']);
}
}