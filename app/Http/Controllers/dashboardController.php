<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class dashboardController extends Controller
{
    public function dashboard()
    {
        $announcements = Announcement::visible()
            ->published()
            ->orderBy('created_at', 'desc')
            ->get();

        $files = File::files(public_path('downloadable_forms'));

        return view('Dashboard', compact('announcements', 'files'));
    }

}
