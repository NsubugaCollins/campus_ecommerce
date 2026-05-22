<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\FormatsProducts;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use FormatsProducts;

    public function index(Request $request)
    {
        $items = CartItem::where('user_id', $request->user()->id)
            ->with('product.images')
            ->get();

        $total = 0;
        $formatted = $items->map(function (CartItem $item) use (&$total) {
            $lineTotal = $item->product->price * $item->quantity;
            $total += $lineTotal;

            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'line_total' => (float) $lineTotal,
                'product' => $this->formatProduct($item->product),
            ];
        });

        return response()->json([
            'items' => $formatted,
            'total' => (float) $total,
            'item_count' => $items->sum('quantity'),
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quantity = $request->integer('quantity', 1);
        $userId = $request->user()->id;

        $cartItem = CartItem::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            $cartItem = CartItem::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'quantity' => $quantity,
            ]);
        }

        return response()->json([
            'message' => 'Added to cart',
            'item_id' => $cartItem->id,
        ], 201);
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $this->authorizeCartItem($request, $cartItem);

        $request->validate(['quantity' => 'required|integer|min:1']);

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json(['message' => 'Cart updated']);
    }

    public function remove(Request $request, CartItem $cartItem)
    {
        $this->authorizeCartItem($request, $cartItem);
        $cartItem->delete();

        return response()->json(['message' => 'Item removed']);
    }

    protected function authorizeCartItem(Request $request, CartItem $cartItem): void
    {
        if ($cartItem->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized');
        }
    }
}
