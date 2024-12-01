<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    /**
     * Handle the incoming login request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            // Authenticate the user using the LoginRequest logic
            $request->authenticate();

            // Redirect with a success alert after successful login
            return redirect()->intended('/dashboard')->with('alert-success', 'Login successful!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Check for specific validation errors (e.g., account deletion or lockout)
            $errors = $e->errors();
            
            if (isset($errors['email']) && $errors['email'][0] == 'Your account has been deleted.') {
                // Alert for deleted account
                return back()->with('alert-error', 'Your account has been deleted.');
            }

            // Alert for invalid credentials or general errors
            return back()->withInput($request->only('email', 'remember'))
                         ->with('alert-error', 'Invalid credentials or too many login attempts.');
        }
    }
}
