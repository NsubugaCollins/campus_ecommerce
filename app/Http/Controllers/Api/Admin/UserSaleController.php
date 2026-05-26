<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Concerns\FormatsUserSales;
use App\Http\Controllers\Controller;
use App\Models\UserSale;
use Illuminate\Http\Request;

class UserSaleController extends Controller
{
    use FormatsUserSales;

    public function index()
    {
        $sales = UserSale::with(['user:id,name,email', 'images'])
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

    public function show(UserSale $userSale)
    {
        $userSale->load(['user:id,name,email', 'images']);

        return response()->json(['sale' => $this->formatUserSale($userSale)]);
    }

    public function makeOffer(Request $request, UserSale $userSale)
    {
        $validated = $request->validate([
            'offered_price' => 'required|numeric|min:0',
            'admin_notes' => 'nullable|string',
        ]);

        $userSale->update([
            'offered_price' => $validated['offered_price'],
            'admin_notes' => $validated['admin_notes'] ?? $userSale->admin_notes,
            'status' => 'offer_made',
        ]);

        return response()->json(['sale' => $this->formatUserSale($userSale->fresh(['user', 'images']))]);
    }

    public function updateStatus(Request $request, UserSale $userSale)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,under_review,offer_made,accepted,rejected,completed',
            'admin_notes' => 'nullable|string',
        ]);

        $update = ['status' => $validated['status']];
        if ($request->has('admin_notes')) {
            $update['admin_notes'] = $validated['admin_notes'];
        }
        $userSale->update($update);

        return response()->json(['sale' => $this->formatUserSale($userSale->fresh(['user', 'images']))]);
    }
}
