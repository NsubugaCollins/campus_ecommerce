@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-5">
        <h2 class="text-white mb-4 fw-bold text-uppercase border-start border-primary ps-3" style="letter-spacing: 1px; border-width: 4px !important;">
            {{ $category }}
        </h2>
        
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-3">
            @forelse($products as $product)
                @include('partials.product_card', ['product' => $product, 'isFlashSale' => false])
            @empty
                <div class="col-12 text-center py-5">
                    <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-secondary opacity-25"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    </div>
                    <h4 class="text-muted">No products found in this category.</h4>
                    <a href="{{ url('/') }}" class="btn btn-primary mt-3 px-4">Back to Shop</a>
                </div>
            @endforelse
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
