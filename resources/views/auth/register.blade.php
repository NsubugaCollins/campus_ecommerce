@extends('layouts.app')

@section('content')
<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 1rem;">
                <div class="card-header text-center py-4 bg-transparent border-0">
                    <h3 class="mb-0 fw-bold" style="color: #fff; letter-spacing: 1px;">Create Account</h3>
                    <p class="text-muted small mt-2 mb-0" style="color: rgba(255,255,255,0.9) !important;">Join Cycle today</p>
                </div>

                <div class="card-body p-4 p-md-5 pt-0">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Full Name') }}</label>
                            <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="John Doe">

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="name@example.com">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">{{ __('Phone Number') }} <span class="text-muted small fw-normal">(Optional)</span></label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-dark border-secondary text-muted" style="font-size: 0.95rem;">📞</span>
                                <input id="phone" type="tel" class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                       name="phone" value="{{ old('phone') }}" autocomplete="tel"
                                       placeholder="+256 700 000 000">
                            </div>
                            @error('phone')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="text-muted">Include country code e.g. +256 for Uganda</small>
                        </div>


                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="••••••••">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                            <input id="password-confirm" type="password" class="form-control form-control-lg" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                        </div>

                        <div class="mb-4">
                            <label for="referral_code" class="form-label">{{ __('Referral Code (Optional)') }}</label>
                            <input id="referral_code" type="text" class="form-control form-control-lg @error('referral_code') is-invalid @enderror" name="referral_code" value="{{ old('referral_code', request('ref')) }}" placeholder="ABC12345">
                            @error('referral_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="text-muted">Enter a code if you were referred by someone to earn bonus points!</small>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold text-uppercase" style="letter-spacing: 1px;">
                                {{ __('Register') }}
                            </button>
                        </div>
                        
                        <div class="text-center mt-4">
                            <p class="text-muted small">Already have an account? <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: #B87333;">Sign in</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
