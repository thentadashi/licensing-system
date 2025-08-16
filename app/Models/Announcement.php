<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'image',
        'publish_date',
        'status',
    ];

    protected $casts = [
        'publish_date' => 'datetime',
    ];

    // Append a computed label (does not override the 'status' column).
    protected $appends = ['status_label'];

    // Scope for published (time-based) announcements
    public function scopePublished($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('publish_date')
              ->orWhere('publish_date', '<=', now());
        });
    }

    // Scope for visible announcements
    public function scopeVisible($query)
    {
        return $query->where('status', 'visible');
    }

    // Check if announcement is published (time)
    public function isPublished(): bool
    {
        return is_null($this->publish_date) || $this->publish_date <= now();
    }

    // Check if announcement is visible (status column)
    public function isVisible(): bool
    {
        // Use the raw column value
        $raw = $this->getRawOriginal('status');
        return $raw === 'visible';
    }

    // Computed label for UI
    public function getStatusLabelAttribute(): string
    {
        if (!$this->isVisible()) {
            return 'Hidden';
        }

        return $this->isPublished() ? 'Published' : 'Scheduled';
    }
}
