<?php

namespace App\Http\Controllers\Api\Concerns;

use App\Models\UserSale;

trait FormatsUserSales
{
    protected function formatUserSale(UserSale $sale): array
    {
        $sale->loadMissing(['user:id,name,email', 'images']);

        return [
            'id' => $sale->id,
            'user_id' => $sale->user_id,
            'user_name' => $sale->user?->name,
            'user_email' => $sale->user?->email,
            'product_name' => $sale->product_name,
            'category' => $sale->category,
            'condition' => $sale->condition,
            'description' => $sale->description,
            'expected_price' => (float) $sale->expected_price,
            'offered_price' => $sale->offered_price !== null ? (float) $sale->offered_price : null,
            'status' => $sale->status,
            'admin_notes' => $sale->admin_notes,
            'images' => $sale->images->map(function ($img) {
                $url = $img->getAttributes()['image_url'] ?? '';
                if ($url && ! str_starts_with($url, 'http')) {
                    $url = url('storage/'.$url);
                }

                return ['id' => $img->id, 'url' => $url];
            })->values()->all(),
            'created_at' => $sale->created_at?->toIso8601String(),
        ];
    }

    protected static function tradeInCategories(): array
    {
        return [
            'Electronics', 'Furniture', 'Beddings', 'Fashion',
            'Accessories', 'Beauty', 'Scholastic Materials', 'Sporting Goods',
            'Grocery', 'Kitchenware',
        ];
    }
}
