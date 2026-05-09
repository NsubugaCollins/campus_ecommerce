@extends('layouts.app')

@section('content')
<!-- Add Custom CSS for Product Cards and Animations -->
<style>
    .category-menu {
        background: rgba(30, 30, 30, 0.6);
        backdrop-filter: blur(15px);
        border-radius: 0.5rem;
        border: 1px solid rgba(255, 255, 255, 0.05);
        height: 100%;
    }
    .category-menu .nav-link {
        color: rgba(255, 255, 255, 0.8);
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }
    .category-menu .nav-link:hover {
        color: #DC143C;
        background: rgba(220, 20, 60, 0.1);
        padding-left: 1.5rem;
    }
    .hero-carousel {
        border-radius: 0.5rem;
        overflow: hidden;
        height: 450px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    @media (max-width: 991.98px) {
        .hero-carousel { height: 350px; }
    }
    @media (max-width: 767.98px) {
        .hero-carousel { height: 250px; }
    }
    .side-banner {
        border-radius: 0.5rem;
        overflow: hidden;
        height: calc(50% - 0.5rem);
        background-size: cover;
        background-position: center;
        box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        transition: transform 0.3s ease;
    }
    .side-banner:hover {
        transform: scale(1.02);
    }
    .product-card {
        background: rgba(30, 30, 30, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(220, 20, 60, 0.15);
        border-color: rgba(220, 20, 60, 0.3);
    }
    .product-img-wrapper {
        height: 200px;
        overflow: hidden;
        background: #111;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .product-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 0.5rem;
        transition: transform 0.5s ease;
    }
    .product-card:hover .product-img {
        transform: scale(1.05);
    }
    .add-to-cart-btn {
        background: transparent;
        color: #B87333;
        border: 1px solid #B87333;
        transition: all 0.3s ease;
    }
    .add-to-cart-btn:hover {
        background: #B87333;
        color: #fff;
        transform: scale(1.05);
    }
    .section-title {
        border-left: 4px solid #DC143C;
        padding-left: 10px;
        letter-spacing: 1px;
    }
</style>

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

        <!-- Center: Main Carousel -->
        <div class="col-lg-7 col-md-8">
            <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                </div>
                <div class="carousel-inner h-100">
                    @php
                        // Find the actual generated image filenames
                        $hero1 = glob(public_path('images/hero_banner_1_*.png'))[0] ?? null;
                        $hero2 = glob(public_path('images/hero_banner_2_*.png'))[0] ?? null;
                        
                        $hero1Name = $hero1 ? basename($hero1) : 'placeholder.jpg';
                        $hero2Name = $hero2 ? basename($hero2) : 'placeholder.jpg';
                    @endphp
                    
                    <div class="carousel-item active h-100">
                        <img src="{{ asset('images/' . $hero1Name) }}" class="d-block w-100 h-100" style="object-fit: cover;" alt="Premium Electronics">
                    </div>
                    <div class="carousel-item h-100">
                        <img src="{{ asset('images/' . $hero2Name) }}" class="d-block w-100 h-100" style="object-fit: cover;" alt="Luxury Furniture">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                </button>
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
            <div class="side-banner position-relative" style="background-image: url('{{ asset('images/' . $side2Name) }}');">
                <div class="position-absolute bottom-0 w-100 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                    <h5 class="text-white mb-0">Cycle Delivery</h5>
                    <small class="text-warning fw-bold">Fast & Secure</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Sales Section -->
    <div class="mb-5 rounded-3 p-4" style="background: linear-gradient(135deg, rgba(220, 20, 60, 0.1) 0%, rgba(30, 30, 30, 0.8) 100%); border: 1px solid rgba(220, 20, 60, 0.2);">
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
        <div class="mb-5 p-4 rounded-3" style="background-color: rgba(30, 30, 30, 0.4); border: 1px solid rgba(255, 255, 255, 0.05);">
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
    <div class="rounded-3 overflow-hidden position-relative mb-5 shadow-lg" style="height: 150px; background: linear-gradient(45deg, #1a1a1a, #DC143C);">
        <div class="position-absolute w-100 h-100" style="background: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="d-flex align-items-center justify-content-center h-100 position-relative z-index-1">
            <div class="text-center px-4">
                <h2 class="text-white fw-bold text-uppercase mb-1" style="letter-spacing: 2px;">Cycle Mega Sale</h2>
                <p class="text-white-50 mb-0">Free delivery on all orders above UGX 50,000</p>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Countdown timer logic for Flash Sales
        // Set an arbitrary end time 5 hours from now to simulate flash sale remaining time
        let endTime = new Date().getTime() + (5 * 60 * 60 * 1000) + (23 * 60 * 1000); 

        function updateTimer() {
            let now = new Date().getTime();
            let distance = endTime - now;

            if (distance < 0) {
                // reset for demonstration purposes if it drops below 0
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
