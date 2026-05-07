<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the user's profile & account settings page.
     */
    public function show()
    {
        $user = auth()->user();
        $totalOrders     = $user->orders()->count();
        $completedOrders = $user->orders()->where('status', 'completed')->count();
        $totalSpent      = $user->orders()->where('payment_status', 'paid')->sum('total_amount');
        $referrals       = $user->referrals()->orderBy('created_at', 'desc')->get();
        $referralPoints  = $referrals->count() * 50; // 50 pts per successful referral

        return view('user.profile', compact(
            'user', 'totalOrders', 'completedOrders', 'totalSpent',
            'referrals', 'referralPoints'
        ));
    }

    /**
     * Update the user's personal information.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('profile_success', 'Profile updated successfully!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password'      => 'required',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'The current password is incorrect.'])
                ->with('open_tab', 'security');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('security_success', 'Password changed successfully!')->with('open_tab', 'security');
    }
}
