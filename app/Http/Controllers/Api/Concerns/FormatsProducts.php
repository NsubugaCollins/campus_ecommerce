<?php

namespace App\Http\Controllers\Api\Concerns;

use App\Models\Product;

trait FormatsProducts
{
    protected function formatProduct(Product $product): array
    {
        $imageUrl = $product->image_url;
        if ($imageUrl && ! str_starts_with($imageUrl, 'http')) {
            $imageUrl = url($imageUrl);
        }

        return [
            'id' => $product->id,
            'product_id' => $product->product_id,
            'ad_type' => $product->ad_type ?? 'product',
            'name' => $product->name,
            'description' => $product->description,
            'category' => $product->category,
            'price' => (float) $product->price,
            'image_url' => $imageUrl,
            'images' => $product->relationLoaded('images')
                ? $product->images->map(function ($img) {
                    $url = $img->image_url;
                    if ($url && ! str_starts_with($url, 'http')) {
                        $url = url($url);
                    }

                    return ['id' => $img->id, 'url' => $url];
                })->values()->all()
                : [],
            'seller' => $product->user ? [
                'id' => $product->user->id,
                'name' => $product->user->name,
                'email' => $product->user->email,
                'phone' => $product->user->phone,
            ] : null,
        ];
    }
}
