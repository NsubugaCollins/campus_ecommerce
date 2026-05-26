<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\FormatsUserSales;
use App\Http\Controllers\Controller;
use App\Models\UserSale;
use App\Models\UserSaleImage;
use Illuminate\Http\Request;

class UserSaleController extends Controller
{
    use FormatsUserSales;

    public function categories()
    {
        return response()->json(['categories' => self::tradeInCategories()]);
    }

    public function index(Request $request)
    {
        $sales = UserSale::where('user_id', $request->user()->id)
            ->with('images')
            ->latest()
            ->paginate(15);

        return response()->json([
            'data' => $sales->getCollection()->map(fn ($s) => $this->formatUserSale($s))->values(),
            'meta' => [
                'current_page' => $sales->currentPage(),
                'last_page' => $sales->lastPage(),
                'total' => $sales->total(),
            ],
        ]);
    }

    public function show(Request $request, UserSale $userSale)
    {
        if ($userSale->user_id !== $request->user()->id) {
            abort(403);
        }
        $userSale->load('images');

        return response()->json(['sale' => $this->formatUserSale($userSale)]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'condition' => 'required|in:New,Like New,Good,Fair',
            'description' => 'required|string',
            'expected_price' => 'required|numeric|min:0',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $sale = UserSale::create([
            'user_id' => $request->user()->id,
            'product_name' => $validated['product_name'],
            'category' => $validated['category'],
            'condition' => $validated['condition'],
            'description' => $validated['description'],
            'expected_price' => $validated['expected_price'],
            'status' => 'pending',
        ]);

        $cloudinary = config('services.cloudinary.url')
            ? new \Cloudinary\Cloudinary(config('services.cloudinary.url'))
            : null;

        foreach ($request->file('images') as $image) {
            if ($cloudinary) {
                $result = $cloudinary->uploadApi()->upload($image->getRealPath(), [
                    'folder' => 'campus_mall/user_sales',
                ]);
                $url = $result['secure_url'];
            } else {
                $url = $image->store('user_sales', 'public');
            }
            UserSaleImage::create(['user_sale_id' => $sale->id, 'image_url' => $url]);
        }

        $sale->load('images');

        return response()->json(['sale' => $this->formatUserSale($sale)], 201);
    }

    public function accept(Request $request, UserSale $userSale)
    {
        if ($userSale->user_id !== $request->user()->id || $userSale->status !== 'offer_made') {
            abort(403);
        }
        $userSale->update(['status' => 'accepted']);

        return response()->json(['sale' => $this->formatUserSale($userSale->fresh('images'))]);
    }

    public function reject(Request $request, UserSale $userSale)
    {
        if ($userSale->user_id !== $request->user()->id || $userSale->status !== 'offer_made') {
            abort(403);
        }
        $userSale->update(['status' => 'rejected']);

        return response()->json(['sale' => $this->formatUserSale($userSale->fresh('images'))]);
    }

    public function destroy(Request $request, UserSale $userSale)
    {
        if ($userSale->user_id !== $request->user()->id) {
            abort(403);
        }
        if (in_array($userSale->status, ['accepted', 'completed'])) {
            return response()->json(['message' => 'Cannot delete completed trade-in'], 422);
        }
        $userSale->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
