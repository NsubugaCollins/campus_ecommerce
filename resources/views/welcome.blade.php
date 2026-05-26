@extends('layouts.app')

@section('content')


<div class="container py-4">
    <!-- Hero Section (Jumia Style Grid) -->
    <div class="row g-3 mb-5">
        <!-- Left Sidebar: Categories -->
        <div class="col-lg-2 d-none d-lg-block">
            <div class="category-menu py-2">
                <h6 class="px-3 pt-2 pb-1 text-uppercase text-secondary fw-bold" style="font-size: 0.8rem;">Categories</h6>
                <div class="nav flex-column">
                    @php
                        $icons = [
                            'Electronics' => '💻',
                            'Furniture' => '🛋️',
                            'Beddings' => '🛏️',
                            'Fashion' => '👗',
                            'Accessories' => '👜',
                            'Beauty' => '💄',
                            'Scholastic Materials' => '📚',
                            'Sporting Goods' => '⚽',
                            'Grocery' => '🛒',
                            'Kitchenware' => '🍳',
                        ];
                    @endphp
                    @foreach($categories as $category)
                        <a href="{{ route('product.category', $category) }}" class="nav-link">
                            <i class="me-2">{{ $icons[$category] ?? '📦' }}</i> {{ $category }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

<style>
    .hero-carousel-text {
        position: relative;
        border-radius: 0.5rem;
        overflow: hidden;
        height: 450px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        border: 1px solid rgba(255, 255, 255, 0.05);
        /* Dark Theme intuitive & attractive gradient background using RGB */
        background: linear-gradient(135deg, rgb(28, 10, 36) 0%, rgb(64, 12, 38) 35%, rgb(14, 30, 69) 70%, rgb(48, 18, 12) 100%);
        background-size: 400% 400%;
        animation: heroGradient 15s ease infinite;
    }

    @keyframes heroGradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    [data-bs-theme="light"] .hero-carousel-text {
        /* Light Theme intuitive & attractive gradient background using RGB */
        background: linear-gradient(135deg, rgb(255, 240, 245) 0%, rgb(240, 243, 255) 40%, rgb(249, 240, 255) 75%, rgb(255, 245, 235) 100%);
        background-size: 400% 400%;
        animation: heroGradient 15s ease infinite;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    /* Floating Ambient Glow Orbs using RGB */
    .glow-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.32;
        z-index: 1;
        pointer-events: none;
    }
    
    .orb-1 {
        width: 280px;
        height: 280px;
        background: rgb(220, 20, 60); /* Crimson */
        top: -60px;
        left: -60px;
        animation: floatOrb1 18s infinite alternate ease-in-out;
    }
    
    .orb-2 {
        width: 320px;
        height: 320px;
        background: rgb(184, 115, 51); /* Copper */
        bottom: -90px;
        right: -90px;
        animation: floatOrb2 24s infinite alternate ease-in-out;
    }

    .orb-3 {
        width: 220px;
        height: 220px;
        background: rgb(0, 102, 204); /* Deep Blue */
        top: 40%;
        left: 30%;
        animation: floatOrb3 20s infinite alternate ease-in-out;
    }

    [data-bs-theme="light"] .glow-orb {
        opacity: 0.45;
        filter: blur(90px);
    }

    @keyframes floatOrb1 {
        0% { transform: translate(0, 0) scale(1); }
        100% { transform: translate(120px, 60px) scale(1.25); }
    }
    
    @keyframes floatOrb2 {
        0% { transform: translate(0, 0) scale(1.1); }
        100% { transform: translate(-140px, -70px) scale(0.85); }
    }

    @keyframes floatOrb3 {
        0% { transform: translate(0, 0) scale(0.9); }
        100% { transform: translate(80px, -60px) scale(1.15); }
    }

    .text-hero-container {
        display: grid;
        grid-template-columns: 1fr;
        grid-template-rows: 1fr;
        align-items: center;
        justify-items: center;
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        z-index: 2;
        pointer-events: none;
    }

    .text-slide {
        grid-column: 1;
        grid-row: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        opacity: 0;
        width: 100%;
        text-align: center;
        padding: 2rem;
    }

    /* Animation cycles */
    .slide-1 {
        animation: slideShow1 12s infinite ease-in-out;
    }
    .slide-2 {
        animation: slideShow2 12s infinite ease-in-out;
    }
    .slide-3 {
        animation: slideShow3 12s infinite ease-in-out;
    }

    @keyframes slideShow1 {
        0% { opacity: 0; transform: scale(0.92) translateY(20px); filter: blur(8px); }
        5% { opacity: 1; transform: scale(1) translateY(0); filter: blur(0); }
        28% { opacity: 1; transform: scale(1) translateY(0); filter: blur(0); }
        33% { opacity: 0; transform: scale(1.08) translateY(-20px); filter: blur(8px); }
        100% { opacity: 0; }
    }

    @keyframes slideShow2 {
        0% { opacity: 0; }
        33% { opacity: 0; transform: scale(0.92) translateY(20px); filter: blur(8px); }
        38% { opacity: 1; transform: scale(1) translateY(0); filter: blur(0); }
        61% { opacity: 1; transform: scale(1) translateY(0); filter: blur(0); }
        66% { opacity: 0; transform: scale(1.08) translateY(-20px); filter: blur(8px); }
        100% { opacity: 0; }
    }

    @keyframes slideShow3 {
        0% { opacity: 0; }
        66% { opacity: 0; transform: scale(0.92) translateY(20px); filter: blur(8px); }
        71% { opacity: 1; transform: scale(1) translateY(0); filter: blur(0); }
        94% { opacity: 1; transform: scale(1) translateY(0); filter: blur(0); }
        99% { opacity: 0; transform: scale(1.08) translateY(-20px); filter: blur(8px); }
        100% { opacity: 0; }
    }

    .subtitle-line {
        display: block;
        text-transform: uppercase;
        font-family: 'Outfit', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: 5px;
        color: #B87333;
        margin-bottom: 0.75rem;
        text-shadow: 0 2px 10px rgba(0,0,0,0.5);
    }

    [data-bs-theme="light"] .subtitle-line {
        color: #A05E24;
        text-shadow: none;
    }

    .main-line {
        display: block;
        text-transform: uppercase;
        font-family: 'Outfit', sans-serif;
        font-size: 3.8rem;
        font-weight: 900;
        letter-spacing: 2px;
        color: #ffffff;
        line-height: 1.1;
        margin-bottom: 0.75rem;
        text-shadow: 0 4px 20px rgba(0,0,0,0.8);
    }

    [data-bs-theme="light"] .main-line {
        color: #121212;
        text-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .accent-line {
        display: block;
        text-transform: uppercase;
        font-family: 'Outfit', sans-serif;
        font-size: 2.8rem;
        font-weight: 800;
        letter-spacing: 3px;
        color: #DC143C;
        line-height: 1.1;
        text-shadow: 0 4px 15px rgba(0,0,0,0.6);
    }

    [data-bs-theme="light"] .accent-line {
        text-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    @media (max-width: 991.98px) {
        .hero-carousel-text { height: 350px; }
        .subtitle-line { font-size: 0.9rem; letter-spacing: 3px; }
        .main-line { font-size: 2.8rem; }
        .accent-line { font-size: 2rem; }
    }

    @media (max-width: 767.98px) {
        .hero-carousel-text { height: 250px; }
        .subtitle-line { font-size: 0.8rem; letter-spacing: 2px; }
        .main-line { font-size: 2rem; }
        .accent-line { font-size: 1.5rem; }
        .text-slide { padding: 1rem; }
    }

    @media (max-width: 575.98px) {
        .main-line { font-size: 1.7rem; }
        .accent-line { font-size: 1.3rem; }
    }
</style>

        <!-- Center: Text Hero Slider with RGB Glow Effects -->
        <div class="col-lg-7 col-md-8">
            <div class="hero-carousel-text">
                <!-- Glowing Ambient Blobs using RGB -->
                <div class="glow-orb orb-1"></div>
                <div class="glow-orb orb-2"></div>
                <div class="glow-orb orb-3"></div>

                <!-- Text Overlay -->
                <div class="text-hero-container">
                    <div class="text-slide slide-1">
                        <span class="subtitle-line">Discover</span>
                        <span class="main-line">The Best</span>
                        <span class="accent-line">Campus Deals</span>
                    </div>
                    <div class="text-slide slide-2">
                        <span class="subtitle-line">Convenience</span>
                        <span class="main-line">Delivered</span>
                        <span class="accent-line">To Your Hostel</span>
                    </div>
                    <div class="text-slide slide-3">
                        <span class="subtitle-line">Smart Shopping</span>
                        <span class="main-line">Save Time</span>
                        <span class="accent-line">& Save Cash</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Side Banners -->
        <div class="col-lg-3 col-md-4 d-none d-md-flex flex-column justify-content-between">
            @php
                $side1 = glob(public_path('images/side_banner_1_*.png'))[0] ?? null;
                $side2 = glob(public_path('images/side_banner_2_*.png'))[0] ?? null;
                
                $side1Name = $side1 ? basename($side1) : 'placeholder.jpg';
                $side2Name = $side2 ? basename($side2) : 'placeholder.jpg';
            @endphp
            <div class="side-banner mb-3 position-relative" style="background-image: url('{{ asset('images/' . $side1Name) }}');">
                <div class="position-absolute bottom-0 w-100 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                    <h5 class="text-white mb-0">Flash Sales</h5>
                    <small class="text-danger fw-bold">Up to 50% Off</small>
                </div>
            </div>
            <div class="side-banner position-relative" style="background-image: url('{{ asset('images/delivery.jpg') }}');">
                <div class="position-absolute bottom-0 w-100 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                    <h5 class="text-white mb-0">Cycle Delivery</h5>
                    <small class="text-warning fw-bold">Fast & Secure</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Sales Section -->
    <div class="mb-5 rounded-3 p-4 flash-sale-container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div class="d-flex align-items-center gap-3">
                <h3 class="text-white mb-0 text-uppercase fw-bold"><i class="text-danger me-2">⚡</i> Flash Sales</h3>
                <div class="d-flex align-items-center bg-dark rounded px-3 py-2 text-white shadow-sm border border-secondary" id="countdown-timer">
                    <span class="fs-5 fw-bold text-danger" id="hours">00</span><span class="mx-1">:</span>
                    <span class="fs-5 fw-bold text-danger" id="minutes">00</span><span class="mx-1">:</span>
                    <span class="fs-5 fw-bold text-danger" id="seconds">00</span>
                    <span class="ms-2 small text-muted text-uppercase">Left</span>
                </div>
            </div>
            <a href="#" class="text-decoration-none text-white fw-bold btn btn-danger btn-sm px-4 shadow-sm" style="background-color: #DC143C;">See All Deals ></a>
        </div>
        
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-3">
            @forelse($flashSales as $product)
                @include('partials.product_card', ['product' => $product, 'isFlashSale' => true])
            @empty
                <div class="col-12 text-center py-5">
                    <h5 class="text-muted">No flash sales active right now.</h5>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Category Sections -->
    @foreach($categoryProducts as $category => $products)
        @if($products->count() > 0)
        <div class="mb-5 p-4 rounded-3 category-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-white mb-0 text-capitalize section-title">{{ $category }}</h4>
                <a href="{{ route('product.category', $category) }}" class="text-decoration-none text-primary fw-bold" style="color: #B87333 !important;">View Category ></a>
            </div>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-3">
                @foreach($products as $product)
                    @include('partials.product_card', ['product' => $product, 'isFlashSale' => false])
                @endforeach
            </div>
        </div>
        @endif
    @endforeach

    <!-- Recommended For You Section -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-white mb-0 section-title text-uppercase">Recommended For You</h3>
        </div>
        
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-3">
            @forelse($recommended as $product)
                @include('partials.product_card', ['product' => $product, 'isFlashSale' => false])
            @empty
                <div class="col-12 text-center py-5">
                    <h5 class="text-muted">More recommendations coming soon!</h5>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Promotional Strip -->
    <div class="rounded-3 overflow-hidden position-relative mb-5 shadow-lg promo-strip-container" style="height: 150px;">
        <div class="position-absolute w-100 h-100" style="background: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="d-flex align-items-center justify-content-center h-100 position-relative z-index-1">
            <div class="text-center px-4">
                <h2 class="text-white fw-bold text-uppercase mb-1" style="letter-spacing: 2px;">Cycle Mega Sale</h2>
                <p class="text-white-50 mb-0">free delivery around MAKERERE</p>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Countdown timer logic for Flash Sales
        let endTime = new Date().getTime() + (5 * 60 * 60 * 1000) + (23 * 60 * 1000); 

        function updateTimer() {
            let now = new Date().getTime();
            let distance = endTime - now;

            if (distance < 0) {
                endTime = new Date().getTime() + (5 * 60 * 60 * 1000);
                distance = endTime - now;
            }

            let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("hours").innerText = hours.toString().padStart(2, '0');
            document.getElementById("minutes").innerText = minutes.toString().padStart(2, '0');
            document.getElementById("seconds").innerText = seconds.toString().padStart(2, '0');
        }

        setInterval(updateTimer, 1000);
        updateTimer();
    });
</script>
@endsection
