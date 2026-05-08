<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $siteSettings['store_name'] ?? 'Cycle' }} - Admin Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Custom Styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    <style>
        .admin-sidebar {
            height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
            background-color: rgba(26, 26, 26, 0.95);
            border-right: 1px solid rgba(184, 115, 51, 0.2);
        }
        .admin-sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
        }
        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 0.25rem;
            transition: all 0.3s ease;
        }
        .admin-sidebar .nav-link:hover, .admin-sidebar .nav-link.active {
            color: #fff;
            background: rgba(220, 20, 60, 0.1);
            border-left: 3px solid #DC143C;
        }
        .admin-content {
            background-color: #121212;
            min-height: 100vh;
        }
        .admin-topbar {
            background-color: rgba(30, 30, 30, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .btn-crimson {
            background-color: #DC143C;
            color: white;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-crimson:hover {
            background-color: #b01030;
            color: white;
            transform: translateY(-1px);
        }
        .text-crimson {
            color: #DC143C !important;
        }
    </style>
</head>
<body class="antialiased">
    <div class="container-fluid p-0">
        <div class="row g-0 flex-nowrap">
            <!-- Sidebar -->
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 admin-sidebar">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-4 text-white h-100 pb-3">
                    <a href="{{ url('/') }}" class="d-flex align-items-center pb-3 mb-4 text-white text-decoration-none border-bottom border-secondary w-100 justify-content-center justify-content-sm-start">
                        <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" height="30" style="object-fit: contain;" class="d-none d-sm-inline">
                    </a>
                    
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100" id="menu">
                        <li class="nav-item w-100">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link align-middle px-3 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <span class="ms-1 d-none d-sm-inline">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item w-100">
                            <a href="{{ route('admin.products.index') }}" class="nav-link align-middle px-3 {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                <span class="ms-1 d-none d-sm-inline">Products</span>
                            </a>
                        </li>
                        <li class="nav-item w-100">
                            <a href="{{ route('admin.orders.index') }}" class="nav-link align-middle px-3 {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                                <span class="ms-1 d-none d-sm-inline">Orders</span>
                            </a>
                        </li>
                        <li class="nav-item w-100">
                            <a href="{{ route('admin.users.index') }}" class="nav-link align-middle px-3 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <span class="ms-1 d-none d-sm-inline">Users</span>
                            </a>
                        </li>
                        <li class="nav-item w-100">
                            <a href="{{ route('admin.analytics') }}" class="nav-link align-middle px-3 {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                                <span class="ms-1 d-none d-sm-inline">Analytics</span>
                            </a>
                        </li>
                        <li class="nav-item w-100">
                            <a href="{{ route('admin.earnings') }}" class="nav-link align-middle px-3 {{ request()->routeIs('admin.earnings') ? 'active' : '' }}">
                                <span class="ms-1 d-none d-sm-inline">Earnings</span>
                            </a>
                        </li>
                        <li class="nav-item w-100">
                            <a href="{{ route('admin.messages.index') }}" class="nav-link align-middle px-3 {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                                <span class="ms-1 d-none d-sm-inline">Messages</span>
                            </a>
                        </li>
                        <li class="nav-item w-100 mt-4">
                            <a href="{{ route('admin.settings') }}" class="nav-link align-middle px-3 {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                                <span class="ms-1 d-none d-sm-inline">Settings</span>
                            </a>
                        </li>
                    </ul>
                    <hr class="w-100 border-secondary">
                    <div class="dropdown pb-4 w-100 text-center text-sm-start">
                        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle px-3" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-sm-2" style="width: 32px; height: 32px; font-weight: bold;">
                                {{ Auth::check() ? substr(Auth::user()->name, 0, 1) : 'A' }}
                            </div>
                            <span class="d-none d-sm-inline mx-1">{{ Auth::check() ? Auth::user()->name : 'Admin' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" style="background-color: #1e1e1e;">
                            <li><a class="dropdown-item" href="{{ route('admin.settings') }}">Settings</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.profile') }}">Profile</a></li>
                            <li>

                                <hr class="dropdown-divider border-secondary opacity-25">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Sign out
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col py-0 px-0 admin-content d-flex flex-column">
                <!-- Topbar -->
                <div class="admin-topbar d-flex justify-content-between align-items-center py-3 px-4 sticky-top">
                    <h5 class="mb-0 text-white d-none d-md-block">@yield('title', 'Admin Panel')</h5>
                    <div class="d-md-none">
                        <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" height="30" style="object-fit: contain;">
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <button class="btn btn-link text-white px-2 d-flex align-items-center" id="themeToggle" type="button" aria-label="Toggle theme">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="theme-icon-light d-none"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="theme-icon-dark"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                            </button>
                        </div>
                        <a href="{{ url('/') }}" class="btn btn-sm btn-crimson text-uppercase fw-bold px-3 py-2 d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                            Visit Shop
                        </a>
                    </div>
                </div>

                <!-- Page Content -->
                <div class="p-4 flex-grow-1">
                    @yield('content')
                </div>
                
                <!-- Footer -->
                <footer class="py-3 text-center" style="border-top: 1px solid rgba(255,255,255,0.05);">
                    <p class="text-muted small mb-0">&copy; {{ date('Y') }} {{ $siteSettings['store_name'] ?? 'Cycle' }}. All rights reserved.</p>
                </footer>
            </div>
        </div>
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
