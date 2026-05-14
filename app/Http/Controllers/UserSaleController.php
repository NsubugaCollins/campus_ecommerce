<?php

namespace App\Http\Controllers;

use App\Models\UserSale;
use App\Models\UserSaleImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSaleController extends Controller
{
    public function index()
    {
        $sales = UserSale::where('user_id', Auth::id())
            ->with('images')
            ->latest()
            ->paginate(10);

        return view('user_sales.index', compact('sales'));
    }

    public function create()
    {
        // Define some standard categories
        $categories = [
            'Electronics', 'Furniture', 'Beddings', 'Fashion', 
            'Accessories', 'Beauty', 'Scholastic Materials', 'Sporting Goods'
        ];
        
        return view('user_sales.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
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
                'user_id' => Auth::id(),
                'product_name' => $validated['product_name'],
                'category' => $validated['category'],
                'condition' => $validated['condition'],
                'description' => $validated['description'],
                'expected_price' => $validated['expected_price'],
                'status' => 'pending',
            ]);

            if ($request->hasFile('images')) {
                $cloudinary = null;
                if (config('services.cloudinary.url')) {
                    $cloudinary = new \Cloudinary\Cloudinary(config('services.cloudinary.url'));
                }

                foreach ($request->file('images') as $image) {
                    if ($cloudinary) {
                        $result = $cloudinary->uploadApi()->upload($image->getRealPath(), [
                            'folder' => 'campus_mall/user_sales'
                        ]);
                        $url = $result['secure_url'];
                    } else {
                        $url = $image->store('user_sales', 'public');
                    }

                    UserSaleImage::create([
                        'user_sale_id' => $sale->id,
                        'image_url' => $url,
                    ]);
                }
            }

            return redirect()->route('user-sales.index')->with('success', 'Your trade-in request has been submitted successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to submit request: ' . $e->getMessage());
        }
    }

    public function show(UserSale $userSale)
    {
        if ($userSale->user_id !== Auth::id()) {
            abort(403);
        }

        $userSale->load('images');
        return view('user_sales.show', compact('userSale'));
    }

    public function acceptOffer(UserSale $userSale)
    {
        if ($userSale->user_id !== Auth::id() || $userSale->status !== 'offer_made') {
            abort(403);
        }

        $userSale->update(['status' => 'accepted']);

        return back()->with('success', 'You have accepted the offer. Our team will contact you shortly for fulfillment.');
    }

    public function rejectOffer(UserSale $userSale)
    {
        if ($userSale->user_id !== Auth::id() || $userSale->status !== 'offer_made') {
            abort(403);
        }

        $userSale->update(['status' => 'rejected']);

        return back()->with('success', 'You have rejected the offer.');
    }

    public function destroy(UserSale $userSale)
    {
        if ($userSale->user_id !== Auth::id()) {
            abort(403);
        }

        // Prevent deletion if already completed or accepted (optional, but safer)
        if (in_array($userSale->status, ['accepted', 'completed'])) {
            return back()->with('error', 'Cannot delete a trade-in that has already been accepted or completed.');
        }

        $userSale->delete();

        return redirect()->route('user-sales.index')->with('success', 'Trade-in request deleted successfully.');
    }
}
