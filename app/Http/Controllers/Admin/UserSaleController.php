<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SellOfferApprovedMail;
use App\Mail\SellOfferRejectedMail;
use App\Models\UserSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
            'admin_notes'   => 'nullable|string',
        ]);

        $userSale->update([
            'offered_price' => $validated['offered_price'],
            'admin_notes'   => $validated['admin_notes'],
            'status'        => 'offer_made',
        ]);

        // Notify the user that an offer has been made
        try {
            $userSale->load('user');
            Mail::to($userSale->user->email)->send(new SellOfferApprovedMail($userSale));
        } catch (\Exception $e) {
            \Log::error('Sell offer approved email failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Offer has been sent to the user.');
    }

    public function updateStatus(Request $request, UserSale $userSale)
    {
        $validated = $request->validate([
            'status'      => 'required|in:pending,under_review,offer_made,accepted,rejected,completed',
            'admin_notes' => 'nullable|string',
        ]);

        $updateData = ['status' => $validated['status']];
        if ($request->has('admin_notes')) {
            $updateData['admin_notes'] = $validated['admin_notes'];
        }

        $userSale->update($updateData);

        // Notify user when their sell request is rejected
        if ($validated['status'] === 'rejected') {
            try {
                $userSale->load('user');
                Mail::to($userSale->user->email)->send(new SellOfferRejectedMail($userSale));
            } catch (\Exception $e) {
                \Log::error('Sell offer rejected email failed: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Status updated successfully.');
    }
}
