<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Try to login and check role
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $request->remember)) {
            
            $user = Auth::user();

            // Allow only super_admin & clerk
            if (in_array($user->role, ['super_admin', 'clerk'])) {
                return redirect()->route('admin.applications.index');
            }

            // If student tries to login here → logout and show error
            Auth::logout();
            return redirect()->route('admin.login')->withErrors([
                'email' => 'Unauthorized access. Admin credentials required.'
            ]);
        }

        // If login fails
        return back()->withErrors([
            'email' => 'Invalid credentials or account not found.',
        ])->withInput($request->only('email', 'remember'));
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
