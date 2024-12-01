<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfilePicture;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProfilePictureController extends Controller
{
    /**
     * Store or update the profile picture.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Get the logged-in user's ID
        $userId = Auth::id();

        // Find the existing profile picture record for the user
        $profilePicture = ProfilePicture::where('user_id', $userId)->first();

        // If no profile picture record exists, create a new one
        if (!$profilePicture) {
            $profilePicture = new ProfilePicture();
            $profilePicture->user_id = $userId;
        }

        // If no file is uploaded and we want to remove the existing profile picture
        if (!$request->hasFile('profile_pic')) {
            if ($profilePicture->profile_pic) {
                // Delete the old image if it exists
                $this->deleteOldImage($profilePicture->profile_pic);
                // Remove profile picture from the database
                $profilePicture->profile_pic = null;
                $profilePicture->save();
            }
            // Redirect with success alert if the picture is removed
            return redirect()->back()->with('alert-success', 'Profile picture removed successfully.');
        }

        // Validate the uploaded file
        $request->validate([
            'profile_pic' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Handle the file upload
            $file = $request->file('profile_pic');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Optionally, delete the old image from storage if it exists
            if ($profilePicture->profile_pic) {
                $this->deleteOldImage($profilePicture->profile_pic);
            }

            // Store the new image
            $file->storeAs('images/profile_pic', $filename, 'public');  // Store it publicly

            // Update the profile_pic column in the database
            $profilePicture->profile_pic = $filename;
            $profilePicture->save();

            // Redirect with success alert
            return redirect()->back()->with('alert-success', 'Profile picture updated successfully.');
        } catch (\Exception $e) {
            // If an error occurs, return with an error message
            return redirect()->back()->with('alert-error', 'Failed to upload the profile picture. Please try again.');
        }
    }

    /**
     * Delete the old image from storage.
     *
     * @param string $filename
     * @return void
     */
    protected function deleteOldImage($filename)
    {
        $oldImagePath = storage_path('app/public/images/profile_pic/' . $filename);

        // Check if the old image exists and delete it
        if (File::exists($oldImagePath)) {
            File::delete($oldImagePath);
        }
    }
}
