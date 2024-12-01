<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    /**
     * Show the profile edit page
     */
    public function edit()
    {
        $user = Auth::user(); // Get the currently authenticated user
        return view('profile.edit', ['user' => $user]);
    }

    /**
     * Update the user's profile information
     */
    public function update(Request $request)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'email', 
                'max:255', 
                // Ensure email is unique, but exclude the current user's email
                Rule::unique('users', 'email')->ignore($user->user_id, 'user_id')
            ],
            'contact_no' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string']
        ]);

        try {
            // Update the user's profile
            $user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'contact_no' => $validatedData['contact_no'] ?? $user->contact_no,
                'address' => $validatedData['address'] ?? $user->address
            ]);

            // Flash a success message
            return redirect()->back()->with('alert-success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            // Flash an error message if something goes wrong
            return redirect()->back()->with('alert-error', 'Failed to update profile. Please try again.');
        }
    }

    /**
     * Mark the user's account as deleted (soft delete).
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Check if the user is already deleted
        if ($user->deleted) {
            return redirect()->back()->with('alert-info', 'Your account is already deleted.');
        }

        try {
            // Mark the user as deleted (soft delete)
            $user->update([
                'deleted' => true
            ]);

            // Log the user out
            Auth::logout();

            // Invalidate the session and regenerate the token
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirect to home page or login page after account deletion
            return redirect('/')->with('alert-success', 'Your account has been successfully deleted.');
        } catch (\Exception $e) {
            // In case of failure, flash an error message
            return redirect()->back()->with('alert-error', 'There was an error deleting your account. Please try again.');
        }
    }
}
