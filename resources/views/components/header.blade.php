<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('resources/images/geoffreyx-logo.png') }}" alt="GeoffreyX">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Services</a>
                    </li>
                </ul>
                @if (Route::has('login'))
                    <div class="d-flex">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-login">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-login">Log in</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-signup">Sign Up</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </nav>