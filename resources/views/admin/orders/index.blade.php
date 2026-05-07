@extends('layouts.admin')

@section('title', 'Order Management')

@section('content')
<div class="card bg-dark border-secondary shadow-sm">
    <div class="card-header bg-black border-secondary d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 text-white fw-bold">ALL ORDERS</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0">
                <thead class="bg-black text-white-50">
                    <tr>
                        <th class="ps-4 py-3">Order ID</th>
                        <th class="py-3">Customer</th>
                        <th class="py-3">Amount</th>
                        <th class="py-3">Payment</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Date</th>
                        <th class="pe-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($orders as $order)
                    <tr class="align-middle border-secondary border-opacity-10">
                        <td class="ps-4 py-3 fw-bold">#{{ $order->id }}</td>
                        <td class="py-3">
                            <div class="d-flex flex-column">
                                <span class="text-white">{{ $order->user->name }}</span>
                                <small class="text-white-50">{{ $order->user->email }}</small>
                            </div>
                        </td>
                        <td class="py-3 text-white">UGX {{ number_format($order->total_amount, 2) }}</td>
                        <td class="py-3">
                            <span class="badge {{ $order->payment_status == 'paid' ? 'bg-success' : 'bg-warning' }} text-uppercase">
                                {{ $order->payment_status }}
                            </span>
                            <br>
                            <small class="text-white-50 text-uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</small>
                        </td>
                        <td class="py-3">
                            <span class="badge {{ $order->status == 'completed' ? 'bg-info' : ($order->status == 'cancelled' ? 'bg-danger' : 'bg-secondary') }} text-uppercase">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="py-3 text-white-50">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="pe-4 py-3 text-end">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-info">View Details</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-white-50">No orders found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($orders->hasPages())
    <div class="card-footer bg-black border-secondary py-3">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
