@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="text-white mb-0">Product Details: {{ $product->name }}</h3>
            </div>

            <div class="card border-0 shadow-lg" style="background: rgba(30, 30, 30, 0.6); backdrop-filter: blur(15px); border-radius: 1rem;">
                <div class="card-body p-4 p-md-5">
                    <div class="row">
                        <div class="col-md-6">
                            @if($product->image)
                                <div class="mb-4">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid rounded" style="width: 100%; max-height: 300px; object-fit: cover;">
                                </div>
                            @else
                                <div class="mb-4 text-center">
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <span class="text-muted">No Image</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-secondary">Product ID</label>
                                <p class="text-white h5">{{ $product->product_id }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-secondary">Product Name</label>
                                <p class="text-white h5">{{ $product->name }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-secondary">Category</label>
                                <p class="text-white"><span class="badge bg-secondary">{{ $product->category }}</span></p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-secondary">Price</label>
                                <p class="text-white h4 text-primary">UGX {{ number_format($product->price, 2) }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-secondary">Created At</label>
                                <p class="text-white">{{ $product->created_at->format('M d, Y H:i') }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-secondary">Last Updated</label>
                                <p class="text-white">{{ $product->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">Edit Product</a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete Product</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection