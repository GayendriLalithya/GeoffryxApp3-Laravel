<!-- Main Content -->
<div class="top-bar">
    <div class="user-info">
        @if (Auth::user()->user_type === 'professional')
            <span class="verified-badge">Verified</span>
        @endif
        <div class="user-details">
            <p class="user-name">Hi {{ Auth::user()->name }}</p>
            <p class="user-role">{{ ucfirst(Auth::user()->user_type) }}</p>
        </div>
        <img src="{{ asset('resources/images/sample.png') }}" alt="Profile" width="45" height="45" class="rounded-circle">
    </div>
</div>
