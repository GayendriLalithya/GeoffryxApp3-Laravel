@extends('layouts.app')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('resources/css/dashboard.css') }}">
@endsection

@section('content')
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
                    <a class="nav-link" href="{{ route('admin.request') }}">
                        <i class="fas fa-user-plus"></i> Account Requests
                    </a>
                    <a class="nav-link" href="#">
                        <i class="fas fa-users"></i> Manage Users
                    </a>
                    <a class="nav-link" href="#">
                        <i class="fas fa-folder"></i> Manage Files
                    </a>
                    <a class="nav-link" href="#">
                        <i class="fas fa-chart-pie"></i> Manage Projects
                    </a>
                    @break
    
                @case('customer')
                    <a class="nav-link" href="#">
                        <i class="fas fa-project-diagram"></i> Projects
                    </a>
                    <a class="nav-link" href="#">
                        <i class="fas fa-user-tie"></i> Professionals
                    </a>
                    <a class="nav-link" href="#">
                        <i class="fas fa-folder"></i> Manage File
                    </a>
                    @break
    
                @case('professional')
                    <a class="nav-link" href="#">
                        <i class="fas fa-envelope-open-text"></i> Project Requests
                    </a>
                    <a class="nav-link" href="#">
                        <i class="fas fa-chart-pie"></i> Manage Projects
                    </a>
                    <a class="nav-link" href="#">
                        <i class="fas fa-history"></i> Work History
                    </a>
                    <a class="nav-link" href="#">
                        <i class="fas fa-folder"></i> My Files
                    </a>
                    @break
    
                @default
                    <a class="nav-link" href="#">No Navigation Available</a>
            @endswitch

        <!-- Common Link - Settings for all users -->
        <a class="nav-link" href="#">
            <i class="fas fa-cog"></i> Settings
        </a>

        <!-- Logout Link as a Form -->
        <form action="{{ route('logout') }}" method="POST" style="display: none;" id="logout-form">
            @csrf
        </form>
        <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-bar">
            <div class="user-info">
                <div class="user-details">
                    <p class="user-name">Hi {{ Auth::user()->name }}</p>
                    <p class="user-role">{{ ucfirst(Auth::user()->user_type) }}</p>
                </div>
                <img src="user-profile.jpg" alt="Profile">
            </div>
        </div>
        
@endsection

@section('custom-js')
    <script src="{{ asset('resources/js/script.js') }}"></script>
@endsection