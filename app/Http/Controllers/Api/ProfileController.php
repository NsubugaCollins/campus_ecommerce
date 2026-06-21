<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $referrals = $user->referrals()->orderByDesc('created_at')->get(['id', 'name', 'email', 'created_at']);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'points' => (int) $user->points,
                'referral_code' => $user->referral_code,
                'subscription_type' => $user->subscription_type,
                'subscription_expires_at' => $user->subscription_expires_at ? $user->subscription_expires_at->toIso8601String() : null,
            ],
            'stats' => [
                'total_orders' => $user->orders()->count(),
                'completed_orders' => $user->orders()->where('status', 'completed')->count(),
                'total_spent' => (float) $user->orders()->where('payment_status', 'paid')->sum('total_amount'),
            ],
            'referrals' => $referrals,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($request->only('name', 'email', 'phone'));

        return response()->json(['message' => 'Profile updated', 'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'points' => (int) $user->points,
            'referral_code' => $user->referral_code,
            'subscription_type' => $user->subscription_type,
            'subscription_expires_at' => $user->subscription_expires_at ? $user->subscription_expires_at->toIso8601String() : null,
        ]]);
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password updated']);
    }
}
