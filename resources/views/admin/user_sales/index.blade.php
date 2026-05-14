@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-white mb-0">User Trade-Ins</h2>
            <p class="text-muted small">Appraise and manage products submitted by users for sale.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success bg-dark text-success border-success py-3 shadow-sm">
            <i class="me-2">✅</i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-lg" style="background: rgba(30, 30, 30, 0.6); backdrop-filter: blur(15px); border-radius: 1rem;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0" style="background: transparent;">
                    <thead style="border-bottom: 2px solid rgba(184, 115, 51, 0.5);">
                        <tr>
                            <th class="px-4 py-3 border-0 text-secondary text-uppercase small" style="letter-spacing: 1px;">User</th>
                            <th class="px-4 py-3 border-0 text-secondary text-uppercase small" style="letter-spacing: 1px;">Product</th>
                            <th class="px-4 py-3 border-0 text-secondary text-center text-uppercase small" style="letter-spacing: 1px;">Status</th>
                            <th class="px-4 py-3 border-0 text-secondary text-uppercase small" style="letter-spacing: 1px;">Expected Price</th>
                            <th class="px-4 py-3 border-0 text-secondary text-uppercase small" style="letter-spacing: 1px;">Our Offer</th>
                            <th class="px-4 py-3 border-0 text-secondary text-end text-uppercase small" style="letter-spacing: 1px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                        <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                            <td class="px-4 py-3 border-0 align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 36px; height: 36px; font-weight: bold;">
                                        {{ substr($sale->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-white fw-bold">{{ $sale->user->name }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $sale->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 border-0 align-middle">
                                <div class="text-white fw-bold">{{ $sale->product_name }}</div>
                                <div class="text-muted small">{{ $sale->category }} <span class="mx-1 opacity-25">|</span> {{ $sale->condition }}</div>
                            </td>
                            <td class="px-4 py-3 border-0 align-middle text-center">
                                <span class="badge rounded-pill shadow-sm 
                                    @if($sale->status == 'pending') bg-warning text-dark
                                    @elseif($sale->status == 'under_review') bg-info text-dark
                                    @elseif($sale->status == 'offer_made') bg-primary
                                    @elseif($sale->status == 'accepted') bg-success
                                    @elseif($sale->status == 'rejected') bg-danger
                                    @elseif($sale->status == 'completed') bg-secondary
                                    @else bg-dark @endif
                                    text-uppercase px-3 py-2" style="font-size: 0.6rem; letter-spacing: 0.5px;">
                                    {{ str_replace('_', ' ', $sale->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border-0 align-middle text-white-50">UGX {{ number_format($sale->expected_price) }}</td>
                            <td class="px-4 py-3 border-0 align-middle">
                                @if($sale->offered_price)
                                    <span class="text-primary fw-bold">UGX {{ number_format($sale->offered_price) }}</span>
                                @else
                                    <span class="text-muted italic small">No offer yet</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 border-0 align-middle text-end">
                                <a href="{{ route('admin.user-sales.show', $sale) }}" class="btn btn-sm btn-outline-primary px-3 fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Review</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-5 text-center text-muted border-0">
                                <div class="py-4">
                                    <i class="mb-3 d-block opacity-25">📦</i>
                                    No trade-in requests found in the system.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-4 d-flex justify-content-center">
        {{ $sales->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
