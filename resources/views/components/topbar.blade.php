<!-- Main Content -->
<div class="top-bar">
    <div class="user-info">

        @php
            // Get the logged-in user's ID
            $userId = auth()->id();

            $unreadNotificationsCount = \App\Models\Notification::where('user_id', $userId)
            ->where('status', 'unread')
            ->count();
        @endphp

        <a href="{{ route('user.dashboard', ['tab' => 'notification']) }}" class="notification-icon">
            <i class="fas fa-bell"></i>
            @if($unreadNotificationsCount > 0)
                <span class="notification-badge" id="notificationBadge">{{ $unreadNotificationsCount }}</span>
            @endif
        </a>

        @if (Auth::user()->user_type === 'professional')
            <span class="verified-badge">Verified</span>
        @endif
        
        <div class="user-details">
            <p class="user-name">Hi {{ Auth::user()->name }}</p>
            <p class="user-role">{{ ucfirst(Auth::user()->user_type) }}</p>
        </div>

        
        @if(Auth::user()->profilePicture && Auth::user()->profilePicture->profile_pic)
            <img src="{{ asset('storage/app/public/images/profile_pic/' . Auth::user()->profilePicture->profile_pic) }}" alt="Profile Picture" width="45" height="45" class="rounded-circle profile-pic">
        @else
            <img src="{{ asset('resources/images/sample.png') }}" alt="Default Profile Picture" width="45" height="45" class="rounded-circle">
        @endif

    </div>
</div>

