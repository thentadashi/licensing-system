<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;

class ApplicationController extends Controller
{
    /**
     * Show paginated applications.
     */
    public function index(Request $request)
    {
        // simple paginate, 10 per page (change number as you like)
        $applications = Application::with('user')->latest()->paginate(10);

        $userRole = auth()->user()->role;

        return view('admin.applications.index', compact('applications', 'userRole'));
    }

    /**
     * Update application status.
     */
    public function update(Request $request, Application $application)
    {
        $data = $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected', // adjust allowed values if needed
        ]);

        $application->status = $data['status'];
        $application->save();

        return redirect()->back()->with('success', 'Application status updated.');
    }
}
