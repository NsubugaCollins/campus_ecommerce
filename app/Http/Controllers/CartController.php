<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{
    private function getCartIdentifier()
    {
        if (Auth::check()) {
            return ['user_id' => Auth::id()];
        }

        $guestCartId = request()->cookie('guest_cart_id');
        if (!$guestCartId) {
            $guestCartId = Str::uuid()->toString();
            Cookie::queue('guest_cart_id', $guestCartId, 60 * 24 * 365 * 5); // 5 years
        }
        return ['session_id' => $guestCartId];
    }

    public function index()
    {
        $identifier = $this->getCartIdentifier();
        $cartItems = CartItem::where($identifier)->with('product')->get();
        
        $cart = [];
        $total = 0;
        foreach ($cartItems as $item) {
            $cart[$item->product_id] = [
                "name" => $item->product->name,
                "quantity" => $item->quantity,
                "price" => $item->product->price,
                "image" => $item->product->image_url,
                "product_id" => $item->product_id,
                "id" => $item->id // DB ID for easier removal if needed
            ];
            $total += $item->product->price * $item->quantity;
        }

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $identifier = $this->getCartIdentifier();
        
        $cartItem = CartItem::where($identifier)
                            ->where('product_id', $request->product_id)
                            ->first();

        if (!$cartItem) {
            CartItem::create(array_merge($identifier, [
                'product_id' => $request->product_id,
                'quantity' => 1
            ]));
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $identifier = $this->getCartIdentifier();
            
            // Note: $request->id here is the product_id from the form
            $cartItem = CartItem::where($identifier)
                                ->where('product_id', $request->id)
                                ->first();
            
            if ($cartItem) {
                $cartItem->quantity = $request->quantity;
                $cartItem->save();
                return redirect()->route('cart.index')->with('success', 'Cart updated successfully');
            }
        }
        return redirect()->route('cart.index')->with('error', 'Could not update cart.');
    }

    public function remove(Request $request)
    {
        if ($request->id) {
            $identifier = $this->getCartIdentifier();
            
            $cartItem = CartItem::where($identifier)
                                ->where('product_id', $request->id)
                                ->first();
            
            if ($cartItem) {
                $cartItem->delete();
                return redirect()->route('cart.index')->with('success', 'Product removed successfully');
            }
        }
        return redirect()->route('cart.index')->with('error', 'Could not remove product.');
    }
}
