<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class dashboardController extends Controller
{
    public function dashboard()
    {
        $dashboard = Dashboard::with('user')->where('user_id', Auth::id())->latest()->get();
        return view('Dashboard', compact('dashboard'));
    }

}