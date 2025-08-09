<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // If not logged in → let auth middleware handle redirect
        if (!auth()->check()) {
            return $next($request);
        }

        // If logged in but role not allowed
        if (!in_array(auth()->user()->role, $roles)) {
            // Send user to their correct dashboard
            if (auth()->user()->role === 'student') {
                return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
            }

            if (in_array(auth()->user()->role, ['super_admin', 'clerk'])) {
                return redirect()->route('admin.dashboard')->with('error', 'Unauthorized access.');
            }

            // Fallback: send home
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}
