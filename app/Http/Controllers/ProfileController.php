<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;

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

        // Update the user's profile
        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'contact_no' => $validatedData['contact_no'] ?? $user->contact_no,
            'address' => $validatedData['address'] ?? $user->address
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}