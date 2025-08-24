<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationTrash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ApplicationTrashController extends Controller
{
    public function index()
    {
        $trashes = ApplicationTrash::with(['application.user'])->latest()->paginate(10);
        return view('admin.applications.trash', compact('trashes'));
    }

    public function store(Request $request, Application $application)
    {
        // prevent trashing if already trashed
        if ($application->trash) {
            return back()->with('success', 'Application already in Trash.');
        }

        // remove archive record if any (moving to trash)
        optional($application->archive)->delete();

        ApplicationTrash::create([
            'application_id' => $application->id,
            'trashed_by' => $request->user()->id,
            'previous_status' => $application->status,
            'previous_progress_stage' => $application->progress_stage,
            'reason' => $request->input('reason'),
        ]);

        // OPTIONAL: visually mark in list (no hard delete)
        $application->update([
            'status' => 'Trashed',
            'progress_stage' => $application->progress_stage ?? 'Trashed',
        ]);

        return back()->with('success', 'Application moved to Trash.');
    }

    public function restore(ApplicationTrash $trash)
    {
        $app = $trash->application;

        // restore previous status/progress
        $app->update([
            'status' => $trash->previous_status ?? 'Pending',
            'progress_stage' => $trash->previous_progress_stage ?? 'Submitted',
        ]);

        $trash->delete();

        return back()->with('success', 'Application restored.');
    }

    public function destroy(ApplicationTrash $trash)
    {
        // PERMANENT DELETE (files + DB rows)
        $app = $trash->application;

        DB::transaction(function () use ($app, $trash) {
            // delete physical files
            foreach ($app->files as $f) {
                if (!empty($f->file_path) && Storage::disk('public')->exists($f->file_path)) {
                    Storage::disk('public')->delete($f->file_path);
                }
            }

            // delete related rows (adjust to your relations)
            $app->files()->delete();
            $app->extraFields()->delete();
            optional($app->archive)->delete();

            // remove trash record then the application
            $trash->delete();
            $app->delete();
        });

        return back()->with('success', 'Application permanently deleted.');
    }
}
