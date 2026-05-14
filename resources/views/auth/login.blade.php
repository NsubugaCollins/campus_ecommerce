@extends('layouts.app')

@section('content')
<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 1rem;">
                <div class="card-header text-center py-4 bg-transparent border-0">
                    <h3 class="mb-0 fw-bold" style="color: #fff; letter-spacing: 1px;">Welcome Back</h3>
                    <p class="text-muted small mt-2 mb-0" style="color: rgba(255,255,255,0.9) !important;">Sign in to your Cycle account</p>
                </div>

                <div class="card-body p-4 p-md-5 pt-0">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="name@example.com">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="password" class="form-label mb-0">{{ __('Password') }}</label>
                                @if (Route::has('password.request'))
                                    <a class="text-decoration-none small" href="{{ route('password.request') }}" style="color: #B87333;">
                                        {{ __('Forgot Password?') }}
                                    </a>
                                @endif
                            </div>
                            <div class="input-group">
                                <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword" style="border-top-left-radius: 0; border-bottom-left-radius: 0; border-left: none;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="eyeIcon"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </button>
                            </div>
                            <script>
                                document.getElementById('togglePassword').addEventListener('click', function() {
                                    const password = document.getElementById('password');
                                    const icon = document.getElementById('eyeIcon');
                                    if (password.type === 'password') {
                                        password.type = 'text';
                                        icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
                                    } else {
                                        password.type = 'password';
                                        icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
                                    }
                                });
                            </script>

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-muted" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold text-uppercase" style="letter-spacing: 1px;">
                                {{ __('Sign In') }}
                            </button>
                        </div>
                        
                        <div class="text-center mt-4">
                            <p class="text-muted small">Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: #DC143C;">Sign up</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
