<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationArchive extends Model
{
    protected $fillable = [
    'application_id',
    'archived_by',
    'previous_status',
    'previous_progress_stage',
    'note',
 ];


    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function archivedBy()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }
}
