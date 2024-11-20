<!-- Main Content -->
<div class="top-bar">
    <div class="user-info">
        <div class="user-details">
            <p class="user-name">Hi {{ Auth::user()->name }}</p>
            <p class="user-role">{{ ucfirst(Auth::user()->user_type) }}</p>
        </div>
        <img src="#" alt="Profile">
    </div>
</div>
