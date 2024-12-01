<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\ProfilePicture;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
{
    // Validate the incoming request
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'contact_no' => ['required', 'string', 'max:15'],
        'address' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'user_type' => ['required', 'string', 'in:professional,customer,admin'],
    ]);

    try {
        // Create the user record
        $user = User::create([
            'name' => $request->name,
            'contact_no' => $request->contact_no,
            'address' => $request->address,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'deleted' => false, // Set deleted to false by default
        ]);

        // Create profile picture record with null value
        ProfilePicture::create([
            'user_id' => $user->user_id, // Corrected to 'id' field
            'profile_pic' => null,  // Default value is null
        ]);

        // Trigger registration event
        event(new Registered($user));

        // Log the user in
        Auth::login($user);

        // Flash success message to session
        session()->flash('alert-success', 'Registration successful! Welcome aboard.');

        // Redirect to the dashboard after successful registration
        return redirect(RouteServiceProvider::DASHBOARD);

    } catch (\Exception $e) {
        // If any error occurs, flash an error message
        session()->flash('alert-error', 'An error occurred while registering. Please try again.');

        // Return back to the registration form
        return back()->withInput();
    }
}

}
