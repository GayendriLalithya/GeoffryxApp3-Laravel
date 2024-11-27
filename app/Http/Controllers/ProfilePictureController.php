<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfilePicture;
use Illuminate\Support\Facades\Auth;

class ProfilePictureController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'profile_pic' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Get the logged-in user's ID
        $userId = Auth::id();

        // Find the existing profile picture record for the user
        $profilePicture = ProfilePicture::where('user_id', $userId)->first();

        if ($profilePicture) {
            // Handle file upload
            if ($request->hasFile('profile_pic')) {
                $file = $request->file('profile_pic');
                $filename = time() . '_' . $file->getClientOriginalName();

                // Save the file to the resources/images/profile_pic directory
                $file->storeAs('images/profile_pic', $filename, 'resources');

                // Update the profile_pic column in the database
                $profilePicture->update(['profile_pic' => $filename]);
            }

            return redirect()->back()->with('success', 'Profile picture updated successfully.');
        }

        return redirect()->back()->with('error', 'Profile picture record not found.');
    }
}
