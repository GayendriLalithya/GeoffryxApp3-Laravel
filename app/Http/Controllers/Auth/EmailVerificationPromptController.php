<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        // Check if the user's email is already verified
        if ($request->user()->hasVerifiedEmail()) {
            // Flash an info alert if email is already verified
            session()->flash('alert-info', 'Your email is already verified. You can proceed.');
            return redirect()->intended(RouteServiceProvider::HOME); // Redirect to intended route (e.g., home)
        }

        // If the email is not verified, show the verification page
        return view('auth.verify-email'); // Return the email verification view
    }
}
