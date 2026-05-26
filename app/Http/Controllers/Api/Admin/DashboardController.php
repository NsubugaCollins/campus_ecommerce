<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Concerns\FormatsProducts;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    use FormatsProducts;

    public function dashboard()
    {
        return response()->json([
            'stats' => [
                'total_sales' => (float) Order::where('payment_status', 'paid')->sum('total_amount'),
                'active_users' => User::where('role', '!=', 'admin')->orWhereNull('role')->count(),
                'total_orders' => Order::count(),
                'total_products' => Product::count(),
            ],
            'recent_orders' => Order::with('user:id,name,email')
                ->orderByDesc('created_at')
                ->take(5)
                ->get()
                ->map(fn ($o) => [
                    'id' => $o->id,
                    'total_amount' => (float) $o->total_amount,
                    'status' => $o->status,
                    'payment_status' => $o->payment_status,
                    'user_name' => $o->user?->name,
                    'created_at' => $o->created_at?->toIso8601String(),
                ]),
        ]);
    }

    public function analytics()
    {
        $salesData = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(7))
            ->get()
            ->groupBy(fn ($o) => $o->created_at->format('Y-m-d'))
            ->map(fn ($orders, $date) => [
                'date' => $date,
                'total' => (float) $orders->sum('total_amount'),
            ])
            ->sortBy('date')
            ->values();

        $orderStatusData = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->map(fn ($r) => ['status' => $r->status, 'count' => (int) $r->count]);

        $topProducts = OrderItem::selectRaw('product_id, SUM(quantity) as total_qty')
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get()
            ->map(fn ($item) => [
                'product_id' => $item->product_id,
                'product_name' => $item->product?->name,
                'total_qty' => (int) $item->total_qty,
            ]);

        return response()->json([
            'sales_data' => $salesData,
            'order_status_data' => $orderStatusData,
            'top_products' => $topProducts,
        ]);
    }

    public function earnings()
    {
        $monthlyEarnings = Order::where('payment_status', 'paid')
            ->whereYear('created_at', date('Y'))
            ->get()
            ->groupBy(fn ($o) => $o->created_at->format('n'))
            ->map(fn ($orders, $month) => [
                'month' => (int) $month,
                'total' => (float) $orders->sum('total_amount'),
            ])
            ->sortBy('month')
            ->values();

        $paymentMethodData = Order::where('payment_status', 'paid')
            ->selectRaw('payment_method, SUM(total_amount) as total')
            ->groupBy('payment_method')
            ->get()
            ->map(fn ($r) => [
                'payment_method' => $r->payment_method,
                'total' => (float) $r->total,
            ]);

        return response()->json([
            'summary' => [
                'total_earned' => (float) Order::where('payment_status', 'paid')->sum('total_amount'),
                'pending_payout' => (float) Order::where('payment_status', 'unpaid')->sum('total_amount'),
                'paid_orders_count' => Order::where('payment_status', 'paid')->count(),
            ],
            'monthly_earnings' => $monthlyEarnings,
            'payment_method_data' => $paymentMethodData,
        ]);
    }
}
