@extends('layouts.admin')

@section('title', 'Earnings Overview')

@section('content')
<div class="row g-4 mb-4">
    <!-- Total Revenue Card -->
    <div class="col-md-4">
        <div class="card bg-dark border-secondary h-100 shadow-sm" style="border-left: 4px solid #198754 !important;">
            <div class="card-body py-4">
                <h6 class="text-white-50 text-uppercase small fw-bold mb-3">Total Earned</h6>
                <h2 class="text-white mb-0">UGX {{ number_format($summary['total_earned'], 2) }}</h2>
                <p class="text-success small mt-2 mb-0 fw-bold">FROM {{ $summary['paid_orders_count'] }} PAID ORDERS</p>
            </div>
        </div>
    </div>

    <!-- Pending Payout Card -->
    <div class="col-md-4">
        <div class="card bg-dark border-secondary h-100 shadow-sm" style="border-left: 4px solid #ffc107 !important;">
            <div class="card-body py-4">
                <h6 class="text-white-50 text-uppercase small fw-bold mb-3">Pending Payments</h6>
                <h2 class="text-white mb-0">UGX {{ number_format($summary['pending_payout'], 2) }}</h2>
                <p class="text-warning small mt-2 mb-0">WAITING FOR PAYMENT COMPLETION</p>
            </div>
        </div>
    </div>

    <!-- Average Order Value -->
    <div class="col-md-4">
        <div class="card bg-dark border-secondary h-100 shadow-sm" style="border-left: 4px solid #0dcaf0 !important;">
            <div class="card-body py-4">
                <h6 class="text-white-50 text-uppercase small fw-bold mb-3">Avg. Order Value</h6>
                <h2 class="text-white mb-0">UGX {{ number_format(\App\Models\Order::avg('total_amount') ?? 0, 2) }}</h2>
                <p class="text-info small mt-2 mb-0">PER TRANSACTION</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card bg-dark border-secondary shadow-sm">
            <div class="card-header bg-black border-secondary py-3">
                <h5 class="mb-0 text-white fw-bold">MONTHLY EARNINGS ({{ date('Y') }})</h5>
            </div>
            <div class="card-body">
                <canvas id="earningsChart" height="350"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card bg-dark border-secondary shadow-sm h-100">
            <div class="card-header bg-black border-secondary py-3">
                <h5 class="mb-0 text-white fw-bold">EARNINGS BY METHOD</h5>
            </div>
            <div class="card-body d-flex flex-column justify-content-center">
                <canvas id="paymentMethodChart"></canvas>
                <div class="mt-4">
                    @foreach($paymentMethodData as $data)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-white-50 text-uppercase small">{{ str_replace('_', ' ', $data->payment_method) }}</span>
                        <span class="text-white fw-bold">UGX {{ number_format($data->total, 2) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Earnings Chart
    const earningsCtx = document.getElementById('earningsChart').getContext('2d');
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const earningsData = Array(12).fill(0);
    
    {!! json_encode($monthlyEarnings) !!}.forEach(item => {
        earningsData[item.month - 1] = item.total;
    });

    new Chart(earningsCtx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Revenue (UGX)',
                data: earningsData,
                backgroundColor: '#198754',
                borderRadius: 5
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

    // Payment Method Chart
    const paymentCtx = document.getElementById('paymentMethodChart').getContext('2d');
    new Chart(paymentCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($paymentMethodData->pluck('payment_method')->map(fn($m) => str_replace('_', ' ', strtoupper($m)))) !!},
            datasets: [{
                data: {!! json_encode($paymentMethodData->pluck('total')) !!},
                backgroundColor: ['#DC143C', '#b87333', '#17a2b8', '#198754'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endsection
