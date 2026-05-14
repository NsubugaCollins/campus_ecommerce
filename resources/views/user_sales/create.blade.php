@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg" style="background: rgba(30, 30, 30, 0.6); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.05);">
                <div class="card-header border-0 bg-transparent pt-4 pb-0 text-center">
                    <h2 class="text-white fw-bold text-uppercase mb-1" style="letter-spacing: 2px;">Sell to Us</h2>
                    <p class="text-muted small">Submit your product for a quick appraisal and offer from our team.</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    @if(session('error'))
                        <div class="alert alert-danger border-0 shadow-sm mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('user-sales.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label for="product_name" class="form-label text-white-50 small text-uppercase fw-bold">Product Name</label>
                                <input type="text" class="form-control bg-dark border-secondary text-white py-2 @error('product_name') is-invalid @enderror" id="product_name" name="product_name" value="{{ old('product_name') }}" placeholder="e.g. iPhone 13 Pro Max" required>
                                @error('product_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="category" class="form-label text-white-50 small text-uppercase fw-bold">Category</label>
                                <select class="form-select bg-dark border-secondary text-white py-2 @error('category') is-invalid @enderror" id="category" name="category" required>
                                    <option value="" selected disabled>Select Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="condition" class="form-label text-white-50 small text-uppercase fw-bold">Condition</label>
                                <select class="form-select bg-dark border-secondary text-white py-2 @error('condition') is-invalid @enderror" id="condition" name="condition" required>
                                    <option value="" selected disabled>Select Condition</option>
                                    <option value="New" {{ old('condition') == 'New' ? 'selected' : '' }}>New (Unopened)</option>
                                    <option value="Like New" {{ old('condition') == 'Like New' ? 'selected' : '' }}>Like New (Barely used)</option>
                                    <option value="Good" {{ old('condition') == 'Good' ? 'selected' : '' }}>Good (Minor wear)</option>
                                    <option value="Fair" {{ old('condition') == 'Fair' ? 'selected' : '' }}>Fair (Noticeable wear)</option>
                                </select>
                                @error('condition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="expected_price" class="form-label text-white-50 small text-uppercase fw-bold">Expected Price (UGX)</label>
                                <input type="number" class="form-control bg-dark border-secondary text-white py-2 @error('expected_price') is-invalid @enderror" id="expected_price" name="expected_price" value="{{ old('expected_price') }}" placeholder="e.g. 1500000" required>
                                @error('expected_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="description" class="form-label text-white-50 small text-uppercase fw-bold">Description & Specs</label>
                                <textarea class="form-control bg-dark border-secondary text-white py-2 @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Tell us about the storage, battery health, any defects, etc." required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="images" class="form-label text-white-50 small text-uppercase fw-bold">Product Images (Min 1)</label>
                                <input type="file" class="form-control bg-dark border-secondary text-white py-2 @error('images') is-invalid @enderror" id="images" name="images[]" multiple accept="image/*" required>
                                <div class="form-text text-muted small">Upload clear photos showing all sides and any notable wear or defects.</div>
                                @error('images')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @error('images.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mt-5">
                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold text-uppercase shadow-lg" style="letter-spacing: 1px;">
                                    Submit Trade-In Request
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('user-sales.index') }}" class="text-decoration-none text-muted small">
                    <i class="me-1">📋</i> View My Submissions
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
