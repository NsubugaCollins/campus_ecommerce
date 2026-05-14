@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="text-white fw-bold text-uppercase mb-0" style="letter-spacing: 1px;">My Trade-Ins</h2>
            <p class="text-muted small mb-0">Track and manage your product sales to the platform.</p>
        </div>
        <a href="{{ route('user-sales.create') }}" class="btn btn-primary px-4 fw-bold text-uppercase shadow-sm">
            <i class="me-2">+</i> Sell New Product
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4 py-3">
            <i class="me-2">✅</i> {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        @forelse($sales as $sale)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 overflow-hidden" style="background: rgba(30, 30, 30, 0.6); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); transition: transform 0.3s ease;">
                    <style>
                        .trade-in-card:hover { transform: translateY(-5px); }
                    </style>
                    <div class="position-relative trade-in-card">
                        @if($sale->images->count() > 0)
                            <img src="{{ $sale->images->first()->image_url }}" class="card-img-top" alt="{{ $sale->product_name }}" style="height: 220px; object-fit: cover;">
                        @else
                            <div class="bg-dark d-flex align-items-center justify-content-center" style="height: 220px;">
                                <span class="text-muted small text-uppercase fw-bold">No Image Available</span>
                            </div>
                        @endif
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge rounded-pill shadow-sm 
                                @if($sale->status == 'pending') bg-warning text-dark
                                @elseif($sale->status == 'under_review') bg-info text-dark
                                @elseif($sale->status == 'offer_made') bg-primary
                                @elseif($sale->status == 'accepted') bg-success
                                @elseif($sale->status == 'rejected') bg-danger
                                @elseif($sale->status == 'completed') bg-secondary
                                @else bg-dark @endif
                                text-uppercase px-3 py-2" style="font-size: 0.65rem; letter-spacing: 1px;">
                                {{ str_replace('_', ' ', $sale->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <h5 class="text-white fw-bold mb-1">{{ $sale->product_name }}</h5>
                            <span class="badge bg-dark text-muted small fw-normal px-0">{{ $sale->category }} • {{ $sale->condition }}</span>
                        </div>
                        
                        <div class="row g-0 mb-4 py-3 border-top border-bottom border-secondary border-opacity-10">
                            <div class="col-6">
                                <small class="text-white-50 d-block text-uppercase fw-bold mb-1" style="font-size: 0.65rem;">Expected</small>
                                <span class="text-white fw-bold">UGX {{ number_format($sale->expected_price) }}</span>
                            </div>
                            @if($sale->offered_price)
                            <div class="col-6 border-start border-secondary border-opacity-10 ps-3">
                                <small class="text-primary d-block text-uppercase fw-bold mb-1" style="font-size: 0.65rem;">Our Offer</small>
                                <span class="text-primary fw-bold">UGX {{ number_format($sale->offered_price) }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('user-sales.show', $sale) }}" class="btn btn-outline-light btn-sm py-2">View Details</a>
                            
                            @if($sale->status == 'offer_made')
                                <div class="d-flex gap-2">
                                    <form action="{{ route('user-sales.accept', $sale) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm w-100 fw-bold py-2 shadow-sm">Accept</button>
                                    </form>
                                    <form action="{{ route('user-sales.reject', $sale) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100 fw-bold py-2">Reject</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="mb-4 opacity-25">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><path d="M3 6h18"></path><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                </div>
                <h4 class="text-white fw-bold">No trade-in requests yet</h4>
                <p class="text-muted mx-auto" style="max-width: 400px;">Got gadgets or items you no longer use? Submit them for appraisal and get paid fast!</p>
                <a href="{{ route('user-sales.create') }}" class="btn btn-primary px-5 mt-3 fw-bold text-uppercase">Start Selling Now</a>
            </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $sales->links() }}
    </div>
</div>
@endsection
