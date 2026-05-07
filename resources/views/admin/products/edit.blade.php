@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="text-white mb-0">Edit Product: <span class="text-crimson">{{ $product->name }}</span></h3>
            </div>

            <div class="card border-0 shadow-lg" style="background: rgba(30, 30, 30, 0.6); backdrop-filter: blur(15px); border-radius: 1rem;">
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="product_id" class="form-label text-secondary">Product ID / SKU</label>
                            <input id="product_id" type="text" class="form-control form-control-lg @error('product_id') is-invalid @enderror" name="product_id" value="{{ old('product_id', $product->product_id) }}" required autofocus placeholder="e.g. ELEC-001">
                            @error('product_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="name" class="form-label text-secondary">Product Name</label>
                            <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" value="{{ old('name', $product->name) }}" required placeholder="e.g. Premium Wireless Headphones">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label text-secondary">Product Description</label>
                            <textarea id="description" class="form-control form-control-lg @error('description') is-invalid @enderror" name="description" rows="4" placeholder="Enter detailed product description here...">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="category" class="form-label text-secondary">Category</label>
                            <select id="category" class="form-control form-control-lg @error('category') is-invalid @enderror" name="category" required>
                                <option value="" disabled>Select a category</option>
                                <option value="Electronics" {{ old('category', $product->category) == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                                <option value="Furniture" {{ old('category', $product->category) == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                                <option value="Beddings" {{ old('category', $product->category) == 'Beddings' ? 'selected' : '' }}>Beddings</option>
                                <option value="Fashion" {{ old('category', $product->category) == 'Fashion' ? 'selected' : '' }}>Fashion</option>
                                <option value="Accessories" {{ old('category', $product->category) == 'Accessories' ? 'selected' : '' }}>Accessories</option>
                            </select>
                            @error('category')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="price" class="form-label text-secondary">Price (UGX)</label>
                            <input id="price" type="number" step="0.01" min="0" class="form-control form-control-lg @error('price') is-invalid @enderror" name="price" value="{{ old('price', $product->price) }}" required placeholder="0.00">
                            @error('price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label text-secondary">Primary Product Image (Main View)</label>
                            @if($product->image)
                                <div class="mb-3 position-relative d-inline-block">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 150px; object-fit: cover;">
                                    <div class="position-absolute top-0 end-0 p-1">
                                        <span class="badge bg-primary">Primary</span>
                                    </div>
                                </div>
                            @endif
                            <input id="image" type="file" class="form-control form-control-lg @error('image') is-invalid @enderror" name="image" accept="image/*" onchange="previewImage(event)">
                            <small class="text-muted d-block mt-2">Uploading a new image will replace the current primary image.</small>
                            @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <p class="text-muted small mb-2">New Primary Image Preview:</p>
                                <img id="previewImg" src="" alt="Image preview" class="img-fluid rounded" style="max-height: 150px; object-fit: cover;">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-secondary d-block">Additional Product Images (Other Sides)</label>
                            @if($product->images->count() > 0)
                                <div class="row g-3 mb-4">
                                    @foreach($product->images as $image)
                                        <div class="col-4 col-md-3">
                                            <div class="position-relative group">
                                                <img src="{{ asset('storage/' . $image->image_path) }}" class="img-fluid rounded border border-secondary" style="height: 100px; width: 100%; object-fit: cover;">
                                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 p-1 shadow" 
                                                        onclick="if(confirm('Delete this image?')) { document.getElementById('delete-image-{{ $image->id }}').submit(); }"
                                                        title="Delete Image">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="bg-dark bg-opacity-25 p-3 rounded border border-secondary border-dashed">
                                <label for="additional_images" class="form-label text-white-50 small mb-2">Add More Images</label>
                                <input id="additional_images" type="file" class="form-control form-control-sm @error('additional_images.*') is-invalid @enderror" name="additional_images[]" accept="image/*" multiple onchange="previewMultipleImages(event)">
                                @error('additional_images.*')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div id="additionalImagesPreview" class="mt-3 d-flex flex-wrap gap-2"></div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold text-uppercase" style="letter-spacing: 1px;">
                                Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if($product->images->count() > 0)
    @foreach($product->images as $image)
        <form id="delete-image-{{ $image->id }}" action="{{ route('admin.products.images.destroy', $image->id) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endif

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

    function previewMultipleImages(event) {
        const input = event.target;
        const previewContainer = document.getElementById('additionalImagesPreview');
        previewContainer.innerHTML = '';
        
        if (input.files) {
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-fluid rounded';
                    img.style.maxHeight = '80px';
                    img.style.width = '80px';
                    img.style.objectFit = 'cover';
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        }
    }
</script>
@endsection
