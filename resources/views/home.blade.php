@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Welcome Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-dark border-secondary shadow-sm overflow-hidden" style="position: relative;">
                <div style="position: absolute; top: 0; right: 0; width: 150px; height: 100%; background: linear-gradient(90deg, transparent, rgba(220, 20, 60, 0.1));"></div>
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-4" style="width: 80px; height: 80px; font-weight: bold; font-size: 2rem; box-shadow: 0 0 20px rgba(184, 115, 51, 0.3);">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-white fw-bold mb-1">Welcome back, {{ explode(' ', $user->name)[0] }}!</h2>
                        <p class="text-white-50 mb-0">Here's what's happening with your account today.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show bg-success border-success text-white" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Quick Stats -->
        <div class="col-lg-8">
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card bg-dark border-secondary h-100 shadow-sm text-center py-4 hover-lift">
                        <div class="card-body">
                            <h3 class="text-white fw-bold display-5 mb-2">{{ $totalOrders }}</h3>
                            <p class="text-white-50 text-uppercase small letter-spacing-1 mb-0">Total Orders</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-dark border-secondary h-100 shadow-sm text-center py-4 hover-lift">
                        <div class="card-body">
                            <h3 class="text-warning fw-bold display-5 mb-2">{{ $pendingOrders }}</h3>
                            <p class="text-white-50 text-uppercase small letter-spacing-1 mb-0">Pending Orders</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-dark border-secondary h-100 shadow-sm text-center py-4 hover-lift" style="border-bottom: 3px solid #ffc107 !important;">
                        <div class="card-body">
                            <h3 class="text-warning fw-bold display-5 mb-2">{{ $user->points }}</h3>
                            <p class="text-white-50 text-uppercase small letter-spacing-1 mb-0">Reward Points</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-dark border-secondary h-100 shadow-sm text-center py-4 hover-lift">
                        <div class="card-body">
                            <h3 class="text-white fw-bold display-6 mb-2 mt-2">{{ $user->created_at->format('M Y') }}</h3>
                            <p class="text-white-50 text-uppercase small letter-spacing-1 mb-0">Member Since</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="card bg-dark border-secondary shadow-sm">
                <div class="card-header bg-black border-secondary d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-white fw-bold text-uppercase" style="letter-spacing: 1px;">Recent Orders</h5>
                    @if($recentOrders->count() > 0)
                        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <tbody>
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <span class="text-white-50 small d-block">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                                            <span class="text-white">{{ $order->created_at->format('M d, Y') }}</span>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                @foreach($order->items->take(3) as $item)
                                                    <div class="rounded overflow-hidden bg-black d-flex align-items-center justify-content-center me-2 border border-secondary" style="width: 40px; height: 40px;">
                                                        <img src="{{ asset('images/' . ($item->product->image ?? 'placeholder.jpg')) }}" alt="Product" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                    </div>
                                                @endforeach
                                                @if($order->items->count() > 3)
                                                    <div class="rounded bg-secondary d-flex align-items-center justify-content-center text-white small" style="width: 40px; height: 40px;">
                                                        +{{ $order->items->count() - 3 }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3 text-end">
                                            <span class="fw-bold text-white d-block">UGX {{ number_format($order->total_amount, 2) }}</span>
                                            @if($order->status == 'pending')
                                                <span class="badge bg-warning text-dark text-uppercase" style="font-size: 0.65rem;">Pending</span>
                                            @elseif($order->status == 'completed')
                                                <span class="badge bg-success text-white text-uppercase" style="font-size: 0.65rem;">Completed</span>
                                            @else
                                                <span class="badge bg-secondary text-white text-uppercase" style="font-size: 0.65rem;">{{ $order->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-secondary mb-3"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                                            <h6 class="text-white-50 mb-0">No orders yet. Start exploring our store!</h6>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="col-lg-4">
            <div class="card bg-dark border-secondary shadow-sm mb-4">
                <div class="card-header bg-transparent border-secondary py-3">
                    <h5 class="mb-0 text-white fw-bold text-uppercase" style="letter-spacing: 1px;">Quick Actions</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush bg-transparent">
                        <a href="{{ url('/') }}" class="list-group-item list-group-item-action bg-transparent text-white border-secondary py-3 d-flex align-items-center">
                            <div class="rounded bg-primary bg-opacity-25 p-2 me-3 text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            </div>
                            Start Shopping
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ms-auto text-secondary"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                        <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action bg-transparent text-white border-secondary py-3 d-flex align-items-center">
                            <div class="rounded bg-info bg-opacity-25 p-2 me-3 text-info">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            </div>
                            My Order History
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ms-auto text-secondary"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                        <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action bg-transparent text-white border-secondary py-3 d-flex align-items-center">
                            <div class="rounded bg-warning bg-opacity-25 p-2 me-3 text-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            </div>
                            Account Settings
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ms-auto text-secondary"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Referral Program Card -->
            <div class="card bg-dark border-secondary shadow-sm mb-4">
                <div class="card-header bg-transparent border-secondary py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white fw-bold text-uppercase" style="letter-spacing: 1px;">Referral Program</h5>
                    <button class="btn btn-sm btn-link text-warning text-decoration-none p-0" data-bs-toggle="modal" data-bs-target="#rewardModal">How it works?</button>
                </div>
                <div class="card-body">
                    <p class="text-white-50 small mb-2">Share your link and earn <strong class="text-white">50 pts</strong> for every friend who signs up!</p>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control bg-black border-secondary text-white" value="{{ url('/register?ref=' . $user->referral_code) }}" id="dashShareLink" readonly style="font-size:.8rem;">
                        <button class="btn btn-primary fw-bold" type="button" id="dashCopyBtn" onclick="dashCopyLink()">Copy Link</button>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-white-50 small">Code: <strong class="text-white" style="letter-spacing:2px;">{{ $user->referral_code }}</strong></span>
                        <a href="{{ route('user.profile') }}" class="btn btn-sm btn-link text-warning text-decoration-none p-0 small">View History &rarr;</a>
                    </div>
                </div>
            </div>

            <!-- Promotional or Info Banner -->
            <div class="card border-0 overflow-hidden text-white shadow-sm" style="background: linear-gradient(135deg, #DC143C, #B87333);">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-3">Cycle Rewards Plus</h5>
                    <p class="small mb-3" style="opacity: 0.9;">Earn points for every purchase and redeem them for amazing discounts on your next order.</p>
                    <button class="btn btn-outline-light btn-sm px-4 fw-bold text-uppercase" data-bs-toggle="modal" data-bs-target="#rewardModal">Learn More</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reward Info Modal -->
<div class="modal fade" id="rewardModal" tabindex="-1" aria-labelledby="rewardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary text-white">
            <div class="modal-header border-secondary bg-black">
                <h5 class="modal-title fw-bold text-uppercase" id="rewardModalLabel" style="letter-spacing: 1px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-warning me-2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                    How to Earn Rewards
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-circle bg-primary bg-opacity-25 p-2 me-3 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                        </div>
                        <h6 class="mb-0 fw-bold">Refer Friends</h6>
                    </div>
                    <p class="text-white-50 small ps-5">Give friends your referral code. You get <strong>50 points</strong> when they join, and they get <strong>20 points</strong> instantly!</p>
                </div>

                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-circle bg-success bg-opacity-25 p-2 me-3 text-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"></path><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        </div>
                        <h6 class="mb-0 fw-bold">Make Purchases</h6>
                    </div>
                    <p class="text-white-50 small ps-5">Earn <strong>10 points for every UGX 1</strong> spent on our store. The more you shop, the more you earn.</p>
                </div>

                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-circle bg-info bg-opacity-25 p-2 me-3 text-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                        <h6 class="mb-0 fw-bold">Daily Login</h6>
                    </div>
                    <p class="text-white-50 small ps-5">Just sign in once a day and receive <strong>10 points</strong> automatically!</p>
                </div>

                <hr class="border-secondary opacity-25">

                <div class="p-3 bg-black border border-secondary rounded">
                    <h6 class="text-white fw-bold mb-2">Redemption:</h6>
                    <p class="text-white-50 small mb-0"><strong>100 points = UGX 1.00 Discount</strong></p>
                    <p class="text-white-50 small">Redeem your points at the checkout page to save money on your orders!</p>
                </div>
            </div>
            <div class="modal-footer border-secondary bg-black">
                <button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal">Got it!</button>
            </div>
        </div>
    </div>
</div>

<script>
    function dashCopyLink() {
        const el = document.getElementById('dashShareLink');
        el.select();
        navigator.clipboard.writeText(el.value).then(() => {
            const btn = document.getElementById('dashCopyBtn');
            const orig = btn.innerText;
            const origClass = btn.className;
            btn.innerText = '✓ Copied!';
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-success');
            setTimeout(() => { btn.innerText = orig; btn.className = origClass; }, 2000);
        });
    }
</script>

<style>
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.4) !important;
    }
    .letter-spacing-1 {
        letter-spacing: 1px;
    }
</style>
@endsection
