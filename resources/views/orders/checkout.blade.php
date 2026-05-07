@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center mb-4">
        <h2 class="text-white mb-0 fw-bold text-uppercase" style="letter-spacing: 1px;">Checkout</h2>
    </div>

    <div class="row">
        <!-- Order Summary Column -->
        <div class="col-lg-5 order-lg-2 mb-4">
            <div class="card bg-dark border-secondary shadow-sm h-100">
                <div class="card-header bg-black border-secondary py-3">
                    <h5 class="mb-0 text-white text-uppercase fw-bold">Order Summary</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush bg-transparent mb-3">
                        @foreach($cart as $item)
                            <li class="list-group-item bg-transparent border-secondary px-0 d-flex justify-content-between lh-sm">
                                <div>
                                    <h6 class="my-0 text-white">{{ $item['name'] }}</h6>
                                    <small class="text-white-50">Quantity: {{ $item['quantity'] }}</small>
                                </div>
                                <span class="text-white">UGX {{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="d-flex justify-content-between mb-3 border-top border-secondary pt-3">
                        <span class="text-white-50">Subtotal</span>
                        <span class="text-white fw-bold">UGX {{ number_format($total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-white-50">Delivery</span>
                        <span class="text-white fw-bold">Free</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 border-top border-secondary pt-3">
                        <span class="text-white fs-5 fw-bold">Total</span>
                        <span id="display-total" class="text-white fs-5 fw-bold text-danger" data-base-total="{{ $total }}">UGX {{ number_format($total, 2) }}</span>
                    </div>

                    @if($points > 0)
                    <div class="mt-4 p-3 bg-black border border-secondary rounded">
                        <h6 class="text-white mb-2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-warning me-2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg> Use Your Rewards</h6>
                        <p class="text-white-50 small mb-2">You have <strong>{{ $points }}</strong> points available. (100 pts = UGX 1.00)</p>
                        <div class="input-group input-group-sm mb-2">
                            <input type="number" id="points_to_use_input" name="points_to_use" class="form-control bg-dark border-secondary text-white" placeholder="Enter points to use" min="0" max="{{ $points }}">
                            <button class="btn btn-outline-primary" type="button" id="apply-points">Apply</button>
                        </div>
                        <div id="points-message" class="small text-success d-none"></div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const applyBtn = document.getElementById('apply-points');
                if (applyBtn) {
                    applyBtn.addEventListener('click', function() {
                        const input = document.getElementById('points_to_use_input');
                        const displayTotal = document.getElementById('display-total');
                        const baseTotal = parseFloat(displayTotal.dataset.baseTotal);
                        const pointsMessage = document.getElementById('points-message');
                        const formPointsInput = document.getElementById('form_points_to_use');
                        
                        let points = parseInt(input.value) || 0;
                        const maxPoints = {{ $points }};
                        
                        if (points > maxPoints) points = maxPoints;
                        if (points < 0) points = 0;
                        
                        const discount = points / 100;
                        const newTotal = Math.max(0, baseTotal - discount);
                        
                        displayTotal.innerText = 'UGX ' + newTotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        
                        if (points > 0) {
                            pointsMessage.innerText = `-UGX ${discount.toFixed(2)} discount applied!`;
                            pointsMessage.classList.remove('d-none');
                        } else {
                            pointsMessage.classList.add('d-none');
                        }
                        
                        if (formPointsInput) {
                            formPointsInput.value = points;
                        }
                    });
                }
            });
        </script>

        <!-- Checkout Form Column -->
        <div class="col-lg-7 order-lg-1">
            <div class="card bg-dark border-secondary shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <h4 class="text-white mb-4">Billing & Delivery Details</h4>
                    
                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="points_to_use" id="form_points_to_use" value="0">
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label text-white-50">Full Name</label>
                                <input type="text" class="form-control bg-black border-secondary text-white" value="{{ Auth::user()->name }}" readonly>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label text-white-50">Email</label>
                                <input type="email" class="form-control bg-black border-secondary text-white" value="{{ Auth::user()->email }}" readonly>
                            </div>
                            
                            <div class="col-12">
                                <label for="shipping_address" class="form-label text-white-50">Delivery Address <span class="text-danger">*</span></label>
                                <textarea name="shipping_address" id="shipping_address" class="form-control bg-black border-secondary text-white @error('shipping_address') is-invalid @enderror" rows="3" required placeholder="Enter your full delivery address..."></textarea>
                                @error('shipping_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="border-secondary my-4">

                            <h4 class="text-white mb-3">Payment Information</h4>
                            
                            <div class="col-12 mb-4">
                                <label class="form-label text-white-50">Select Payment Method <span class="text-danger">*</span></label>
                                <div class="form-check mb-2">
                                    <input id="payment_cod" name="payment_method" type="radio" class="form-check-input" value="cash_on_delivery" checked required>
                                    <label class="form-check-label text-white" for="payment_cod">Cash on Delivery</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input id="payment_mobile" name="payment_method" type="radio" class="form-check-input" value="mobile_money" required>
                                    <label class="form-check-label text-white" for="payment_mobile">Mobile Money (M-Pesa)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input id="payment_paypal" name="payment_method" type="radio" class="form-check-input" value="paypal" required>
                                    <label class="form-check-label text-white" for="payment_paypal">
                                        <span class="text-primary fw-bold">Pay</span><span class="text-info fw-bold">Pal</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input id="payment_card" name="payment_method" type="radio" class="form-check-input" value="credit_card" required disabled>
                                    <label class="form-check-label text-white-50" for="payment_card">Credit/Debit Card (Coming Soon)</label>
                                </div>

                                @error('payment_method')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <button class="btn btn-primary w-100 py-3 fw-bold text-uppercase" type="submit" style="letter-spacing: 1px;">
                                Place Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
