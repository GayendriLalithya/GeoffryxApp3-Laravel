@section('additional-css')
    <link rel="stylesheet" href="{{ asset('resources/css/notification.css') }}">
@endsection

<div class="container mt-4">
        <div class="filters">
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="unread">Unread</button>
        </div>

        <div class="notifications-container">
            <!-- Notifications will be dynamically added here -->
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="notificationModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h4 class="modal-title"></h4>
                    <p class="modal-text mt-3"></p>
                    <div class="text-end mt-4">
                        <button class="close-btn" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>