<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Announcement extends Model
{
    protected $fillable = ['title', 'content', 'image', 'publish_date'];
    
    protected $casts = [
        'publish_date' => 'datetime',
    ];

    // Scope for published announcements
    public function scopePublished($query)
    {
        return $query->where(function($q) {
            $q->whereNull('publish_date')
              ->orWhere('publish_date', '<=', now());
        });
    }

    // Check if announcement is published
    public function isPublished()
    {
        return is_null($this->publish_date) || $this->publish_date <= now();
    }

    // Get status badge
    public function getStatusAttribute()
    {
        return $this->isPublished() ? 'Published' : 'Scheduled';
    }
}
