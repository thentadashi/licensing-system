<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;


class ApplicationController extends Controller
{
    public function index()
    {
        // Get all applications with their related student
        $applications = Application::with('user')->latest()->get();

        // Get logged-in user's role
        $userRole = auth()->user()->role;

        // Pass data to the view
        return view('admin.applications.index', compact('applications', 'userRole'));
        
    }
}
