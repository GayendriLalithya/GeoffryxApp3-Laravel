<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        // If the user's email is already verified, show an info alert
        if ($request->user()->hasVerifiedEmail()) {
            session()->flash('alert-info', 'Your email has already been verified.');
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        // Send the email verification notification
        $request->user()->sendEmailVerificationNotification();

        // Flash a success alert to inform the user that the verification link was sent
        session()->flash('alert-success', 'A new verification link has been sent to your email.');

        return back();
    }
}
