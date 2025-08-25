<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use App\Enums\ApplicationStatus;
use App\Enums\ProgressStage;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'application_type',
        'form_541',  // legacy
        'picture',   // legacy
        'signature', // legacy
        'receipt',   // legacy
        'status',
        'progress_stage',
        'admin_notes',
        'revision_files',
        'revision_notes',
        'archive_status', // ✅ new
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function files()
    {
        return $this->hasMany(ApplicationFile::class);
    }

    public function extraFields()
    {
        return $this->hasMany(ApplicationExtraField::class);
    }

    /**
     * Return files for display: merge new application_files with legacy columns.
     * Returns a Collection of arrays with keys: requirement_key, requirement_label, file_path
     */
    public function displayFiles(): Collection
    {
        $list = collect();

        // new-style files
        foreach ($this->files as $f) {
            $list->push([
                'requirement_key' => $f->requirement_key,
                'requirement_label' => $f->requirement_label,
                'file_path' => $f->file_path,
                'original_name' => $f->original_name,
            ]);
        }

        // legacy columns (if populated)
        if ($this->form_541) {
            $list->push([
                'requirement_key' => 'form_541',
                'requirement_label' => 'Form 541',
                'file_path' => $this->form_541,
                'original_name' => null,
            ]);
        }
        if ($this->picture) {
            $list->push([
                'requirement_key' => 'picture',
                'requirement_label' => '2x2 Picture',
                'file_path' => $this->picture,
                'original_name' => null,
            ]);
        }
        if ($this->signature) {
            $list->push([
                'requirement_key' => 'signature',
                'requirement_label' => 'E-signature',
                'file_path' => $this->signature,
                'original_name' => null,
            ]);
        }
        if ($this->receipt) {
            $list->push([
                'requirement_key' => 'receipt',
                'requirement_label' => 'Receipt',
                'file_path' => $this->receipt,
                'original_name' => null,
            ]);
        }

        return $list;
    }
    public function archive()
    {
        return $this->hasOne(\App\Models\ApplicationArchive::class);
    }

    public function trash()
    {
        return $this->hasOne(\App\Models\ApplicationTrash::class);
    }
        protected $casts = [
        'archive_status' => 'boolean',
        'revision_files' => 'array',
        'status' => ApplicationStatus::class,
        'progress_stage' => ProgressStage::class,
    ];

}