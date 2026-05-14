@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('admin.user-sales.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to Trade-Ins
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success bg-dark text-success border-success py-3 shadow-sm mb-4">
            <i class="me-2">✅</i> {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        <!-- Left: Product Info & Images -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg mb-4" style="background: rgba(30, 30, 30, 0.6); backdrop-filter: blur(15px); border-radius: 1rem; border: 1px solid rgba(255,255,255,0.05);">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h2 class="text-white fw-bold mb-1">{{ $userSale->product_name }}</h2>
                            <p class="text-muted mb-0 small text-uppercase fw-bold">{{ $userSale->category }} <span class="mx-2 opacity-25">|</span> Condition: <span class="text-white">{{ $userSale->condition }}</span></p>
                        </div>
                        <span class="badge rounded-pill shadow-sm 
                            @if($userSale->status == 'pending') bg-warning text-dark
                            @elseif($userSale->status == 'under_review') bg-info text-dark
                            @elseif($userSale->status == 'offer_made') bg-primary
                            @elseif($userSale->status == 'accepted') bg-success
                            @elseif($userSale->status == 'rejected') bg-danger
                            @elseif($userSale->status == 'completed') bg-secondary
                            @else bg-dark @endif
                            px-3 py-2 text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">
                            {{ str_replace('_', ' ', $userSale->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Image Gallery -->
                    <div class="row g-3 mb-5">
                        @forelse($userSale->images as $image)
                        <div class="col-md-4 col-sm-6">
                            <div class="position-relative group overflow-hidden rounded shadow-sm border border-secondary border-opacity-10" style="height: 200px;">
                                <a href="{{ $image->image_url }}" target="_blank" class="d-block h-100">
                                    <img src="{{ $image->image_url }}" class="img-fluid w-100 h-100" style="object-fit: cover; transition: transform 0.3s ease;">
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5 bg-dark rounded border border-secondary border-opacity-10">
                            <span class="text-muted">No images provided by user.</span>
                        </div>
                        @endforelse
                    </div>

                    <div class="mb-5">
                        <h6 class="text-secondary text-uppercase small fw-bold mb-3" style="letter-spacing: 1px;">User's Description & Notes</h6>
                        <div class="p-4 rounded bg-black bg-opacity-30 text-white-50 shadow-inner" style="white-space: pre-line; line-height: 1.6; border: 1px solid rgba(255,255,255,0.03);">
                            {{ $userSale->description }}
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-4 rounded bg-dark border border-secondary border-opacity-10 shadow-sm h-100">
                                <small class="text-secondary d-block text-uppercase fw-bold mb-2" style="font-size: 0.65rem; letter-spacing: 1px;">Submitted By</small>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">
                                        {{ substr($userSale->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-white fw-bold h6 mb-0">{{ $userSale->user->name }}</div>
                                        <div class="text-muted small">{{ $userSale->user->email }}</div>
                                        @if($userSale->user->phone)
                                            <div class="text-muted small mt-1">📞 {{ $userSale->user->phone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 rounded bg-dark border border-secondary border-opacity-10 shadow-sm h-100 d-flex flex-column justify-content-center">
                                <small class="text-secondary d-block text-uppercase fw-bold mb-2" style="font-size: 0.65rem; letter-spacing: 1px;">User's Asking Price</small>
                                <div class="text-white fw-bold h4 mb-0">UGX {{ number_format($userSale->expected_price) }}</div>
                                <div class="text-muted small mt-1 italic">Target price set by user</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Appraisal & Actions -->
        <div class="col-lg-4">
            <!-- Action Card -->
            <div class="card border-0 shadow-lg mb-4" style="background: rgba(30, 30, 30, 0.6); backdrop-filter: blur(15px); border-radius: 1rem; border: 1px solid rgba(255,255,255,0.05);">
                <div class="card-body p-4">
                    <h5 class="text-white fw-bold mb-4 d-flex align-items-center">
                        <span class="me-2 text-primary">⚖️</span> Appraisal & Offer
                    </h5>

                    <form action="{{ route('admin.user-sales.make-offer', $userSale) }}" method="POST" class="mb-5">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label for="offered_price" class="form-label text-secondary small text-uppercase fw-bold" style="letter-spacing: 0.5px;">Offer Amount (UGX)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-secondary">UGX</span>
                                <input type="number" class="form-control bg-dark border-secondary text-white fw-bold py-2" id="offered_price" name="offered_price" value="{{ $userSale->offered_price ?? $userSale->expected_price }}" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="admin_notes" class="form-label text-secondary small text-uppercase fw-bold" style="letter-spacing: 0.5px;">Message to User</label>
                            <textarea class="form-control bg-dark border-secondary text-white small" id="admin_notes" name="admin_notes" rows="5" placeholder="Explain your appraisal or provide instructions for the user...">{{ $userSale->admin_notes }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold text-uppercase shadow-sm" style="letter-spacing: 1px;">
                            {{ $userSale->offered_price ? 'Update Offer' : 'Send Initial Offer' }}
                        </button>
                    </form>

                    <hr class="border-secondary border-opacity-10 mb-5">

                    <h6 class="text-secondary text-uppercase small fw-bold mb-3" style="letter-spacing: 1px;">Override Status</h6>
                    <form action="{{ route('admin.user-sales.update-status', $userSale) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <select class="form-select bg-dark border-secondary text-white py-2" name="status" required>
                                <option value="pending" {{ $userSale->status == 'pending' ? 'selected' : '' }}>Pending Review</option>
                                <option value="under_review" {{ $userSale->status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                <option value="offer_made" {{ $userSale->status == 'offer_made' ? 'selected' : '' }}>Offer Made</option>
                                <option value="accepted" {{ $userSale->status == 'accepted' ? 'selected' : '' }}>User Accepted</option>
                                <option value="rejected" {{ $userSale->status == 'rejected' ? 'selected' : '' }}>User Rejected</option>
                                <option value="completed" {{ $userSale->status == 'completed' ? 'selected' : '' }}>Bought (Completed)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-outline-secondary w-100 py-2 fw-bold text-uppercase small" style="letter-spacing: 0.5px;">
                            Update Status Only
                        </button>
                    </form>
                </div>
            </div>

            <!-- Guidelines -->
            <div class="card border-0 shadow-sm" style="background: rgba(30, 30, 30, 0.4); border-radius: 1rem; border: 1px solid rgba(255,255,255,0.03);">
                <div class="card-body p-4">
                    <h6 class="text-white-50 fw-bold mb-3 small text-uppercase">Trade-In Guidelines</h6>
                    <div class="small text-muted" style="line-height: 1.8;">
                        <div class="d-flex mb-2">
                            <span class="me-2">1.</span>
                            <span>Verify the condition matches the description in photos.</span>
                        </div>
                        <div class="d-flex mb-2">
                            <span class="me-2">2.</span>
                            <span>Check market value before making an offer.</span>
                        </div>
                        <div class="d-flex mb-2">
                            <span class="me-2">3.</span>
                            <span>Upon <strong>Acceptance</strong>, contact the user via phone/email to collect the item.</span>
                        </div>
                        <div class="d-flex mb-0">
                            <span class="me-2">4.</span>
                            <span>Mark as <strong>Completed</strong> only after physical inspection and payment.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
