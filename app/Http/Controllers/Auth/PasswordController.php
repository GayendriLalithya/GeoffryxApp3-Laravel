<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        // Validate the password update request
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // If validation fails, an error message will be flashed automatically

        // Update the user's password
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Flash a success message
        return back()->with('alert-success', 'Your password has been updated successfully.');
    }
}
