@extends('layouts.app')

@section('content')
<div class="container py-5">

    <h2 class="text-white mb-4 fw-bold text-uppercase" style="letter-spacing: 1px;">Shopping Cart</h2>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card bg-dark border-secondary mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead class="table-active">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Product</th>
                                    <th scope="col" class="py-3">Price</th>

                                    <th scope="col" class="py-3">Total</th>
                                    <th scope="col" class="px-4 py-3 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cart as $id => $item)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded overflow-hidden bg-black d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px; border: 1px solid rgba(255,255,255,0.1);">
                                                @if($item['image'])
                                                @if(\Illuminate\Support\Str::startsWith($item['image'], ['http://', 'https://']))
                                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                @else
                                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                @endif
                                                @else
                                                    <div class="text-muted small">No Img</div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-white">{{ $item['name'] }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-white-50">UGX {{ number_format($item['price'], 2) }}</td>
                                    <td class="py-3">
                                        <span class="text-white">1</span>
                                    </td>
                                    <td class="py-3 fw-bold text-white">UGX {{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                    <td class="px-4 py-3 text-end">
                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-secondary opacity-25"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                                        </div>
                                        <h5 class="text-muted mb-3">Your cart is empty</h5>
                                        <a href="{{ url('/') }}" class="btn btn-primary px-4 fw-bold text-uppercase">Start Shopping</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card bg-dark border-secondary shadow-lg">
                <div class="card-header bg-transparent border-secondary py-3">
                    <h5 class="mb-0 text-white text-uppercase fw-bold" style="letter-spacing: 1px;">Order Summary</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-white-50">Subtotal</span>
                        <span class="text-white fw-bold">UGX {{ number_format($total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-white-50">Delivery</span>
                        <span class="text-white fw-bold">Free</span>
                    </div>
                    <hr class="border-secondary mb-4 opacity-25">
                    <div class="d-flex justify-content-between mb-4">
                        <span class="text-white fs-5 fw-bold">Total</span>
                        <span class="text-white fs-5 fw-bold text-danger">UGX {{ number_format($total, 2) }}</span>
                    </div>
                    
                    @if(count($cart) > 0)
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 fw-bold py-3 text-uppercase shadow-sm" style="letter-spacing: 1px;">Proceed to Checkout</a>
                    @else
                        <button class="btn btn-secondary w-100 fw-bold py-3 text-uppercase" disabled>Proceed to Checkout</button>
                    @endif
                    
                    <div class="mt-4 pt-3 border-top border-secondary border-opacity-10">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="rounded-circle bg-success bg-opacity-10 p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#198754" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                            </div>
                            <small class="text-white-50">Secure checkout guaranteed</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .quantity-input::-webkit-inner-spin-button,
    .quantity-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .quantity-input {
        -moz-appearance: textfield;
    }
    .btn-minus:hover, .btn-plus:hover {
        background-color: #DC143C !important;
        border-color: #DC143C !important;
        color: white !important;
    }
    .input-group .btn {
        z-index: 0;
    }
</style>


@endsection
