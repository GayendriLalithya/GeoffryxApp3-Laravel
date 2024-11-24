@extends('main.app')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('resources/css/auth/style.css') }}">
@endsection

@section('content')
    <!-- Logo -->
    <div class="logo-container">
        <img src="{{ asset('resources/images/geoffreyx-logo.png') }}" alt="GeoffreyX">
    </div>

    <!-- Forgot Password Section -->
    <div class="auth-container" id="forgotPassword">
        
        <p class="form-heading">Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input id="email" type="email" name="email" class="form-control" required autofocus>
            </div>

            <button type="submit" class="btn-primary-custom">Email Password Reset Link</button>

        </form>

    </div>
@endsection

@section('custom-js')
    <script src="{{ asset('resources/js/script.js') }}"></script>
@endsection