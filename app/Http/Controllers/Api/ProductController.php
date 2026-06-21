<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\FormatsProducts;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use FormatsProducts;

    public function categories()
    {
        $categories = Product::select('category')->distinct()->orderBy('category')->pluck('category');

        return response()->json(['categories' => $categories]);
    }

    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $products = $query->orderBy('name')->paginate($request->integer('per_page', 20));

        return response()->json([
            'data' => $products->getCollection()->map(fn ($p) => $this->formatProduct($p))->values(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    public function show(Product $product)
    {
        $product->load('images');

        return response()->json(['product' => $this->formatProduct($product)]);
    }

    public function byCategory(string $category)
    {
        $products = Product::where('category', $category)
            ->orderBy('name')
            ->paginate(20);

        return response()->json([
            'category' => $category,
            'data' => $products->getCollection()->map(fn ($p) => $this->formatProduct($p))->values(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
            ],
        ]);
    }
}
