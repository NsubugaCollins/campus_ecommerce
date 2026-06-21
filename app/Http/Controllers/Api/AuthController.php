<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlacklistedEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->role !== 'admin') {
            $this->applyLoginRewards($user);
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $this->formatUser($user),
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => [
                'required', 'email', 'max:255', 'unique:users',
                function ($attribute, $value, $fail) {
                    if (BlacklistedEmail::where('email', $value)->exists()) {
                        $fail('This email address has been banned.');
                    }
                },
            ],
            'password' => 'required|string|min:8|confirmed',
            'referral_code' => 'nullable|string|exists:users,referral_code',
        ]);

        $referrer = null;
        if ($request->filled('referral_code')) {
            $referrer = User::where('referral_code', strtoupper($request->referral_code))->first();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'referred_by' => $referrer?->id,
            'points' => $referrer ? 20 : 0,
        ]);

        if ($referrer) {
            $referrer->increment('points', 50);
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $this->formatUser($user),
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }

    public function user(Request $request)
    {
        return response()->json(['user' => $this->formatUser($request->user())]);
    }

    protected function applyLoginRewards(User $user): void
    {
        $user->increment('login_count');

        $today = now()->startOfDay();
        if (! $user->last_login_at || $user->last_login_at < $today) {
            $user->points += 10;
        }
        $user->last_login_at = now();
        $user->save();
    }

    protected function formatUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role ?? 'user',
            'points' => (int) $user->points,
            'referral_code' => $user->referral_code,
            'login_count' => (int) $user->login_count,
            'subscription_type' => $user->subscription_type,
            'subscription_expires_at' => $user->subscription_expires_at ? $user->subscription_expires_at->toIso8601String() : null,
        ];
    }
}
