@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('user-sales.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to My Trade-Ins
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4 py-3">
            <i class="me-2">✅</i> {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        <!-- Left: Images -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm overflow-hidden" style="background: rgba(30, 30, 30, 0.6); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 1rem;">
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @forelse($userSale->images as $index => $image)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                <img src="{{ $image->image_url }}" class="d-block w-100" alt="Product Image" style="height: 500px; object-fit: contain; background: #000;">
                            </div>
                        @empty
                            <div class="carousel-item active">
                                <div class="d-flex align-items-center justify-content-center bg-dark text-muted" style="height: 500px;">
                                    No images available
                                </div>
                            </div>
                        @endforelse
                    </div>
                    @if($userSale->images->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                        </button>
                    @endif
                </div>
                @if($userSale->images->count() > 1)
                <div class="card-footer bg-dark border-0 p-3">
                    <div class="d-flex gap-2 overflow-auto pb-2">
                        @foreach($userSale->images as $index => $image)
                            <img src="{{ $image->image_url }}" class="rounded cursor-pointer border {{ $index == 0 ? 'border-primary' : 'border-secondary' }}" 
                                 style="width: 60px; height: 60px; object-fit: cover;" 
                                 data-bs-target="#productCarousel" data-bs-slide-to="{{ $index }}">
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Right: Details -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100" style="background: rgba(30, 30, 30, 0.6); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 1rem;">
                <div class="card-body p-4 p-md-5">
                    <div class="mb-4">
                        <span class="badge rounded-pill shadow-sm 
                            @if($userSale->status == 'pending') bg-warning text-dark
                            @elseif($userSale->status == 'under_review') bg-info text-dark
                            @elseif($userSale->status == 'offer_made') bg-primary
                            @elseif($userSale->status == 'accepted') bg-success
                            @elseif($userSale->status == 'rejected') bg-danger
                            @elseif($userSale->status == 'completed') bg-secondary
                            @else bg-dark @endif
                            text-uppercase px-3 py-2 mb-3" style="font-size: 0.65rem; letter-spacing: 1px;">
                            {{ str_replace('_', ' ', $userSale->status) }}
                        </span>
                        <h2 class="text-white fw-bold mb-1">{{ $userSale->product_name }}</h2>
                        <p class="text-muted small text-uppercase fw-bold">{{ $userSale->category }} <span class="mx-2 opacity-25">|</span> {{ $userSale->condition }}</p>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="p-3 rounded bg-dark border border-secondary border-opacity-10 shadow-sm">
                                <small class="text-white-50 d-block text-uppercase fw-bold mb-1" style="font-size: 0.6rem; letter-spacing: 0.5px;">Your Expected Price</small>
                                <span class="text-white fw-bold h5 mb-0">UGX {{ number_format($userSale->expected_price) }}</span>
                            </div>
                        </div>
                        @if($userSale->offered_price)
                        <div class="col-6">
                            <div class="p-3 rounded bg-primary bg-opacity-10 border border-primary border-opacity-25 shadow-sm">
                                <small class="text-primary d-block text-uppercase fw-bold mb-1" style="font-size: 0.6rem; letter-spacing: 0.5px;">Our Final Offer</small>
                                <span class="text-primary fw-bold h5 mb-0">UGX {{ number_format($userSale->offered_price) }}</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h6 class="text-white-50 small text-uppercase fw-bold mb-2" style="font-size: 0.65rem; letter-spacing: 1px;">Product Description</h6>
                        <div class="p-3 rounded bg-dark bg-opacity-50 text-white-50 small" style="white-space: pre-line; line-height: 1.6;">
                            {{ $userSale->description }}
                        </div>
                    </div>

                    @if($userSale->admin_notes)
                    <div class="mb-4 p-3 rounded bg-info bg-opacity-10 border border-info border-opacity-25">
                        <h6 class="text-info small text-uppercase fw-bold mb-2" style="font-size: 0.65rem; letter-spacing: 1px;">Message from Admin</h6>
                        <p class="text-white-50 small mb-0">{{ $userSale->admin_notes }}</p>
                    </div>
                    @endif

                    @if($userSale->status == 'offer_made')
                    <div class="p-4 rounded bg-primary bg-opacity-10 border border-primary border-opacity-25 mb-4 shadow-sm">
                        <div class="d-flex gap-3 mb-3">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="text-white small">💰</i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="text-white fw-bold mb-1">Appraisal Complete</h6>
                                <p class="text-white-50 small mb-0">We have reviewed your item and made an offer. Accept it to proceed with the transaction.</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <form action="{{ route('user-sales.accept', $userSale) }}" method="POST" class="flex-grow-1">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 py-2 fw-bold text-uppercase shadow-sm">Accept Offer</button>
                            </form>
                            <form action="{{ route('user-sales.reject', $userSale) }}" method="POST" class="flex-grow-1">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100 py-2 fw-bold text-uppercase">Reject</button>
                            </form>
                        </div>
                    </div>
                    @elseif($userSale->status == 'accepted')
                    <div class="p-4 rounded bg-success bg-opacity-10 border border-success border-opacity-25 shadow-sm mb-4">
                        <h6 class="text-success fw-bold mb-2"><i class="me-2">🤝</i> Offer Accepted</h6>
                        <p class="text-white-50 small mb-0">Thank you! Our fulfillment team will contact you within 24 hours to arrange item pickup and payment. Keep your phone reachable.</p>
                    </div>
                    @elseif($userSale->status == 'pending')
                    <div class="p-4 rounded bg-warning bg-opacity-10 border border-warning border-opacity-25 shadow-sm mb-4">
                        <h6 class="text-warning fw-bold mb-2"><i class="me-2">⏳</i> Under Review</h6>
                        <p class="text-white-50 small mb-0">Our team is currently appraising your item. You'll receive a notification and an offer here soon.</p>
                    </div>
                    @endif

                    <div class="mt-auto pt-4 border-top border-secondary border-opacity-10 text-center">
                        <small class="text-muted">Submitted on {{ $userSale->created_at->format('M d, Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
