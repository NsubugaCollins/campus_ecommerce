<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderDeliveredMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        // Notify buyer when order is marked as delivered/completed
        if ($request->status === 'completed') {
            try {
                $order->load(['user', 'items.product']);
                Mail::to($order->user->email)->send(new OrderDeliveredMail($order));
            } catch (\Exception $e) {
                \Log::error('Order delivered email failed: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Order status updated successfully!');
    }
}
