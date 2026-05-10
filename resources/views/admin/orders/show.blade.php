@extends('layouts.admin')

@section('title', 'Order #' . $order->id)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card bg-dark border-secondary shadow-sm mb-4">
            <div class="card-header bg-black border-secondary d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 text-white fw-bold text-uppercase">Items Ordered</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th class="pe-4 text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr class="align-middle border-secondary border-opacity-10">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded overflow-hidden bg-black d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; border: 1px solid rgba(255,255,255,0.1);">
                                            @if($item->product && $item->product->image_url)
                                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                            @else
                                                <div class="text-white-50" style="font-size: 8px;">No Image</div>
                                            @endif
                                        </div>
                                        <div class="text-white fw-bold">{{ $item->product->name }}</div>
                                    </div>
                                </td>
                                <td class="text-white-50">UGX {{ number_format($item->price, 2) }}</td>
                                <td class="text-white">{{ $item->quantity }}</td>
                                <td class="pe-4 text-end text-white fw-bold">UGX {{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-black border-top border-secondary">
                            <tr>
                                <td colspan="3" class="text-end ps-4 py-3 text-white-50">Total Amount</td>
                                <td class="pe-4 py-3 text-end text-danger fs-5 fw-bold">UGX {{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="card bg-dark border-secondary shadow-sm">
            <div class="card-header bg-black border-secondary py-3">
                <h5 class="mb-0 text-white fw-bold text-uppercase">Delivery Details</h5>
            </div>
            <div class="card-body">
                <p class="text-white-50 mb-0" style="white-space: pre-line;">{{ $order->shipping_address }}</p>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card bg-dark border-secondary shadow-sm mb-4">
            <div class="card-header bg-black border-secondary py-3">
                <h5 class="mb-0 text-white fw-bold text-uppercase">Order Actions</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-4">
                        <label class="form-label text-white-50">Update Order Status</label>
                        <select name="status" class="form-select bg-black border-secondary text-white">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-crimson w-100 py-2 fw-bold text-uppercase">Update Status</button>
                </form>
            </div>
        </div>

        <div class="card bg-dark border-secondary shadow-sm">
            <div class="card-header bg-black border-secondary py-3">
                <h5 class="mb-0 text-white fw-bold text-uppercase">Customer Information</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; font-weight: bold; font-size: 20px;">
                        {{ substr($order->user->name, 0, 1) }}
                    </div>
                    <div>
                        <h6 class="text-white mb-0">{{ $order->user->name }}</h6>
                        <small class="text-white-50">{{ $order->user->email }}</small>
                    </div>
                </div>
                <hr class="border-secondary opacity-25">
                <div class="mb-2">
                    <small class="text-white-50 d-block">Payment Method</small>
                    <span class="text-white text-uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                </div>
                <div class="mb-0">
                    <small class="text-white-50 d-block">Payment Status</small>
                    <span class="badge {{ $order->payment_status == 'paid' ? 'bg-success' : 'bg-warning' }} text-uppercase">
                        {{ $order->payment_status }}
                    </span>
                    @if($order->paypal_order_id)
                        <br><small class="text-white-50">PayPal ID: {{ $order->paypal_order_id }}</small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-crimson {
        background-color: #DC143C;
        color: white;
        border: none;
    }
    .btn-crimson:hover {
        background-color: #b01030;
        color: white;
    }
</style>
@endsection
