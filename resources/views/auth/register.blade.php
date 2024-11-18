@extends('layouts.app')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('resources/css/register.css') }}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 signup-section">
                <h1>Create a new account</h1>
                <form action="{{ route('register') }}" method="POST">
                    @csrf  <!-- CSRF token for security -->
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contact No</label>
                        <input type="tel" class="form-control" name="contact_no" required>
                    </div>

                    <!-- <div class="mb-3">
                        <label class="form-label">User type</label>
                        <select class="form-control" name="user_type" required>
                            <option value="" selected disabled>Select user type</option>
                            <option value="customer">Customer</option>
                            <option value="admin">Admin</option>
                            <option value="professional">Professional</option>
                        </select>
                    </div> -->

                    <div class="mb-3">
                        <label class="form-label">User type</label>
                        <select class="form-control" name="user_type" required disabled>
                            <option value="customer" selected>Customer</option>
                            <option value="admin">Admin</option>
                            <option value="professional">Professional</option>
                        </select>
                        <input type="hidden" name="user_type" value="customer">
                    </div>

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

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <div class="password-field">
                            <input type="password" class="form-control" name="password_confirmation" required>
                            <span class="password-toggle">
                                <i class="far fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-signup">Sign Up</button>
                </form>
            </div>

            <div class="col-md-6 login-section">
                <h2>Already have an account?</h2>
                <p>Sign In and discover great amount of opportunities</p>
                <a href="{{ route('login') }}" class="btn btn-signin">Sign In</a>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ asset('resources/js/script.js') }}"></script>
@endsection