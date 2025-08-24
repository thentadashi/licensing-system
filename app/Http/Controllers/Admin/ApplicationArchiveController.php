<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationArchive;
use Illuminate\Http\Request;

class ApplicationArchiveController extends Controller
{
    public function index()
    {
        $archives = ApplicationArchive::with(['application.user'])
            ->latest()
            ->paginate(10);

        return view('admin.applications.archives', compact('archives'));
    }

    public function store(Request $request, Application $application)
    {
        // dd('Archive button clicked for application ID: ' . $application->id);

        // prevent archiving if already trashed
        if ($application->trash) {
            return back()->with('success', 'This application is in Trash. Restore it first.');
        }
        if ($application->archive_status) {
            return back()->with('success', 'Application is already archived.');
        }

        ApplicationArchive::create([
            'application_id'            => $application->id,
            'archived_by'               => $request->user()->id,
            'previous_status'           => $application->status,
            'previous_progress_stage'   => $application->progress_stage,
            'note'                      => $request->input('note'),
        ]);

        $application->update([
            'archive_status' => 1,
        ]);

        return back()->with('success', 'Application archived.');
    }


    public function restore(ApplicationArchive $archive)
    {
        $app = $archive->application;

        // ✅ Unarchive flag
        $app->update([
            'archive_status' => 0,
        ]);

        $archive->delete();

        return back()->with('success', 'Application restored from archive.');
    }

    public function destroy(ApplicationArchive $archive)
    {
        $app = $archive->application;

        // ✅ Also reset archive_status when destroying archive record
        $app->update([
            'archive_status' => 0,
        ]);

        $archive->delete();

        return back()->with('success', 'Application removed from archive.');
    }
}
