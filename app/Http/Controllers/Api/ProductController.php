<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\FormatsProducts;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use FormatsProducts;

    public function categories()
    {
        $categories = [
            'Electronics', 'Furniture', 'Beddings', 'Fashion',
            'Accessories', 'Beauty', 'Scholastic Materials', 'Sporting Goods',
            'Grocery', 'Kitchenware',
        ];

        return response()->json(['categories' => $categories]);
    }

    public function index(Request $request)
    {
        $query = Product::query()->with('user');

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
        $product->load(['images', 'user']);

        return response()->json(['product' => $this->formatProduct($product)]);
    }

    public function byCategory(string $category)
    {
        $products = Product::where('category', $category)
            ->with('user')
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ad_type' => 'nullable|string|in:product,service',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|max:20480',
            'additional_images' => 'nullable|array',
            'additional_images.*' => 'nullable|image|max:20480',
        ]);

        $validated['product_id'] = 'prod_' . strtolower(\Illuminate\Support\Str::random(10));
        $validated['user_id'] = $request->user()->id;

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadImage($request->file('image'), 'campus_mall/products');
        }

        $product = Product::create($validated);

        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $additionalImage) {
                $path = $this->uploadImage($additionalImage, 'campus_mall/products/gallery');
                $product->images()->create(['image_path' => $path]);
            }
        }

        return response()->json(['product' => $this->formatProduct($product->load(['images', 'user']))], 201);
    }

    public function userProducts(Request $request)
    {
        $products = Product::where('user_id', $request->user()->id)
            ->with(['images', 'user'])
            ->latest()
            ->paginate(15);

        return response()->json([
            'data' => $products->getCollection()->map(fn ($p) => $this->formatProduct($p))->values(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    public function destroyUserProduct(Request $request, Product $product)
    {
        if ($product->user_id !== $request->user()->id) {
            abort(403);
        }

        // Delete main image if it exists locally
        if ($product->image && ! str_starts_with($product->image, ['http://', 'https://'])) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete additional images
        $product->images->each(function ($img) {
            if ($img->image_path && ! str_starts_with($img->image_path, ['http://', 'https://'])) {
                Storage::disk('public')->delete($img->image_path);
            }
            $img->delete();
        });

        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }

    protected function uploadImage($file, string $folder): string
    {
        if (config('services.cloudinary.url')) {
            $cloudinary = new \Cloudinary\Cloudinary(config('services.cloudinary.url'));
            $result = $cloudinary->uploadApi()->upload($file->getRealPath(), ['folder' => $folder]);

            return $result['secure_url'];
        }

        return $file->store('products', 'public');
    }
}
