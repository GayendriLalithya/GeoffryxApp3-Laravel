<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geoffryx - Find Your Perfect Architect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --teal: #008080;
            --dark-teal: #006666;
            --light-teal: #e1f7f7;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Navbar Styles */
        .navbar {
            padding: 20px 0;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 600;
            color: var(--teal);
            font-size: 24px;
        }
        
        .navbar-brand img {
            height: 90px;
        }

        .nav-link {
            color: #333;
            font-weight: 500;
            margin: 0 15px;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: var(--teal);
        }

        .btn-login {
            background: var(--light-teal);
            color: var(--teal);
            padding: 8px 24px;
            border-radius: 6px;
            margin-right: 10px;
        }

        .btn-signup {
            background: var(--teal);
            color: white;
            padding: 8px 24px;
            border-radius: 6px;
        }

        /* Hero Section */
        .hero {
            padding: 100px 0;
            background: linear-gradient(45deg, var(--light-teal), #fff);
        }

        .hero-img-section img {
            opacity: 0.7; /* Adjust transparency (0.1 to 1) */
            /* filter: brightness(0.9) sepia(1) hue-rotate(-50deg) saturate(3);  */
            /* transition: all 0.3s ease;  */
        }
        
        /* .hero-img-section img:hover {
            opacity: 1; 
            filter: brightness(1) saturate(1); 
        } */


        .hero h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #333;
            margin-top: 40px;
        }

        .hero p {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
        }

        .search-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        /* Features Section */
        .features {
            padding: 80px 0;
        }

        .feature-card {
            padding: 30px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 40px;
            color: var(--teal);
            margin-bottom: 20px;
        }

        /* How It Works Section */
        .how-it-works {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .step-card {
            text-align: center;
            padding: 20px;
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: var(--teal);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-weight: 600;
        }

        /* Featured Architects Section */
        .featured-architects {
            padding: 80px 0;
        }

        .architect-card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .architect-info {
            padding: 20px;
        }

        .rating {
            color: #FFD700;
        }

        /* Footer */
        footer {
            background: #333;
            color: white;
            padding: 60px 0 30px;
        }

        .footer-links h5 {
            color: var(--light-teal);
            margin-bottom: 20px;
        }

        .footer-links ul {
            list-style: none;
            padding: 0;
        }

        .footer-links a {
            color: #fff;
            text-decoration: none;
            margin-bottom: 10px;
            display: block;
            opacity: 0.8;
            transition: opacity 0.3s;
        }

        .footer-links a:hover {
            opacity: 1;
        }

        .social-links {
            font-size: 24px;
            margin-top: 20px;
        }

        .social-links a {
            color: white;
            margin-right: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('resources/images/geoffreyx-logo-bg.png') }}" alt="GeoffreyX">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#architects">Find Architects</a>
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

    <!-- Hero Section -->
    <section class="hero mt-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1>Find Your Perfect Architect</h1>
                    <p>Connect with top-rated architects for your dream project. Browse portfolios, read reviews, and hire with confidence.</p>
                    <div class="search-box">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Location">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Project Type">
                            </div>
                            <div class="col-12">
                                <button class="btn btn-signup w-100">Search Architects</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-img-section">
                        <img src="{{ asset('resources/images/hero-image.jpg') }}" alt="Hero Image" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <h2 class="text-center mb-5">Why Choose ArchitectFinder</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-shield-alt feature-icon"></i>
                        <h4>Verified Professionals</h4>
                        <p>All architects are thoroughly vetted and verified for quality assurance.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-star feature-icon"></i>
                        <h4>Client Reviews</h4>
                        <p>Read authentic reviews from previous clients to make informed decisions.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-handshake feature-icon"></i>
                        <h4>Secure Hiring</h4>
                        <p>Safe and secure platform for hiring and communicating with architects.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <h2 class="text-center mb-5">How It Works</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h4>Search</h4>
                        <p>Browse architects based on location and project type</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h4>Compare</h4>
                        <p>Review portfolios and client feedback</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h4>Connect</h4>
                        <p>Hire and collaborate with your chosen architect</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Architects Section -->
    <section class="featured-architects" id="architects">
        <div class="container">
            <h2 class="text-center mb-5">Featured Architects</h2>
            <div class="row">
                <!-- Repeat this card structure for each featured architect -->
                <div class="col-md-4">
                    <div class="architect-card">
                        <img src="path_to_architect_image.jpg" alt="Architect" class="img-fluid">
                        <div class="architect-info">
                            <h4>Ann Fox</h4>
                            <p>Charted Architect</p>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <span class="ms-2">4.5</span>
                            </div>
                            <button class="btn btn-signup w-100 mt-3">View Profile</button>
                        </div>
                    </div>
                </div>
                <!-- Add more architect cards here -->
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>ArchitectFinder</h5>
                    <p>Connect with the best architects for your dream project.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="footer-links">
                        <h5>Company</h5>
                        <ul>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Careers</a></li>
                            <li><a href="#">Press</a></li>
                            <li><a href="#">Blog</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="footer-links">
                        <h5>Support</h5>
                        <ul>
                            <li><a href="#">Help Center</a></li>
                            <li><a href="#">Safety</a></li>
                            <li><a href="#">Terms of Service</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="footer-links">
                        <h5>Subscribe to Our Newsletter</h5>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Enter your email">
                            <button class="btn btn-signup" type="button">Subscribe</button>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="mt-5 mb-3" style="border-color: rgba(255,255,255,0.1);">
            <p class="text-center mb-0">© 2024 Geoffryx. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add navbar background color on scroll
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.querySelector('.navbar').style.backgroundColor = '#fff';
                document.querySelector('.navbar').style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
            } else {
                document.querySelector('.navbar').style.backgroundColor = 'transparent';
                document.querySelector('.navbar').style.boxShadow = 'none';
            }
        });
    </script>
</body>
</html>