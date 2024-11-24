@extends('main.app')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('resources/css/auth/style.css') }}">
@endsection

@section('content')
    <!-- Logo -->
    <div class="logo-container">
        <img src="{{ asset('resources/images/geoffreyx-logo.png') }}" alt="GeoffreyX">
    </div>

    <!-- Confirm Password Section -->
    <div class="auth-container" id="confirmPassword">
        <p class="form-heading">This is a secure area of the application. Please confirm your password before continuing.</p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="mb-3">

                <label class="form-label">Password</label>

                <div class="password-field">

                    <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
                    <i class="fas fa-eye password-toggle"></i>
                </div>

            </div>

            <button type="submit" class="btn-primary-custom">Confirm</button>

        </form>
    </div>
@endsection

@section('custom-js')
    <script src="{{ asset('resources/js/script.js') }}"></script>
@endsection