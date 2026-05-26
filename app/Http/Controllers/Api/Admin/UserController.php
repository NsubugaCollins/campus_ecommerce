<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlacklistedEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where(function ($q) {
            $q->where('role', '!=', 'admin')->orWhereNull('role');
        })->orderByDesc('created_at')
            ->get()
            ->map(fn ($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'phone' => $u->phone,
                'points' => (int) $u->points,
                'created_at' => $u->created_at?->toIso8601String(),
            ]);

        return response()->json(['users' => $users]);
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'Cannot delete yourself'], 422);
        }
        if ($user->role === 'admin') {
            return response()->json(['message' => 'Cannot delete admin'], 422);
        }

        BlacklistedEmail::firstOrCreate(
            ['email' => $user->email],
            ['reason' => 'Deleted by admin']
        );
        $user->delete();

        return response()->json(['message' => 'User deleted and blacklisted']);
    }

    public function profile(Request $request)
    {
        $admin = $request->user();

        return response()->json([
            'user' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->role,
            ],
        ]);
    }

    public function updateProfile(Request $request)
    {
        $admin = $request->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$admin->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }
        $admin->save();

        return response()->json(['message' => 'Profile updated', 'user' => [
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => $admin->role,
        ]]);
    }
}
