<?php
namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function dashboard()
    {
        $applications = Application::with('user')->where('user_id', Auth::id())->latest()->get();
        return view('application', compact('application'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'application_type' => 'required|string',
            'form_541' => 'required|file|mimes:pdf|max:2048',
            'picture' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'signature' => 'required|image|mimes:png|max:1024',
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = $request->only('application_type');
        $data['user_id'] = Auth::id();
        $data['form_541'] = $request->file('form_541')->store('uploads', 'public');
        $data['picture'] = $request->file('picture')->store('uploads', 'public');
        $data['signature'] = $request->file('signature')->store('uploads', 'public');
        $data['receipt'] = $request->file('receipt')->store('uploads', 'public');

        Application::create($data);

        return redirect()->back()->with('success', 'Application submitted successfully!');
    }
}