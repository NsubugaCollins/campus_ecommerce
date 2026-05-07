<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->role === 'admin') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Admins cannot perform user activities.'], 403);
            }

            return redirect()->route('admin.dashboard')
                ->with('error', 'Admins cannot perform user activities.');
        }

        return $next($request);
    }
}
