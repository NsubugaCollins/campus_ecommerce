<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $siteSettings['store_name'] ?? 'Cycle' }} - Premium E-Commerce</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Custom Styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body class="antialiased">
    <div id="app" class="min-vh-100 d-flex flex-column">
        <nav class="navbar navbar-expand-md navbar-dark sticky-top shadow-sm py-3">
            <div class="container">
                <a class="navbar-brand text-uppercase" href="{{ url('/') }}">
                    <img src="{{ asset('images/MAIN LOGO 1.png') }}" alt="Logo" height="50" style="object-fit: contain;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Search Bar (Middle) -->
                    <form class="d-flex mx-auto w-100 my-3 my-md-0 px-0 px-md-4 order-3 order-md-2" style="max-width: 600px;">
                        <div class="input-group">
                            <input class="form-control border-secondary bg-dark text-white shadow-none py-2" type="search" placeholder="Search for products, brands..." aria-label="Search">
                            <button class="btn btn-primary px-3" type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            </button>
                        </div>
                    </form>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto align-items-center flex-row flex-wrap justify-content-center gap-3 gap-md-0 mt-3 mt-md-0 order-2 order-md-3">
                        @if(!Auth::check() || Auth::user()->role === 'user')
                        <li class="nav-item me-md-4">
                            <a class="nav-link d-flex align-items-center text-white p-0 p-md-2 fw-bold" href="{{ route('user-sales.create') }}" title="Sell to Us">
                                <span class="d-none d-lg-inline me-2 small text-uppercase" style="letter-spacing: 1px;">Sell</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                            </a>
                        </li>
                        <li class="nav-item me-md-4">
                            <a class="nav-link position-relative d-flex align-items-center text-white p-0 p-md-2" href="{{ route('cart.index') }}" title="Cart">
                                <div class="position-relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">{{ \App\Models\CartItem::getCount() }}</span>
                                </div>
                            </a>
                        </li>
                        @endif
                        
                        <!-- Theme Toggle -->
                        <li class="nav-item me-md-3">
                            <button class="btn btn-link nav-link p-0 p-md-2 d-flex align-items-center" id="themeToggle" type="button" aria-label="Toggle theme">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="theme-icon-light d-none"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="theme-icon-dark"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                            </button>
                        </li>
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link p-0 p-md-2" href="{{ route('login') }}">{{ __('Sign In') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="btn btn-primary px-3 py-1 px-md-4 py-md-2" href="{{ route('register') }}">{{ __('Sign Up') }}</a>
                                </li>
                            @endif
                        @else
                            @if(Auth::user()->role === 'admin')
                                <li class="nav-item me-3 d-none d-md-block">
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm text-uppercase fw-bold px-3 py-2">
                                        Dashboard
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-weight: bold;">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-dark shadow border-0" aria-labelledby="navbarDropdown" style="background-color: #1e1e1e;">
                                    @if(Auth::user()->role === 'admin')
                                        <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                                        <a class="dropdown-item py-2" href="{{ route('admin.profile') }}">Admin Profile</a>
                                    @else
                                        <a class="dropdown-item py-2" href="{{ route('home') }}">Dashboard</a>
                                        <a class="dropdown-item py-2 fw-bold text-primary" href="{{ route('user-sales.create') }}">Sell to Us</a>
                                        <a class="dropdown-item py-2" href="{{ route('user-sales.index') }}">My Trade-Ins</a>
                                        <a class="dropdown-item py-2" href="{{ route('user.profile') }}">My Profile</a>
                                        <a class="dropdown-item py-2" href="{{ route('orders.index') }}">My Orders</a>
                                        <a class="dropdown-item py-2" href="{{ route('messages.index') }}">Messages</a>
                                    @endif
                                    <div class="dropdown-divider border-secondary opacity-25"></div>
                                    <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="flex-grow-1 d-flex align-items-center">
            @yield('content')
        </main>
        <footer class="pt-5 pb-4 mt-auto" style="background-color: #121212; border-top: 1px solid rgba(255,255,255,0.05);">
            <div class="container">
                <div class="row text-md-start text-center text-muted">
                    <div class="col-md-3 mb-4">
                        <h5 class="text-white text-uppercase fw-bold mb-3">{{ $siteSettings['store_name'] ?? 'Cycle' }}</h5>
                        <p class="small">{{ $siteSettings['store_description'] ?? 'Your premium destination for the best deals, genuine products, and fastest delivery on campus.' }}</p>
                        <h6 class="text-white text-uppercase fw-bold mt-4 mb-2">Location & Contacts</h6>
                        <ul class="list-unstyled small">
                            <li class="mb-2"><i class="me-2">📍</i> {{ $siteSettings['store_address'] ?? 'Main Campus Plaza, Block A' }}</li>
                            <li class="mb-2"><i class="me-2">📞</i> {{ $siteSettings['store_phone'] ?? '+256 700 000 000' }}</li>
                            <li class="mb-2"><i class="me-2">✉️</i> {{ $siteSettings['store_email'] ?? 'support@cycle.com' }}</li>
                        </ul>
                    </div>
                    <div class="col-6 col-md-3 mb-4">
                        <h6 class="text-white text-uppercase fw-bold mb-3">Customer Service</h6>
                        <ul class="list-unstyled small">
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Help Center</a></li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Track Order</a></li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Returns</a></li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Delivery</a></li>
                        </ul>
                    </div>
                    <div class="col-6 col-md-3 mb-4">
                        <h6 class="text-white text-uppercase fw-bold mb-3">How to Trade</h6>
                        <ul class="list-unstyled small">
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Selling</a></li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Guidelines</a></li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Payments</a></li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Disputes</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 mb-4">
                        <h6 class="text-white text-uppercase fw-bold mb-3">Newsletter</h6>
                        <p class="small mb-3">Subscribe for latest offers!</p>
                        <form class="d-flex justify-content-center justify-content-md-start">
                            <div class="input-group input-group-sm" style="max-width: 250px;">
                                <input type="email" class="form-control bg-dark border-secondary text-white shadow-none" placeholder="Email">
                                <button class="btn btn-primary" type="submit">Go</button>
                            </div>
                        </form>
                    </div>
                </div>
                <hr class="border-secondary my-4 opacity-25">
                <div class="text-center">
                    <p class="text-muted small mb-0">&copy; {{ date('Y') }} {{ $siteSettings['store_name'] ?? 'Cycle' }}. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

    @if(session('welcome_message'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="welcomeToast" class="toast align-items-center text-white bg-primary border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="me-2">👋</i> {{ session('welcome_message') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var welcomeToast = document.getElementById('welcomeToast');
            var toast = new bootstrap.Toast(welcomeToast);
            toast.show();
            
            // Auto hide after 5 seconds
            setTimeout(function() {
                toast.hide();
            }, 5000);
        });
    </script>
    @endif
    @if(session('points_earned'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="pointsToast" class="toast align-items-center text-white bg-warning border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body text-dark fw-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                    {{ session('points_earned') }}
                </div>
                <button type="button" class="btn-close btn-close-dark me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var pointsToast = document.getElementById('pointsToast');
            var toast = new bootstrap.Toast(pointsToast);
            toast.show();
            setTimeout(function() { toast.hide(); }, 6000);
        });
    </script>
    @endif
    <script>
        // Theme Toggle Logic
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            const htmlElement = document.documentElement;
            const sunIcon = document.querySelector('.theme-icon-light');
            const moonIcon = document.querySelector('.theme-icon-dark');

            // Check for saved theme preference
            const currentTheme = localStorage.getItem('theme') || 'dark';
            setTheme(currentTheme);

            themeToggle.addEventListener('click', () => {
                const newTheme = htmlElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
                setTheme(newTheme);
            });

            function setTheme(theme) {
                htmlElement.setAttribute('data-bs-theme', theme);
                localStorage.setItem('theme', theme);
                
                if (theme === 'dark') {
                    sunIcon.classList.add('d-none');
                    moonIcon.classList.remove('d-none');
                } else {
                    sunIcon.classList.remove('d-none');
                    moonIcon.classList.add('d-none');
                }
            }
        });
    </script>
</body>
</html>
