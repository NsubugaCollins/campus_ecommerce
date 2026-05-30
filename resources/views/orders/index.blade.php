@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white mb-0 fw-bold text-uppercase" style="letter-spacing: 1px;">My Orders</h2>
    </div>

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
        <div class="col-12">
            @forelse($orders as $order)
                <div class="card admin-card-custom mb-4 shadow-sm">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center py-3">
                        <div>
                            <span class="text-white-50 small me-3">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-white-50 small">Placed on {{ $order->created_at->format('M d, Y') }}</span>
                        </div>
                        <div>
                            @if($order->status == 'pending')
                                <span class="badge bg-warning text-dark text-uppercase px-3 py-2">Pending</span>
                            @elseif($order->status == 'completed')
                                <span class="badge bg-success text-white text-uppercase px-3 py-2">Completed</span>
                            @elseif($order->status == 'cancelled')
                                <span class="badge bg-danger text-white text-uppercase px-3 py-2">Cancelled</span>
                            @else
                                <span class="badge bg-secondary text-white text-uppercase px-3 py-2">{{ $order->status }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark mb-0 align-middle">
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td class="px-4 py-3 border-secondary" style="width: 80px;">
                                            <div class="rounded overflow-hidden bg-black d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                @if($item->product && $item->product->image_url)
                                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                @else
                                                    <div class="text-white-50" style="font-size: 10px;">No Image</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3 border-secondary">
                                            <h6 class="mb-1 text-white">{{ $item->product->name ?? 'Deleted Product' }}</h6>
                                            <small class="text-white-50">Qty: {{ $item->quantity }} &times; UGX {{ number_format($item->price, 2) }}</small>
                                        </td>
                                        <td class="px-4 py-3 text-end border-secondary">
                                            <span class="fw-bold text-white">UGX {{ number_format($item->quantity * $item->price, 2) }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center py-3">
                        <div>
                            <small class="text-white-50 d-block">Delivery To: <span class="text-white">{{ $order->shipping_address }}</span></small>
                            <small class="text-white-50 d-block">Payment: <span class="text-white text-capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span></small>
                        </div>
                        <div class="text-end d-flex align-items-center gap-3">
                            <div>
                                <span class="text-white-50 me-2">Total Amount:</span>
                                <span class="fs-5 fw-bold text-danger">UGX {{ number_format($order->total_amount, 2) }}</span>
                            </div>
                            @if($order->status == 'pending')
                                <button class="btn btn-sm btn-cancel-order text-uppercase fw-bold px-3 py-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#cancelModal{{ $order->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                    Cancel Order
                                </button>
                            @endif
                            @if(in_array($order->status, ['completed', 'pending']))
                                @if(!$order->rating)
                                    <button class="btn btn-sm btn-outline-warning text-uppercase fw-bold px-3 py-2" data-bs-toggle="modal" data-bs-target="#ratingModal{{ $order->id }}">
                                        Rate Shopping
                                    </button>
                                @else
                                    <div class="text-warning d-flex align-items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="{{ $i <= $order->rating->rating ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                        @endfor
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Cancel Confirmation Modal (pending orders only) --}}
                @if($order->status == 'pending')
                <div class="modal fade" id="cancelModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content admin-card-custom border border-danger border-opacity-25">
                            <div class="modal-header border-secondary">
                                <h5 class="modal-title text-white d-flex align-items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                    Cancel Order
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center py-4">
                                <div class="mb-3">
                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                         style="width:64px;height:64px;background:rgba(220,53,69,0.12);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#dc3545" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                    </div>
                                </div>
                                <p class="text-white mb-1 fw-semibold fs-6">Are you sure you want to cancel this order?</p>
                                <p class="text-white-50 small mb-0">
                                    Order <span class="text-warning fw-bold">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                                    &mdash; UGX {{ number_format($order->total_amount, 2) }}
                                </p>
                                <p class="text-white-50 small mt-2">A cancellation confirmation will be sent to your email.</p>
                            </div>
                            <div class="modal-footer border-secondary justify-content-center gap-3">
                                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Keep Order</button>
                                <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger px-4 fw-bold">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                        Yes, Cancel Order
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Rating Modal --}}
                @if(in_array($order->status, ['completed', 'pending']) && !$order->rating)
                <div class="modal fade" id="ratingModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content admin-card-custom">
                            <div class="modal-header border-secondary">
                                <h5 class="modal-title text-white">Rate Your Experience</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('rating.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <div class="modal-body">
                                    <div class="mb-4 text-center">
                                        <label class="form-label d-block text-white-50 mb-3">How was your shopping experience?</label>
                                        <div class="rating-stars h3">
                                            <input type="radio" name="rating" value="5" id="star5-{{ $order->id }}" class="d-none" required>
                                            <label for="star5-{{ $order->id }}" class="text-secondary cursor-pointer star-label" data-value="5"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg></label>

                                            <input type="radio" name="rating" value="4" id="star4-{{ $order->id }}" class="d-none">
                                            <label for="star4-{{ $order->id }}" class="text-secondary cursor-pointer star-label" data-value="4"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg></label>

                                            <input type="radio" name="rating" value="3" id="star3-{{ $order->id }}" class="d-none">
                                            <label for="star3-{{ $order->id }}" class="text-secondary cursor-pointer star-label" data-value="3"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg></label>

                                            <input type="radio" name="rating" value="2" id="star2-{{ $order->id }}" class="d-none">
                                            <label for="star2-{{ $order->id }}" class="text-secondary cursor-pointer star-label" data-value="2"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg></label>

                                            <input type="radio" name="rating" value="1" id="star1-{{ $order->id }}" class="d-none">
                                            <label for="star1-{{ $order->id }}" class="text-secondary cursor-pointer star-label" data-value="1"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg></label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="comment" class="form-label text-white-50">Comment (Optional)</label>
                                        <textarea name="comment" class="form-control bg-transparent border-0 text-white" rows="3" placeholder="Tell us more about your experience..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer border-secondary">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary px-4">Submit Rating</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            @empty
                <div class="card admin-card-custom text-center py-5">
                    <div class="card-body">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-secondary mb-3"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                        <h4 class="text-white mb-3">You haven't placed any orders yet.</h4>
                        <a href="{{ url('/') }}" class="btn btn-primary px-4 py-2">Start Shopping</a>
                    </div>
                </div>
            @endforelse

            <div class="text-center mt-5 mb-4">
                <a href="{{ route('home') }}" class="btn btn-outline-light px-5 py-3 text-uppercase fw-bold shadow-sm rounded-pill transition-all hover-lift">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .star-label {
        transition: all 0.2s ease;
        display: inline-flex;
        flex-direction: row-reverse;
    }
    .rating-stars {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        gap: 5px;
    }
    .star-label:hover,
    .star-label:hover ~ .star-label,
    input:checked ~ .star-label {
        color: #ffc107 !important;
    }
    .star-label svg {
        fill: transparent;
    }
    .star-label:hover svg,
    .star-label:hover ~ .star-label svg,
    input:checked ~ .star-label svg {
        fill: #ffc107;
    }
    .cursor-pointer {
        cursor: pointer;
    }
    .btn-cancel-order {
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.5);
        background: rgba(220, 53, 69, 0.08);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
    }
    .btn-cancel-order:hover {
        background: rgba(220, 53, 69, 0.2);
        border-color: #dc3545;
        color: #ff6b6b;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.25);
    }
</style>
@endsection
