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
            <div class="notification-item {{ $notification->status }} {{ $notification->status == 'unread' ? 'unread' : '' }}">
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

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    // JavaScript for Marking Notifications as Read
    document.addEventListener("DOMContentLoaded", function () {
        // Mark as Read Button Click
        const markReadButtons = document.querySelectorAll('.mark-read-btn');

        markReadButtons.forEach(button => {
            button.addEventListener('click', function () {
                const notificationId = button.getAttribute('data-id');

                // Send AJAX request to mark as read
                fetch("{{ route('notifications.markRead') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ notification_id: notificationId })
                })
                .then(response => {
                    return response.json();  // Ensure we get the JSON response from the server
                })
                .then(data => {
                    if (data.success) {
                        // Successfully marked as read
                        button.closest('.notification-item').classList.remove('unread');
                        button.closest('.notification-item').classList.add('read');
                        button.style.display = 'none'; // Hide the 'Mark as Read' button
                    } else {
                        // Log response to console for debugging (if something goes wrong)
                        console.error("Failed to mark notification as read:", data.message);
                    }
                })
                .catch(error => {
                    // Catch any unexpected errors and log them in the console
                    console.error("AJAX Error:", error);
                });
            });
        });
    });
</script>
