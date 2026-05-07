@extends('layouts.admin')

@section('title', 'Store Settings')

@section('content')
<div class="card bg-dark border-secondary shadow-sm">
    <div class="card-header bg-black border-secondary py-3">
        <ul class="nav nav-tabs card-header-tabs border-0" id="settingsTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active text-uppercase fw-bold py-3 px-4 border-0 bg-transparent" id="general-tab" data-bs-toggle="tab" href="#general" role="tab">General</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-uppercase fw-bold py-3 px-4 border-0 bg-transparent" id="payment-tab" data-bs-toggle="tab" href="#payment" role="tab">Payment</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-uppercase fw-bold py-3 px-4 border-0 bg-transparent" id="social-tab" data-bs-toggle="tab" href="#social" role="tab">Social Media</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-uppercase fw-bold py-3 px-4 border-0 bg-transparent" id="shipping-tab" data-bs-toggle="tab" href="#shipping" role="tab">Delivery</a>
            </li>
        </ul>
    </div>
    <div class="card-body p-4 p-md-5">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            <div class="tab-content" id="settingsTabContent">
                <!-- General Settings -->
                <div class="tab-pane fade show active" id="general" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-white-50">Store Name</label>
                            <input type="text" name="store_name" class="form-control bg-black border-secondary text-white" value="{{ $settings['store_name'] ?? 'Cycle' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white-50">Store Email</label>
                            <input type="email" name="store_email" class="form-control bg-black border-secondary text-white" value="{{ $settings['store_email'] ?? 'support@cycle.com' }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-white-50">Store Description</label>
                            <textarea name="store_description" class="form-control bg-black border-secondary text-white" rows="3">{{ $settings['store_description'] ?? '' }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white-50">Default Currency</label>
                        <select name="currency" class="form-select bg-black border-secondary text-white">
                                <option value="UGX" {{ ($settings['currency'] ?? 'UGX') == 'UGX' ? 'selected' : '' }}>UGX (Ugandan Shilling)</option>
                                <option value="USD" {{ ($settings['currency'] ?? '') == 'USD' ? 'selected' : '' }}>USD (US Dollar)</option>
                                <option value="KES" {{ ($settings['currency'] ?? '') == 'KES' ? 'selected' : '' }}>KES (Kenyan Shilling)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white-50">Phone Number</label>
                            <input type="text" name="store_phone" class="form-control bg-black border-secondary text-white" value="{{ $settings['store_phone'] ?? '+256 700 000000' }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-white-50">Store Address</label>
                            <textarea name="store_address" class="form-control bg-black border-secondary text-white" rows="2">{{ $settings['store_address'] ?? 'Main Campus Plaza, Block A' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Settings -->
                <div class="tab-pane fade" id="payment" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="enable_paypal" id="enable_paypal" {{ ($settings['enable_paypal'] ?? '') == 'on' ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="enable_paypal">Enable PayPal</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white-50">PayPal Client ID</label>
                            <input type="text" name="paypal_client_id" class="form-control bg-black border-secondary text-white" value="{{ $settings['paypal_client_id'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white-50">PayPal Secret</label>
                            <input type="password" name="paypal_secret" class="form-control bg-black border-secondary text-white" value="{{ $settings['paypal_secret'] ?? '' }}">
                        </div>
                        <hr class="border-secondary opacity-25">
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="enable_cod" id="enable_cod" {{ ($settings['enable_cod'] ?? '') == 'on' ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="enable_cod">Enable Cash on Delivery</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="tab-pane fade" id="social" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-white-50">Facebook URL</label>
                            <input type="url" name="facebook_url" class="form-control bg-black border-secondary text-white" value="{{ $settings['facebook_url'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white-50">Instagram URL</label>
                            <input type="url" name="instagram_url" class="form-control bg-black border-secondary text-white" value="{{ $settings['instagram_url'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white-50">Twitter (X) URL</label>
                            <input type="url" name="twitter_url" class="form-control bg-black border-secondary text-white" value="{{ $settings['twitter_url'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white-50">WhatsApp Number</label>
                            <input type="text" name="whatsapp_number" class="form-control bg-black border-secondary text-white" value="{{ $settings['whatsapp_number'] ?? '' }}">
                        </div>
                    </div>
                </div>

                <!-- Delivery -->
                <div class="tab-pane fade" id="shipping" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-white-50">Flat Delivery Rate (UGX)</label>
                            <input type="number" name="shipping_rate" class="form-control bg-black border-secondary text-white" value="{{ $settings['shipping_rate'] ?? '0' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white-50">Free Delivery Above (UGX)</label>
                            <input type="number" name="free_shipping_threshold" class="form-control bg-black border-secondary text-white" value="{{ $settings['free_shipping_threshold'] ?? '500000' }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5 text-end">
                <button type="submit" class="btn btn-crimson px-5 py-3 fw-bold text-uppercase" style="letter-spacing: 1px;">Save All Settings</button>
            </div>
        </form>
    </div>
</div>

<style>
    .nav-tabs .nav-link {
        color: rgba(255, 255, 255, 0.5) !important;
        border-bottom: 2px solid transparent !important;
    }
    .nav-tabs .nav-link.active {
        color: #fff !important;
        border-bottom: 2px solid #DC143C !important;
    }
    .btn-crimson {
        background-color: #DC143C;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-crimson:hover {
        background-color: #b01030;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 20, 60, 0.3);
    }
</style>
@endsection
