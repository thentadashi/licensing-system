<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (auth()->guard($guard)->check()) {
                
                // Only redirect if the user is trying to access login or registration pages
                if ($request->is('login') || $request->is('register') || $request->is('admin/login')) {
                    $role = auth()->user()->role;

                    if ($role === 'student') {
                        return redirect()->route('dashboard');
                    }

                    if (in_array($role, ['super_admin', 'clerk'])) {
                        return redirect()->route('admin.applications.index');
                    }

                    // Fallback
                    return redirect('/');
                }
            }
        }

        return $next($request);
    }
}
