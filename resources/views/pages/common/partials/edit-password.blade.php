<!-- Update Password -->

<div class="card mb-3">

<div class="card-header" data-bs-toggle="collapse" data-bs-target="#updatePassword">
    Update Password
    <i class="fas fa-chevron-down"></i>
</div>

<div id="updatePassword" class="collapse" data-bs-parent="#accountSettings">

    <div class="card-body">

        <p class="text-muted">Ensure your account is using a long random password to stay secure.</p>

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="mb-3">
                <label class="form-label">Current Password</label>

                <div class="password-field">
                    <input id="update_password_current_password" name="current_password" type="password" class="form-control">
                    <span class="password-toggle">
                        <i class="far fa-eye"></i>
                    </span>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">New Password</label>

                <div class="password-field">
                    <input id="update_password_password" name="password" type="password" class="form-control">
                    <span class="password-toggle">
                        <i class="far fa-eye"></i>
                    </span>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>

                <div class="password-field">
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control">
                    <span class="password-toggle">
                        <i class="far fa-eye"></i>
                    </span>
                </div>
            </div>
            
            <button class="btn btn-teal">Save</button>

            @if (session('status') === 'password-updated')
                <div id="notification" class="notification success">
                    {{ __('Saved.') }}
                </div>
            @endif
            
        </form>

    </div>

</div>

</div>
