<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationFile extends Model
{
    protected $fillable = [
        'application_id',
        'requirement_key',
        'requirement_label',
        'file_path',
        'original_name',
        'file_type',
        'file_size',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
