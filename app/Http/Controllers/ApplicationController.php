<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationFile;
use App\Models\ApplicationExtraField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    /**
     * Status constants
     */
    private const STATUS_PENDING = 'Pending';
    private const STATUS_SUBMITTED = 'Submitted';
    private const STATUS_REVISION_REQUESTED = 'Revision Requested';

    /**
     * Request a revision for an application.
     */
    public function requestRevision(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        $validated = $request->validate([
            'files' => 'required|array',
            'files.*' => 'string',
            'notes' => 'nullable|string'
        ]);

        $application->update([
            'revision_files' => json_encode($validated['files']),
            'revision_notes' => $validated['notes'] ?? null,
            'status' => self::STATUS_REVISION_REQUESTED,
        ]);

        return back()->with('success', 'Revision request sent to student.');
    }



    
    /**
     * Display user applications.
     */
    public function application()
    {
        $applications = Application::with(['files', 'extraFields'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $requirements = $this->requirements();

        return view('application', compact('applications', 'requirements'));
    }

    /**
     * Store a new application.
     */
    public function store(Request $request)
    {
        $requirements = $this->requirements();
        $type = $request->input('application_type');

        if (!isset($requirements[$type])) {
            return redirect()->back()
                ->withErrors(['application_type' => 'Invalid application type'])
                ->withInput();
        }

        $rules = $this->buildValidationRules($requirements[$type]);
        $validated = $request->validate($rules);

        $application = Application::create([
            'user_id' => Auth::id(),
            'application_type' => $type,
            'status' => self::STATUS_PENDING,
            'progress_stage' => self::STATUS_SUBMITTED,
        ]);

        $this->storeExtraFields($application->id, $request->input('extra', []));
        $this->storeApplicationFiles($application->id, $requirements[$type]['files'], $request);

        return back()->with('success', 'Application submitted successfully!');
    }

    /**
     * Build dynamic validation rules from the requirements map.
     */
    private function buildValidationRules(array $map): array
    {
        $rules = ['application_type' => 'required|string'];

        // Extra fields
        foreach ($map['extra_fields'] ?? [] as $field => $config) {
            $required = $config['required'] ?? false;
            $type = $config['type'] ?? 'string';
            $baseRule = $required ? 'required' : 'sometimes';

            if ($type === 'array') {
                $rules["extra.$field"] = "$baseRule|array";
                $rules["extra.$field.*"] = 'string';
            } else {
                $rules["extra.$field"] = "$baseRule|string";
            }
        }

        // Files
        foreach ($map['files'] ?? [] as $key => $cfg) {
            $rules["files.$key"] = $this->getFileValidationRule($cfg);
        }

        return $rules;
    }

    /**
     * Return validation rule for a specific file type.
     */
    private function getFileValidationRule(array $cfg): string
    {
        $required = ($cfg['required'] ?? false) ? 'required|' : 'sometimes|';
        return match ($cfg['type'] ?? '') {
            'pdf' => $required . 'file|mimes:pdf|max:2048',
            'image' => $required . 'image|mimes:jpg,jpeg,png|max:2048',
            'png' => $required . 'image|mimes:png|max:1024',
            'any_doc' => $required . 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'image_or_pdf' => $required . 'file|mimes:jpg,jpeg,png,pdf|max:5120',
            default => $required . 'file|max:5120',
        };
    }

    /**
     * Store extra fields for an application.
     */
    private function storeExtraFields(int $appId, array $extra)
    {
        foreach ($extra as $field => $value) {
            $v = is_array($value) ? implode(',', $value) : $value;
            ApplicationExtraField::create([
                'application_id' => $appId,
                'field_name' => $field,
                'field_value' => $v,
            ]);
        }
    }

        /**
         * Store uploaded files for an application.
         */
        private function storeApplicationFiles(int $appId, array $fileRequirements, Request $request)
        {
            $userId = Auth::id();

            foreach ($fileRequirements as $key => $cfg) {
                if ($request->hasFile("files.$key")) {
                    $file = $request->file("files.$key");
                    $ext = strtolower($file->getClientOriginalExtension());
                    $filename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $key) . '.' . $ext;

                    $path = $file->storeAs("applications/{$userId}/{$appId}", $filename, 'public');

                    ApplicationFile::create([
                        'application_id' => $appId,
                        'requirement_key' => $key,
                        'requirement_label' => $cfg['label'],
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'file_type' => $ext,
                        'file_size' => $file->getSize(),
                    ]);
                }
            }
        }

    public function reupload(Request $request, Application $application)
    {
        // Make sure only the student who owns the application can reupload
        if ($application->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Validate re-uploaded files
        $validated = $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|max:2048', // 2MB per file
        ]);

        foreach ($validated['files'] as $key => $file) {
            // Delete old file if exists
            $oldFile = $application->files()->where('requirement_key', $key)->first();
            if ($oldFile && $oldFile->file_path && Storage::disk('public')->exists($oldFile->file_path)) {
                Storage::disk('public')->delete($oldFile->file_path);
            }

            // Store new file
            $path = $file->store('applications', 'public');

            // Generate label from key (you can adjust if you have a config)
            $label = ucfirst(str_replace('_', ' ', $key));

            // Save or update record in application_files table
            $application->files()->updateOrCreate(
                ['requirement_key' => $key],
                [
                    'file_path' => $path,
                    'requirement_label' => $label,
                ]
            );
        }

        // Clear revision request info after successful reupload
        $application->revision_files = null;
        $application->revision_notes = null;
        $application->status = 'Pending';
        $application->progress_stage = 'Submitted';
        $application->admin_notes = "Your application has been updated and is awaiting review.";
        $application->touch();
        $application->save();

        return back()->with('success', 'Files reuploaded successfully.');
    }

    /**
     * Return the mapping of application types and their required extras/files.
     * Keep keys small and safe (no spaces) — these keys will be used as input names.
     */
    protected function requirements(): array
    {
        return [
            'PEL' => [
                'label' => 'PEL Number Application',
                'extra_fields' => [
                    // none
                ],
                'files' => [
                    'form_541' => ['label' => '541 Form', 'required' => true, 'type' => 'pdf'],
                    'signature' => ['label' => 'E-signature', 'required' => true, 'type' => 'png'],
                    'picture' => ['label' => '2x2 Picture', 'required' => true, 'type' => 'image'],
                    'receipt' => ['label' => 'Receipt', 'required' => true, 'type' => 'image_or_pdf'],
                ],
            ],

            'PPL' => [
                'label' => 'PPL Checkride Application',
                'extra_fields' => [
                    'issuance_type' => ['label' => 'Issuance or Reissuance', 'required' => true],
                    'aircraft' => ['label' => 'Aircraft', 'required' => true],
                ],
                'files' => [
                    'flight_certificate' => ['label' => 'Flight Certificate', 'required' => true, 'type' => 'any_doc'],
                    'logbook' => ['label' => 'Logbook', 'required' => true, 'type' => 'pdf'],
                    'form_541' => ['label' => '541 Form', 'required' => true, 'type' => 'pdf'],
                    'knowledge_test' => ['label' => 'Knowledge Test Report', 'required' => true, 'type' => 'pdf'],
                    'signature' => ['label' => 'E-signature', 'required' => true, 'type' => 'png'],
                    'picture' => ['label' => '2x2 Picture', 'required' => true, 'type' => 'image'],
                    'receipt' => ['label' => 'Receipt', 'required' => true, 'type' => 'image_or_pdf'],
                ],
            ],

            'CPL' => [
                'label' => 'CPL Checkride Application',
                'extra_fields' => [
                    'issuance_type' => ['label' => 'Issuance or Reissuance', 'required' => true],
                    'aircraft' => ['label' => 'Aircraft', 'required' => true],
                ],
                'files' => [
                    'flight_certificate' => ['label' => 'Flight Certificate', 'required' => true, 'type' => 'any_doc'],
                    'logbook' => ['label' => 'Logbook', 'required' => true, 'type' => 'pdf'],
                    'form_541' => ['label' => '541 Form', 'required' => true, 'type' => 'pdf'],
                    'knowledge_test' => ['label' => 'Knowledge Test Report', 'required' => true, 'type' => 'pdf'],
                    'signature' => ['label' => 'E-signature', 'required' => true, 'type' => 'png'],
                    'picture' => ['label' => '2x2 Picture', 'required' => true, 'type' => 'image'],
                    'receipt' => ['label' => 'Receipt', 'required' => true, 'type' => 'image_or_pdf'],
                ],
            ],

            'AR' => [
                'label' => 'AR Checkride Application',
                'extra_fields' => [
                    'issuance_type' => ['label' => 'Issuance or Reissuance', 'required' => true],
                    'aircraft' => ['label' => 'Aircraft', 'required' => true],
                ],
                'files' => [
                    'flight_certificate' => ['label' => 'Flight Certificate', 'required' => true, 'type' => 'any_doc'],
                    'logbook' => ['label' => 'Logbook', 'required' => true, 'type' => 'pdf'],
                    'form_541' => ['label' => '541 Form', 'required' => true, 'type' => 'pdf'],
                    'knowledge_test' => ['label' => 'Knowledge Test Report', 'required' => true, 'type' => 'pdf'],
                    'signature' => ['label' => 'E-signature', 'required' => true, 'type' => 'png'],
                    'picture' => ['label' => '2x2 Picture', 'required' => true, 'type' => 'image'],
                    'receipt' => ['label' => 'Receipt', 'required' => true, 'type' => 'image_or_pdf'],
                ],
            ],

            'GI' => [
                'label' => 'GI Checkride Application',
                'extra_fields' => [
                    'issuance_type' => ['label' => 'Issuance or Reissuance', 'required' => true],
                    'gi_subjects' => ['label' => 'Subjects', 'required' => true, 'type' => 'array'],
                ],
                'files' => [
                    'flight_certificate' => ['label' => 'Flight Certificate', 'required' => true, 'type' => 'any_doc'],
                    'form_541' => ['label' => '541 Form', 'required' => true, 'type' => 'pdf'],
                    'knowledge_test' => ['label' => 'Knowledge Test Report', 'required' => true, 'type' => 'pdf'],
                    'signature' => ['label' => 'E-signature', 'required' => true, 'type' => 'png'],
                    'picture' => ['label' => '2x2 Picture', 'required' => true, 'type' => 'image'],
                    'receipt' => ['label' => 'Receipt', 'required' => true, 'type' => 'image_or_pdf'],
                ],
            ],

            'FI' => [
                'label' => 'FI Checkride Application',
                'extra_fields' => [
                    'issuance_type' => ['label' => 'Issuance or Reissuance', 'required' => true],
                    'aircraft' => ['label' => 'Aircraft', 'required' => true],
                ],
                'files' => [
                    'flight_certificate' => ['label' => 'Flight Certificate', 'required' => true, 'type' => 'any_doc'],
                    'logbook' => ['label' => 'Logbook', 'required' => true, 'type' => 'pdf'],
                    'form_541' => ['label' => '541 Form', 'required' => true, 'type' => 'pdf'],
                    'knowledge_test' => ['label' => 'Knowledge Test Report', 'required' => true, 'type' => 'pdf'],
                    'signature' => ['label' => 'E-signature', 'required' => true, 'type' => 'png'],
                    'picture' => ['label' => '2x2 Picture', 'required' => true, 'type' => 'image'],
                    'receipt' => ['label' => 'Receipt', 'required' => true, 'type' => 'image_or_pdf'],
                ],
            ],
        ];
    }
}
