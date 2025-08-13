<?php

namespace App\Http\Controllers;
use App\Models\Announcement;
use Carbon\Carbon;

class dashboardController extends Controller
{
    public function dashboard()
    {
        $announcements = Announcement::where(function($query) {
                $query->whereNull('publish_date')
                      ->orWhere('publish_date', '<=', Carbon::now());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Dashboard', compact('announcements'));
    }
}
