<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Carbon\Carbon;

class dashboardController extends Controller
{
    public function dashboard()
    {
        $announcements = Announcement::visible()
            ->published()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Dashboard', compact('announcements'));
    }
}
