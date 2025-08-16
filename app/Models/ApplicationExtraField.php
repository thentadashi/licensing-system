<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationExtraField extends Model
{
    protected $fillable = [
        'application_id',
        'field_name',
        'field_value',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
