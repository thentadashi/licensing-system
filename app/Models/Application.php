<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'application_type',
        'form_541',
        'picture',
        'signature',
        'receipt',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
