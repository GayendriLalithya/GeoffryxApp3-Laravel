@extends('main.app')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('resources/css/auth/login.css') }}">
@endsection

@section('content')
    <div class="container-fluid">
        
        <div class="row">

            <div class="col-md-6 login-section">

                <h1>Login to your Account</h1>

                <form method="POST" action="{{ route('login') }}">
                    @csrf  <!-- CSRF token for security -->

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="password-field">
                            <input type="password" class="form-control" name="password" required>
                            <span class="password-toggle">
                                <i class="far fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <div class="auth-extras">
                        <div class="form-check">
                            <label class="form-check-label" for="remember_me">
                                <input class="form-check-input" id="remember_me" type="checkbox" name="remember">
                                Remember me
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-password">Forgot password?</a>
                        @endif

                    </div>

                    <button type="submit" class="btn btn-signin">Sign In</button>
                </form>
            </div>

            <div class="col-md-6 signup-section">
                <h2>New Here?</h2>
                <p>Sign Up and discover great amount of opportunities</p>
                <a href="{{ route('register') }}" class="btn btn-signup">Sign Up</a>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ asset('resources/js/script.js') }}"></script>
@endsection