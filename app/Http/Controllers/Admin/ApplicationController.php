<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Notifications\ApplicationStatusChanged;
use App\Notifications\RevisionRequested;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $applications = Application::with(['user', 'files', 'extraFields', 'archive', 'trash'])
            ->whereDoesntHave('trash') // ✅ exclude trashed applications
            ->where('archive_status', 0) // ✅ exclude archived applications
            ->latest()
            ->paginate(10);

        $userRole = auth()->user()->role;

        return view('admin.applications.index', compact('applications', 'userRole'));
    }

    // ✅ Request Revision feature
    public function requestRevision(Request $request, $id)
    {
        $app = Application::findOrFail($id);

        $validated = $request->validate([
            'files' => 'required|array',
            'files.*' => 'string',
            'notes' => 'nullable|string'
        ]);

        $app->revision_files = $validated['files']; // no json_encode
        $app->revision_notes = $validated['notes'] ?? null;
        $app->status = 'Revision Requested'; // ✅ changed to new status
        $app->progress_stage = 'Revision request'; // ✅ changed to new progress stage
        $app->admin_notes = 'Revision requested by admin. Please upload the requested files to the portal.';
        $app->save();

        $app->user->notify(new RevisionRequested($app)); // 🔔 notify student

        return back()->with('success', 'Revision request sent to student.');
    }
    
    public function show(Application $application)
    {
        return view('admin.applications.show', compact('application'));
    }

    public function update(Request $request, Application $application)
    {
        // Get the old status
        $old = $application->getOriginal('status')->value;

        $data = $request->validate([
            // ✅ include Revision Requested as an allowed status
            'status' => 'required|in:Pending,Under Review,Approved,Rejected,Revision Requested',
            'progress_stage' => 'nullable|in:Submitted,Under Review,Processing License,Ready for Release,Completed,Revision request,Rejected',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $application->update([
            'status' => $data['status'],
            'progress_stage' => $data['progress_stage'] ?? $application->progress_stage,
            'admin_notes' => $data['admin_notes'] ?? $application->admin_notes,
            // ✅ clear revision fields if status changed from 'Revision Requested'
            'revision_files' => $data['status'] === 'Revision Requested' ? $application->revision_files : null,
            'revision_notes' => $data['status'] === 'Revision Requested' ? $application->revision_notes : null,
        ]);

        // Notify user
        if ($old !== $application->status->value) {
            $application->user->notify(
                new ApplicationStatusChanged(
                    $application,
                    $old,
                    $application->status->value
                )
            );
        }

        return redirect()->back()->with('success', 'Application updated successfully.');
    }
}
