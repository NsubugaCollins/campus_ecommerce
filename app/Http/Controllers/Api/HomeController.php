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
        $flashSales = Product::with('user')->inRandomOrder()->take(6)->get();
        $recommended = Product::with('user')->inRandomOrder()->take(12)->get();
        $categories = [
            'Electronics', 'Furniture', 'Beddings', 'Fashion',
            'Accessories', 'Beauty', 'Scholastic Materials', 'Sporting Goods',
            'Grocery', 'Kitchenware',
        ];

        $categoryProducts = [];
        foreach ($categories as $category) {
            $items = Product::with('user')->where('category', $category)->take(6)->get();
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
