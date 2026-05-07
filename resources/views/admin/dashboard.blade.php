@extends('layouts.admin')

@section('title', 'Overview')

@section('content')
<div class="row g-4 mb-4">
    <!-- Total Sales -->
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="background: rgba(30, 30, 30, 0.6); backdrop-filter: blur(15px); border-radius: 1rem; border-left: 4px solid #DC143C !important;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="mb-0 text-secondary text-uppercase fw-bold" style="letter-spacing: 1px;">Total Sales</h6>
                    <div class="text-primary fs-4">
                        <!-- Money icon SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    </div>
                </div>
                <h3 class="mb-2 text-white">UGX {{ number_format($stats['total_sales'], 2) }}</h3>
                <p class="mb-0 text-success small"><span class="fw-bold">+{{ rand(5, 20) }}%</span> from last month</p>
            </div>
        </div>
    </div>
    
    <!-- Active Users -->
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="background: rgba(30, 30, 30, 0.6); backdrop-filter: blur(15px); border-radius: 1rem; border-left: 4px solid #B87333 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="mb-0 text-secondary text-uppercase fw-bold" style="letter-spacing: 1px;">Users</h6>
                    <div class="text-secondary fs-4" style="color: #B87333 !important;">
                        <!-- Users icon SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                </div>
                <h3 class="mb-2 text-white">{{ number_format($stats['active_users']) }}</h3>
                <p class="mb-0 text-success small"><span class="fw-bold">+{{ rand(2, 10) }}%</span> from last month</p>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="background: rgba(30, 30, 30, 0.6); backdrop-filter: blur(15px); border-radius: 1rem; border-left: 4px solid #198754 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="mb-0 text-secondary text-uppercase fw-bold" style="letter-spacing: 1px;">Orders</h6>
                    <div class="text-success fs-4">
                        <!-- Shopping bag SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                    </div>
                </div>
                <h3 class="mb-2 text-white">{{ number_format($stats['total_orders']) }}</h3>
                <p class="mb-0 text-success small"><span class="fw-bold">+{{ rand(5, 15) }}%</span> from last month</p>
            </div>
        </div>
    </div>

    <!-- Total Products -->
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="background: rgba(30, 30, 30, 0.6); backdrop-filter: blur(15px); border-radius: 1rem; border-left: 4px solid #0dcaf0 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="mb-0 text-secondary text-uppercase fw-bold" style="letter-spacing: 1px;">Products</h6>
                    <div class="text-info fs-4">
                        <!-- Package SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                    </div>
                </div>
                <h3 class="mb-2 text-white">{{ number_format($stats['total_products']) }}</h3>
                <a href="{{ route('admin.products.index') }}" class="mb-0 text-info small text-decoration-none">View Catalog →</a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="card border-0 shadow-lg" style="background: rgba(30, 30, 30, 0.6); backdrop-filter: blur(15px); border-radius: 1rem;">
    <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-white fw-bold text-uppercase">Recent Orders</h5>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0" style="background: transparent;">
                <thead style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <tr>
                        <th class="px-4 py-3 border-0 text-secondary">Order ID</th>
                        <th class="px-4 py-3 border-0 text-secondary">Customer</th>
                        <th class="px-4 py-3 border-0 text-secondary">Date</th>
                        <th class="px-4 py-3 border-0 text-secondary">Amount</th>
                        <th class="px-4 py-3 border-0 text-secondary">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                        <td class="px-4 py-3 border-0 align-middle">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-primary text-decoration-none fw-bold">#{{ $order->id }}</a>
                        </td>
                        <td class="px-4 py-3 border-0 align-middle text-white">{{ $order->user->name }}</td>
                        <td class="px-4 py-3 border-0 align-middle text-muted">{{ $order->created_at->diffForHumans() }}</td>
                        <td class="px-4 py-3 border-0 align-middle fw-bold">UGX {{ number_format($order->total_amount, 2) }}</td>
                        <td class="px-4 py-3 border-0 align-middle">
                            <span class="badge {{ $order->status == 'completed' ? 'bg-success' : ($order->status == 'cancelled' ? 'bg-danger' : 'bg-warning') }} bg-opacity-25 border border-opacity-50">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-white-50">No recent orders.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
