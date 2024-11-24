@extends('main.app')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('resources/css/auth/style.css') }}">
@endsection

@section('content')
    <!-- Logo -->
    <div class="logo-container">
        <img src="{{ asset('resources/images/geoffreyx-logo.png') }}" alt="GeoffreyX">
    </div>

    <!-- Reset Password Section -->
    <div class="auth-container" id="resetPassword" >

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input id="email" type="email" name="email" class="form-control" required autofocus autocomplete="username">
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="password-field">
                    <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password">
                    <span class="password-toggle">
                        <i class="far fa-eye"></i>
                    </span>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <div class="password-field">
                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">      
                    <span class="password-toggle">
                        <i class="far fa-eye"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn-primary-custom">Reset Password</button>

        </form>
    </div>
@endsection

@section('custom-js')
    <script src="{{ asset('resources/js/script.js') }}"></script>
@endsection