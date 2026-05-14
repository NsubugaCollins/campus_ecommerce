<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserSale;
use Illuminate\Http\Request;

class UserSaleController extends Controller
{
    public function index()
    {
        $sales = UserSale::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.user_sales.index', compact('sales'));
    }

    public function show(UserSale $userSale)
    {
        $userSale->load(['user', 'images']);
        return view('admin.user_sales.show', compact('userSale'));
    }

    public function makeOffer(Request $request, UserSale $userSale)
    {
        $validated = $request->validate([
            'offered_price' => 'required|numeric|min:0',
            'admin_notes' => 'nullable|string',
        ]);

        $userSale->update([
            'offered_price' => $validated['offered_price'],
            'admin_notes' => $validated['admin_notes'],
            'status' => 'offer_made',
        ]);

        return back()->with('success', 'Offer has been sent to the user.');
    }

    public function updateStatus(Request $request, UserSale $userSale)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,under_review,offer_made,accepted,rejected,completed',
            'admin_notes' => 'nullable|string',
        ]);

        $updateData = ['status' => $validated['status']];
        if ($request->has('admin_notes')) {
            $updateData['admin_notes'] = $validated['admin_notes'];
        }

        $userSale->update($updateData);

        return back()->with('success', 'Status updated successfully.');
    }
}
