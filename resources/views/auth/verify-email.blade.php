@extends('main.app')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('resources/css/auth/style.css') }}">
@endsection

@section('content')
    <!-- Logo -->
    <div class="logo-container">
        <img src="{{ asset('resources/images/geoffreyx-logo.png') }}" alt="GeoffreyX">
    </div>

    <!-- Verify Email Section -->
    <div class="auth-container" id="verifyEmail">

        <p class="form-heading">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.</p>

        @if (session('status') == 'verification-link-sent')
            <p class="mb-4">A new verification link has been sent to the email address you provided during registration.</p>
        @endif

        <div class="btn-group">

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <button class="btn-primary-custom">Resend Verification Email</button>

            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button class="btn-outline-custom">Logout</button>

            </form>
        
        </div>

    </div>
@endsection

@section('custom-js')
    <script src="{{ asset('resources/js/script.js') }}"></script>
@endsection