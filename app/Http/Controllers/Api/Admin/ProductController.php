<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Concerns\FormatsProducts;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use FormatsProducts;

    public function index()
    {
        $products = Product::with('images')->latest()->paginate(15);

        return response()->json([
            'data' => $products->getCollection()->map(fn ($p) => $this->formatProduct($p))->values(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    public function show(Product $product)
    {
        $product->load('images');

        return response()->json(['product' => $this->formatProduct($product)]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|string|max:255|unique:products',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:20480',
            'additional_images.*' => 'nullable|image|max:20480',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadImage($request->file('image'), 'campus_mall/products');
        }

        $product = Product::create($validated);
        $this->saveAdditionalImages($product, $request);

        return response()->json(['product' => $this->formatProduct($product->load('images'))], 201);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_id' => 'required|string|max:255|unique:products,product_id,'.$product->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:20480',
            'additional_images.*' => 'nullable|image|max:20480',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadImage($request->file('image'), 'campus_mall/products');
        }

        $product->update($validated);
        $this->saveAdditionalImages($product, $request);

        return response()->json(['product' => $this->formatProduct($product->fresh('images'))]);
    }

    public function destroy(Product $product)
    {
        if ($product->image && ! str_starts_with($product->image, ['http://', 'https://'])) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }

    public function destroyImage(ProductImage $image)
    {
        if (! str_starts_with($image->image_path, ['http://', 'https://'])) {
            Storage::disk('public')->delete($image->image_path);
        }
        $image->delete();

        return response()->json(['message' => 'Image deleted']);
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

    protected function saveAdditionalImages(Product $product, Request $request): void
    {
        if (! $request->hasFile('additional_images')) {
            return;
        }
        foreach ($request->file('additional_images') as $additionalImage) {
            $path = $this->uploadImage($additionalImage, 'campus_mall/products/gallery');
            $product->images()->create(['image_path' => $path]);
        }
    }
}
