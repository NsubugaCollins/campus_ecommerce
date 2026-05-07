@extends('layouts.app')

@section('content')
<style>
    .product-details-card {
        background: rgba(30, 30, 30, 0.7);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0,0,0,0.4);
    }
    .product-img-large {
        width: 100%;
        height: auto;
        max-height: 500px;
        object-fit: cover;
        border-radius: 0.5rem;
    }
    .img-container {
        background: #111;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        border-radius: 0.5rem;
    }
    .product-price {
        color: #DC143C;
        font-size: 2.5rem;
        font-weight: 800;
        letter-spacing: -1px;
    }
    .add-to-cart-lg {
        background: #B87333;
        color: #fff;
        border: none;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .add-to-cart-lg:hover {
        background: #A05E24;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(184, 115, 51, 0.3);
        color: #fff;
    }
</style>

<div class="container py-5">


    <div class="product-details-card p-4 p-md-5">
        <div class="row g-5 align-items-center">
            
            <!-- Left: Product Image -->
            <div class="col-lg-5">
                <div class="img-container shadow-sm position-relative mb-3">
                    @if($product->image)
                        <img id="main-product-image" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-img-large">
                    @else
                        <div class="d-flex align-items-center justify-content-center" style="height: 400px;">
                            <span class="text-muted fs-4">No Image Available</span>
                        </div>
                    @endif
                    <span class="position-absolute top-0 start-0 badge bg-danger m-3 fs-6 px-3 py-2">-20% OFF</span>
                </div>

                @if($product->images->count() > 0)
                    <div class="row g-2 overflow-x-auto pb-2" style="scrollbar-width: thin;">
                        <!-- Primary Image Thumbnail -->
                        <div class="col-3">
                            <div class="gallery-thumbnail active" onclick="changeImage('{{ asset('storage/' . $product->image) }}', this)">
                                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded" style="height: 80px; width: 100%; object-fit: cover; cursor: pointer;">
                            </div>
                        </div>
                        <!-- Additional Images Thumbnails -->
                        @foreach($product->images as $additionalImage)
                            <div class="col-3">
                                <div class="gallery-thumbnail" onclick="changeImage('{{ asset('storage/' . $additionalImage->image_path) }}', this)">
                                    <img src="{{ asset('storage/' . $additionalImage->image_path) }}" class="img-fluid rounded" style="height: 80px; width: 100%; object-fit: cover; cursor: pointer; opacity: 0.6; transition: all 0.3s ease;">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <style>
                .gallery-thumbnail.active img {
                    opacity: 1 !important;
                    border: 2px solid #DC143C;
                }
                .gallery-thumbnail:hover img {
                    opacity: 1 !important;
                }
            </style>

            <script>
                function changeImage(src, element) {
                    document.getElementById('main-product-image').src = src;
                    
                    // Update active state
                    document.querySelectorAll('.gallery-thumbnail').forEach(el => el.classList.remove('active'));
                    element.classList.add('active');
                }
            </script>

            <!-- Right: Product Details -->
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <span class="badge bg-secondary fs-6">{{ $product->category }}</span>
                    <span class="text-muted small">SKU: {{ $product->product_id }}</span>
                </div>
                
                <h1 class="text-white fw-bold mb-4">{{ $product->name }}</h1>
                
                <div class="mb-4 d-flex align-items-baseline gap-3">
                    <span class="product-price">UGX {{ number_format($product->price, 2) }}</span>
                    <span class="text-muted text-decoration-line-through fs-5">UGX {{ number_format($product->price * 1.2, 2) }}</span>
                </div>

                <div class="mb-5">
                    <h5 class="text-white border-bottom border-secondary pb-2 mb-3">Product Details</h5>
                    @if($product->description)
                        <p class="text-light" style="line-height: 1.8; opacity: 0.9;">
                            {!! nl2br(e($product->description)) !!}
                        </p>
                    @else
                        <p class="text-muted fst-italic">
                            No detailed description is available for this product yet.
                        </p>
                    @endif
                </div>

                <div class="d-grid gap-3 d-md-flex mt-5">
                    @if(!Auth::check() || Auth::user()->role === 'user')
                        <form action="{{ route('cart.add') }}" method="POST" class="flex-grow-1 d-flex">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn add-to-cart-lg btn-lg fw-bold w-100 py-3 d-flex align-items-center justify-content-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                                Add to Cart Now
                            </button>
                        </form>
                    @elseif(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-info btn-lg fw-bold w-100 py-3 d-flex align-items-center justify-content-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            Edit Product Details
                        </a>
                    @endif
                    <button class="btn btn-outline-light btn-lg px-4 d-flex align-items-center justify-content-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                    </button>
                </div>

                <!-- Trust Badges -->
                <div class="mt-5 pt-4 border-top border-secondary d-flex gap-4">
                    <div class="d-flex align-items-center text-muted small">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                        Fast Delivery
                    </div>
                    <div class="d-flex align-items-center text-muted small">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        Secure Checkout
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
