<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Attempt to authenticate the user
        if (Auth::attempt($request->only('email', 'password'))) {
            // If authentication is successful, regenerate the session and redirect to the dashboard
            $request->session()->regenerate();

            // Flash a success message
            session()->flash('alert-success', 'Login successful! Welcome back.');

            return redirect()->intended(RouteServiceProvider::DASHBOARD);
        }

        // Flash an error message if authentication fails
        session()->flash('alert-error', 'Invalid credentials. Please try again.');

        // Redirect back to the login page
        return back()->withInput($request->only('email'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Logout the user
        Auth::guard('web')->logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the CSRF token to prevent CSRF attacks
        $request->session()->regenerateToken();

        // Flash a success message that the user has logged out
        session()->flash('alert-success', 'You have been logged out successfully.');

        // Redirect to the home page
        return redirect('/');
    }
}
