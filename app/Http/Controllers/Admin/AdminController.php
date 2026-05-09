<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BlacklistedEmail;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_sales' => \App\Models\Order::where('payment_status', 'paid')->sum('total_amount'),
            'active_users' => \App\Models\User::count(),
            'total_orders' => \App\Models\Order::count(),
            'total_products' => \App\Models\Product::count(),
        ];
        
        $recentOrders = \App\Models\Order::with('user')->orderBy('created_at', 'desc')->take(5)->get();
        
        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }

    public function analytics()
    {
        // Sales over time (last 7 days)
        $salesData = \App\Models\Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(7))
            ->get()
            ->groupBy(function($order) {
                return $order->created_at->format('Y-m-d');
            })
            ->map(function ($orders, $date) {
                return (object)[
                    'date' => $date,
                    'total' => $orders->sum('total_amount')
                ];
            })
            ->sortBy('date')
            ->values();

        // Orders by Status
        $orderStatusData = \App\Models\Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Top Products
        $topProducts = \App\Models\OrderItem::selectRaw('product_id, SUM(quantity) as total_qty')
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_qty', 'desc')
            ->take(5)
            ->get();

        return view('admin.analytics', compact('salesData', 'orderStatusData', 'topProducts'));
    }

    public function earnings()
    {
        // Monthly Earnings (Current Year)
        $monthlyEarnings = \App\Models\Order::where('payment_status', 'paid')
            ->whereYear('created_at', date('Y'))
            ->get()
            ->groupBy(function($order) {
                return $order->created_at->format('n');
            })
            ->map(function ($orders, $month) {
                return (object)[
                    'month' => $month,
                    'total' => $orders->sum('total_amount')
                ];
            })
            ->sortBy('month')
            ->values();

        // Earnings by Payment Method
        $paymentMethodData = \App\Models\Order::where('payment_status', 'paid')
            ->selectRaw('payment_method, SUM(total_amount) as total')
            ->groupBy('payment_method')
            ->get();

        // Total Summary
        $summary = [
            'total_earned' => \App\Models\Order::where('payment_status', 'paid')->sum('total_amount'),
            'pending_payout' => \App\Models\Order::where('payment_status', 'unpaid')->sum('total_amount'),
            'paid_orders_count' => \App\Models\Order::where('payment_status', 'paid')->count(),
        ];

        return view('admin.earnings', compact('monthlyEarnings', 'paymentMethodData', 'summary'));
    }

    public function placeholder($section)

    {
        return view('admin.placeholder', compact('section'));
    }


    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function destroyUser(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        // Add to blacklist
        BlacklistedEmail::create([
            'email' => $user->email,
            'reason' => 'Deleted by admin'
        ]);

        // Delete user
        $user->delete();

        return back()->with('success', 'User deleted and blacklisted successfully!');
    }

    public function profile()
    {
        $admin = auth()->user();
        return view('admin.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $admin = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->filled('password')) {
            $admin->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $admin->save();

        return back()->with('success', 'Profile updated successfully!');
    }
}

