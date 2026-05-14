<div class="col">
    <div class="product-card">
        <a href="{{ route('product.show', $product->id) }}" class="text-decoration-none">
            <div class="product-img-wrapper position-relative">
                @if($product->image)
                    <img src="{{ $product->image_url }}" class="product-img" alt="{{ $product->name }}">
                @else
                    <div class="text-muted d-flex align-items-center justify-content-center h-100 w-100 bg-dark">
                        <span class="small">No Image</span>
                    </div>
                @endif
                @if(isset($isFlashSale) && $isFlashSale)
                    <span class="position-absolute top-0 end-0 badge bg-danger m-2">-{{ rand(10, 50) }}%</span>
                @endif
            </div>
        </a>
        <div class="card-body p-3 d-flex flex-column flex-grow-1">
            <span class="text-muted small mb-1">{{ $product->category }}</span>
            <a href="{{ route('product.show', $product->id) }}" class="text-decoration-none">
                <h6 class="text-white text-truncate mb-2 product-title-hover" title="{{ $product->name }}">{{ $product->name }}</h6>
            </a>
            <div class="mt-auto">
                <h5 class="text-white fw-bold mb-0">UGX {{ number_format($product->price, 2) }}</h5>
                <small class="text-muted text-decoration-line-through">UGX {{ number_format($product->price * 1.2, 2) }}</small>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0 p-3 pt-0 mt-auto">
            @if(!Auth::check())
                <a href="{{ route('login') }}" class="btn add-to-cart-btn w-100 text-uppercase fw-bold shadow-sm d-flex justify-content-center align-items-center gap-2 text-decoration-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    Add to Cart
                </a>
            @elseif(Auth::user()->role === 'user')
                <form action="{{ route('cart.add') }}" method="POST" class="w-100">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn add-to-cart-btn w-100 text-uppercase fw-bold shadow-sm d-flex justify-content-center align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                        Add to Cart
                    </button>
                </form>
            @elseif(Auth::user()->role === 'admin')
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-info w-100 text-uppercase fw-bold shadow-sm d-flex justify-content-center align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit Product
                </a>
            @endif
        </div>
    </div>
</div>
