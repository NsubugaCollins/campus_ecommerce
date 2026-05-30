<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\FormatsProducts;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use FormatsProducts;

    public function index(Request $request)
    {
        $orders = $request->user()->orders()
            ->with(['items.product', 'rating'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (Order $order) => $this->formatOrder($order));

        return response()->json(['orders' => $orders]);
    }

    public function show(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        $order->load(['items.product', 'rating']);

        return response()->json(['order' => $this->formatOrder($order)]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'payment_method' => 'required|in:cash,paypal',
            'points_to_use' => 'nullable|integer|min:0',
        ]);

        $user = $request->user();
        $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty'], 422);
        }

        $totalAmount = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);
        $pointsToUse = $request->integer('points_to_use', 0);

        if ($pointsToUse > 0) {
            if ($pointsToUse > $user->points) {
                return response()->json(['message' => "You don't have enough points"], 422);
            }

            $discount = $pointsToUse * 10;
            if ($discount > $totalAmount) {
                $discount = $totalAmount;
                $pointsToUse = (int) ($discount / 10);
            }

            $user->decrement('points', $pointsToUse);
            $totalAmount -= $discount;
        }

        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'shipping_address' => $request->shipping_address,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_method === 'paypal' ? 'pending' : 'unpaid',
        ]);

        $pointsEarned = (int) floor($totalAmount / 5000);
        if ($pointsEarned > 0) {
            $user->increment('points', $pointsEarned);
        }

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        CartItem::where('user_id', $user->id)->delete();

        $order->load(['items.product']);

        $response = [
            'message' => 'Order placed successfully',
            'order' => $this->formatOrder($order),
            'points_earned' => $pointsEarned,
        ];

        if ($request->payment_method === 'paypal') {
            $response['paypal_required'] = true;
            $response['note'] = 'Complete PayPal payment on the website for now.';
        }

        return response()->json($response, 201);
    }

    public function cancel(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Only pending orders can be cancelled.'], 422);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Order cancelled successfully',
            'order' => $this->formatOrder($order->fresh(['items.product', 'rating']))
        ]);
    }

    protected function formatOrder(Order $order): array
    {
        return [
            'id' => $order->id,
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
            'rating' => $order->rating ? [
                'rating' => $order->rating->rating,
                'comment' => $order->rating->comment,
            ] : null,
        ];
    }
}
