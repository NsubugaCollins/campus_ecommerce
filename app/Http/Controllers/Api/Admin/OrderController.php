<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Concerns\FormatsProducts;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use FormatsProducts;

    public function index()
    {
        $orders = Order::with(['user:id,name,email', 'items.product'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json([
            'data' => $orders->getCollection()->map(fn ($o) => $this->formatOrder($o))->values(),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['user:id,name,email,phone', 'items.product']);

        return response()->json(['order' => $this->formatOrder($order)]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);
        $order->update(['status' => $request->status]);

        return response()->json(['order' => $this->formatOrder($order->fresh(['user', 'items.product']))]);
    }

    protected function formatOrder(Order $order): array
    {
        return [
            'id' => $order->id,
            'user' => $order->user ? [
                'id' => $order->user->id,
                'name' => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->user->phone,
            ] : null,
            'total_amount' => (float) $order->total_amount,
            'status' => $order->status,
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
            'shipping_address' => $order->shipping_address,
            'created_at' => $order->created_at?->toIso8601String(),
            'items' => $order->items->map(fn ($item) => [
                'id' => $item->id,
                'quantity' => $item->quantity,
                'price' => (float) $item->price,
                'product' => $item->product ? $this->formatProduct($item->product) : null,
            ])->values(),
        ];
    }
}
