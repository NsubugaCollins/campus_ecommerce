@extends('layouts.admin')

@section('title', 'Admin Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card bg-dark border-secondary shadow-sm">
            <div class="card-header bg-black border-secondary py-3 d-flex align-items-center">
                <div class="rounded-circle bg-crimson text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; font-weight: bold; font-size: 20px;">
                    {{ substr($admin->name, 0, 1) }}
                </div>
                <h5 class="mb-0 text-white fw-bold">EDIT PROFILE</h5>
            </div>
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <!-- Name -->
                        <div class="col-md-6">
                            <label class="form-label text-white-50">Full Name</label>
                            <input type="text" name="name" class="form-control bg-black border-secondary text-white @error('name') is-invalid @enderror" value="{{ old('name', $admin->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="form-label text-white-50">Email Address</label>
                            <input type="email" name="email" class="form-control bg-black border-secondary text-white @error('email') is-invalid @enderror" value="{{ old('email', $admin->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="border-secondary opacity-25 my-4">
                        
                        <h6 class="text-white text-uppercase fw-bold mb-3" style="letter-spacing: 1px;">Change Password <small class="text-white-50 fw-normal ms-2"></small></h6>

                        <!-- Password -->
                        <div class="col-md-6">
                            <label class="form-label text-white-50">New Password</label>
                            <input type="password" name="password" class="form-control bg-black border-secondary text-white @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6">
                            <label class="form-label text-white-50">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control bg-black border-secondary text-white">
                        </div>
                    </div>

                    <div class="mt-5 text-end">
                        <button type="submit" class="btn btn-crimson px-5 py-3 fw-bold text-uppercase" style="letter-spacing: 1px;">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-crimson {
        background-color: #DC143C;
    }
    .btn-crimson {
        background-color: #DC143C;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-crimson:hover {
        background-color: #b01030;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 20, 60, 0.3);
    }
</style>
@endsection
