<!-- Edit Profile Info -->

<div class="card mb-3">

    <div class="card-header" data-bs-toggle="collapse" data-bs-target="#editProfile">
        Edit Profile Info
        <i class="fas fa-chevron-down"></i>
    </div>
    
    <div id="editProfile" class="collapse" data-bs-parent="#accountSettings">
    
        <div class="card-body">
    
            <p class="text-muted">Update your account's profile information and email address.</p>
    
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')
    
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
    
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" class="form-control" name="address" required>
                </div>
    
                <div class="mb-3">
                    <label class="form-label">Contact No</label>
                    <input type="tel" class="form-control" name="contact_no" required>
                </div>
    
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
    
                    @if (Auth::user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! Auth::user()->hasVerifiedEmail())
                        <div class="mt-2">
                            <p class="text-sm text-gray-800">
                                {{ __('Your email address is unverified.') }}
                                <button form="send-verification" class="underline text-sm text-gray-600">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>
    
                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 text-sm text-green-600">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
    
                <button type="submit" class="btn btn-teal">Save</button>
    
                @if (session('status') === 'profile-updated')
                    <p class="text-sm text-green-600 mt-2">
                        {{ __('Profile updated successfully.') }}
                    </p>
                @endif
            </form>
    
    
        </div>
    
    </div>

</div>