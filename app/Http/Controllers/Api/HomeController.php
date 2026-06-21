<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\FormatsProducts;
use App\Http\Controllers\Controller;
use App\Models\Product;

class HomeController extends Controller
{
    use FormatsProducts;

    public function index()
    {
        $flashSales = Product::inRandomOrder()->take(6)->get();
        $recommended = Product::inRandomOrder()->take(12)->get();
        $categories = Product::select('category')->distinct()->pluck('category');

        $categoryProducts = [];
        foreach ($categories as $category) {
            $items = Product::where('category', $category)->take(6)->get();
            $categoryProducts[$category] = $items->map(fn ($p) => $this->formatProduct($p))->values();
        }

        return response()->json([
            'flash_sales' => $flashSales->map(fn ($p) => $this->formatProduct($p))->values(),
            'recommended' => $recommended->map(fn ($p) => $this->formatProduct($p))->values(),
            'categories' => $categories,
            'category_products' => $categoryProducts,
        ]);
    }
}
