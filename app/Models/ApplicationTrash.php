<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationTrash extends Model
{
    protected $fillable = [
        'application_id',
        'trashed_by',
        'previous_status',
        'previous_progress_stage',
        'reason',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function trashedBy()
    {
        return $this->belongsTo(User::class, 'trashed_by');
    }
}
