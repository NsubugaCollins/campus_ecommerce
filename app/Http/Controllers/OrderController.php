<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    private function getCartIdentifier()
    {
        if (Auth::check()) {
            return ['user_id' => Auth::id()];
        }

        $guestCartId = request()->cookie('guest_cart_id');
        if (!$guestCartId) {
            // This shouldn't normally happen if they are at checkout, 
            // but just in case.
            return ['session_id' => 'none']; 
        }
        return ['session_id' => $guestCartId];
    }

    public function index()
    {
        $orders = Auth::user()->orders()->with(['items.product', 'rating'])->orderBy('created_at', 'desc')->get();
        return view('orders.index', compact('orders'));
    }

    public function checkoutView()
    {
        $identifier = $this->getCartIdentifier();
        $cartItems = CartItem::where($identifier)->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $cart = [];
        $total = 0;
        foreach ($cartItems as $item) {
            $cart[$item->product_id] = [
                "name" => $item->product->name,
                "quantity" => $item->quantity,
                "price" => $item->product->price,
                "image" => $item->product->image_url,
                "product_id" => $item->product_id
            ];
            $total += $item->product->price * $item->quantity;
        }

        $points = Auth::check() ? Auth::user()->points : 0;
        return view('orders.checkout', compact('cart', 'total', 'points'));
    }

    public function checkout(Request $request)
    {
        $identifier = $this->getCartIdentifier();
        $cartItems = CartItem::where($identifier)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'payment_method' => 'required|string',
        ]);

        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += $item->product->price * $item->quantity;
        }

        $user = Auth::user();
        $pointsToUse = $request->input('points_to_use', 0);
        $discount = 0;

        if ($pointsToUse > 0 && $user) {
            if ($pointsToUse > $user->points) {
                $pointsToUse = $user->points;
            }
            
            $discount = $pointsToUse / 100; // 100 points = UGX 1.00
            if ($discount > $totalAmount) {
                $discount = $totalAmount;
                $pointsToUse = $discount * 100;
            }
            
            $user->decrement('points', $pointsToUse);
            $totalAmount -= $discount;
        }

        // Create the Order
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'shipping_address' => $request->shipping_address,
            'payment_method' => $request->payment_method,
        ]);

        if ($user) {
            $pointsEarned = floor($totalAmount * 10); // Award points for this purchase (10 points per 1 UGX = 10% value back)
            $user->increment('points', $pointsEarned);
            session()->flash('points_earned', "You earned $pointsEarned points for this order!");
        }

        // Create Order Items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        // Send order confirmation email
        $order->sendConfirmationEmail();

        // Clear the cart
        CartItem::where($identifier)->delete();

        if ($request->payment_method === 'paypal') {
            return redirect()->route('paypal.payment', $order->id);
        }

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }
}
