@extends('layouts.admin')

@section('title', 'Business Analytics')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card bg-dark border-secondary h-100 shadow-sm">
            <div class="card-header bg-black border-secondary py-3">
                <h5 class="mb-0 text-white fw-bold">SALES PERFORMANCE (LAST 7 DAYS)</h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-dark border-secondary h-100 shadow-sm">
            <div class="card-header bg-black border-secondary py-3">
                <h5 class="mb-0 text-white fw-bold">ORDER STATUS DISTRIBUTION</h5>
            </div>
            <div class="card-body d-flex align-items-center">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card bg-dark border-secondary shadow-sm">
            <div class="card-header bg-black border-secondary py-3">
                <h5 class="mb-0 text-white fw-bold">TOP SELLING PRODUCTS</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0">
                        <thead class="bg-black">
                            <tr>
                                <th class="ps-4">Product</th>
                                <th class="text-center">Units Sold</th>
                                <th class="pe-4 text-end">Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $item)
                            <tr class="align-middle border-secondary border-opacity-10">
                                <td class="ps-4">
                                    <span class="text-white fw-bold">{{ $item->product->name }}</span>
                                </td>
                                <td class="text-center text-info">{{ $item->total_qty }}</td>
                                <td class="pe-4 text-end text-success">UGX {{ number_format($item->total_qty * $item->product->price, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-dark border-secondary shadow-sm h-100">
            <div class="card-header bg-black border-secondary py-3">
                <h5 class="mb-0 text-white fw-bold">QUICK INSIGHTS</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6 class="text-white-50 small text-uppercase">Average Order Value</h6>
                    <h3 class="text-white">UGX {{ number_format(\App\Models\Order::avg('total_amount'), 2) }}</h3>
                </div>
                <div class="mb-4">
                    <h6 class="text-white-50 small text-uppercase">Total Items Sold</h6>
                    <h3 class="text-white">{{ \App\Models\OrderItem::sum('quantity') }}</h3>
                </div>
                <div>
                    <h6 class="text-white-50 small text-uppercase">Most Popular Category</h6>
                    <h3 class="text-white">{{ \App\Models\Product::select('category')->withCount('orderItems')->orderBy('order_items_count', 'desc')->first()->category ?? 'N/A' }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($salesData->pluck('date')) !!},
            datasets: [{
                label: 'Sales (UGX)',
                data: {!! json_encode($salesData->pluck('total')) !!},
                borderColor: '#DC143C',
                backgroundColor: 'rgba(220, 20, 60, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: 'rgba(255, 255, 255, 0.5)' }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: 'rgba(255, 255, 255, 0.5)' }
                }
            }
        }
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($orderStatusData->pluck('status')) !!},
            datasets: [{
                data: {!! json_encode($orderStatusData->pluck('count')) !!},
                backgroundColor: ['#DC143C', '#b87333', '#17a2b8', '#6c757d'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: 'rgba(255, 255, 255, 0.7)', padding: 20 }
                }
            }
        }
    });
</script>
@endsection
