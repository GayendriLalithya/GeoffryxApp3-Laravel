<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the password using the provided email and password
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            // Flash an error message if the password is incorrect
            session()->flash('alert-error', 'Incorrect password. Please try again.');

            // Redirect back to the confirm password page
            return back();
        }

        // Store the time of password confirmation
        $request->session()->put('auth.password_confirmed_at', time());

        // Flash a success message that the password was confirmed
        session()->flash('alert-success', 'Password confirmed successfully!');

        // Redirect to the intended route
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
