@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white mb-0">Products Management</h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add New Product</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success bg-dark text-success border-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger bg-dark text-danger border-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card border-0 shadow-lg" style="background: rgba(30, 30, 30, 0.6); backdrop-filter: blur(15px); border-radius: 1rem;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0" style="background: transparent;">
                    <thead style="border-bottom: 2px solid rgba(184, 115, 51, 0.5);">
                        <tr>
                            <th class="px-4 py-3 border-0 text-secondary">Image</th>
                            <th class="px-4 py-3 border-0 text-secondary">Product ID</th>
                            <th class="px-4 py-3 border-0 text-secondary">Name</th>
                            <th class="px-4 py-3 border-0 text-secondary">Category</th>
                            <th class="px-4 py-3 border-0 text-secondary">Price</th>
                            <th class="px-4 py-3 border-0 text-secondary text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                            <td class="px-4 py-3 border-0 align-middle">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 60px; max-width: 60px; object-fit: cover;">
                                @else
                                    <span class="text-muted small">No image</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 border-0 align-middle">{{ $product->product_id }}</td>
                            <td class="px-4 py-3 border-0 align-middle font-weight-bold text-white">{{ $product->name }}</td>
                            <td class="px-4 py-3 border-0 align-middle"><span class="badge bg-secondary">{{ $product->category }}</span></td>
                            <td class="px-4 py-3 border-0 align-middle text-primary">UGX {{ number_format($product->price, 2) }}</td>
                            <td class="px-4 py-3 border-0 align-middle text-end">
                                <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-outline-info me-2">View</a>
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-light me-2">Edit</a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-5 text-center text-muted border-0">
                                No products found. Click "Add New Product" to create one.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-4 d-flex justify-content-center">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
