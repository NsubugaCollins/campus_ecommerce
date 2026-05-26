<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        if ($request->order_id) {
            $order = Order::findOrFail($request->order_id);
            if ($order->user_id !== $request->user()->id) {
                abort(403);
            }
            if ($order->rating) {
                return response()->json(['message' => 'Order already rated'], 422);
            }
        }

        $rating = Rating::create([
            'user_id' => $request->user()->id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'rating' => [
                'id' => $rating->id,
                'rating' => $rating->rating,
                'comment' => $rating->comment,
                'order_id' => $rating->order_id,
            ],
        ], 201);
    }
}
