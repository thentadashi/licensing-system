<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Application;

class DashboardController extends Controller
{
    public function index()
    {
        // Count students (role = 'student')
        $studentsCount = User::where('role', 'student')->count();

        // Count pending applications (status = 'Pending')
        $pendingCount = Application::where('status', 'Pending')->count();

        return view('admin.dashboard', compact('studentsCount', 'pendingCount'));
    }
}
