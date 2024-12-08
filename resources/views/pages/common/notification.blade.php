@section('additional-css')
    <link rel="stylesheet" href="{{ asset('resources/css/notification.css') }}">
@endsection

@php
        // Get the logged-in user's ID
        $userId = auth()->id();

        // Fetch notifications for the user
        $notifications = \App\Models\Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    @endphp

<div class="container mt-4">
    <div class="filters">
        <button class="filter-btn active" data-filter="all">All</button>
        <button class="filter-btn" data-filter="unread">Unread</button>
    </div>

    <div class="notifications-container">
        @foreach ($notifications as $notification)
            <div class="notification-item {{ $notification->status }}">
                <div class="notification-title">
                    <h5>{{ $notification->title }}</h5>
                </div>
                <div class="notification-message">
                    <p>{{ $notification->message }}</p>
                </div>
                <div class="notification-footer">
                    <small>{{ $notification->created_at->diffForHumans() }}</small>
                    @if($notification->status == 'unread')
                        <button class="mark-read-btn" data-id="{{ $notification->notification_id }}">Mark as Read</button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
