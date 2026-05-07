@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="text-white mb-0">Add New Product</h3>
            </div>

            @if (session('error'))
                <div class="alert alert-danger bg-dark text-danger border-danger mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card border-0 shadow-lg" style="background: rgba(30, 30, 30, 0.6); backdrop-filter: blur(15px); border-radius: 1rem;">
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="product_id" class="form-label text-secondary">Product ID</label>
                            <input id="product_id" type="text" class="form-control form-control-lg @error('product_id') is-invalid @enderror" name="product_id" value="{{ old('product_id') }}" required autofocus placeholder="e.g. ELEC-001">
                            @error('product_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="name" class="form-label text-secondary">Product Name</label>
                            <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required placeholder="e.g. Premium Wireless Headphones">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label text-secondary">Product Description</label>
                            <textarea id="description" class="form-control form-control-lg @error('description') is-invalid @enderror" name="description" rows="4" placeholder="Enter detailed product description here...">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="category" class="form-label text-secondary">Category</label>
                            <select id="category" class="form-control form-control-lg @error('category') is-invalid @enderror" name="category" required>
                                <option value="" disabled selected>Select a category</option>
                                <option value="Electronics" {{ old('category') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                                <option value="Furniture" {{ old('category') == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                                <option value="Beddings" {{ old('category') == 'Beddings' ? 'selected' : '' }}>Beddings</option>
                                <option value="Fashion" {{ old('category') == 'Fashion' ? 'selected' : '' }}>Fashion</option>
                                <option value="Accessories" {{ old('category') == 'Accessories' ? 'selected' : '' }}>Accessories</option>
                            </select>
                            @error('category')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="price" class="form-label text-secondary">Price (UGX)</label>
                            <input id="price" type="number" step="0.01" min="0" class="form-control form-control-lg @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" required placeholder="0.00">
                            @error('price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label text-secondary">Product Image</label>
                            <input id="image" type="file" class="form-control form-control-lg @error('image') is-invalid @enderror" name="image" accept="image/jpeg,image/png,image/gif,image/webp,image/bmp,image/tiff" onchange="previewImage(event)">
                            <small class="text-muted d-block mt-2">Allowed formats: JPEG, PNG, JPG, GIF, WebP, BMP, TIFF (Max 20MB)</small>
                            @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <img id="previewImg" src="" alt="Image preview" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold text-uppercase" style="letter-spacing: 1px;">
                                Save Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
        }
    }
</script>
@endsection
