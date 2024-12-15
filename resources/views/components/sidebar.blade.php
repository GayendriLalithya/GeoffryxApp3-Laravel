<div class="dashboard-wrapper">
<!-- Sidebar -->
<div class="sidebar">
    <div class="logo">
        <img src="{{ asset('resources/images/geoffreyx-logo.png') }}" alt="GeoffreyX">
    </div>
    <nav class="nav flex-column">
        <!-- Common Links -->
        <a class="nav-link active" href="#">
            <i class="fas fa-home"></i> Home
        </a>

            @switch($userType)
                @case('admin')
                    <a class="nav-link {{ request('tab') == 'requests' ? 'active' : '' }}" href="{{ route('user.dashboard', ['tab' => 'requests']) }}">
                        <i class="fas fa-user-plus"></i> Account Requests
                    </a>
                    <a class="nav-link {{ request('tab') == 'users' ? 'active' : '' }}" href="{{ route('user.dashboard', ['tab' => 'users']) }}">
                        <i class="fas fa-users"></i> Manage Users
                    </a>
                    <a class="nav-link" href="#">
                        <i class="fas fa-chart-pie"></i> Manage Projects
                    </a>
                    @break

                @case('professional')
                    <a class="nav-link {{ request('tab') == 'project_requests' ? 'active' : '' }}" href="{{ route('user.dashboard', ['tab' => 'project_requests']) }}">
                        <i class="fas fa-envelope-open-text"></i> Project Requests
                    </a>
                    <a class="nav-link {{ request('tab') == 'manage_projects' ? 'active' : '' }}" href="{{ route('user.dashboard', ['tab' => 'manage_projects']) }}">
                        <i class="fas fa-chart-pie"></i> Manage Projects
                    </a>
                    <a class="nav-link" href="#">
                        <i class="fas fa-history"></i> Work History
                    </a>
                    @break
            @endswitch

            <!-- Common Links - Settings for all users -->
            <a class="nav-link {{ request('tab') == 'projects' ? 'active' : '' }}" href="{{ route('user.dashboard', ['tab' => 'projects']) }}">
                <i class="fas fa-project-diagram"></i> Projects
            </a>
            <a class="nav-link {{ request('tab') == 'professional' ? 'active' : '' }}" href="{{ route('user.dashboard', ['tab' => 'professional']) }}">
                <i class="fas fa-user-tie"></i> Professionals
            </a>
            <a class="nav-link" href="#">
                <i class="fas fa-folder"></i> Manage File
            </a>

            <div class="mt-auto">
                
                <!-- Common Links down - Settings for all users -->
                <a class="nav-link {{ request('tab') == 'profile' ? 'active' : '' }}" href="{{ route('user.dashboard', ['tab' => 'profile']) }}">
                    <i class="fas fa-cog"></i> Settings
                </a>

                <!-- Logout Link as a Form -->
                <form action="{{ route('logout') }}" method="POST" style="display: none;" id="logout-form">
                    @csrf
                </form>
                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>

            </div>

        </nav>
    </div>
</div>