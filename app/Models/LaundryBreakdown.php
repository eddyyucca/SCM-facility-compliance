<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaundryBreakdown extends Model
{
    protected $fillable = [
        'equipment_id',
        'notes',
        'reported_by',
        'breakdown_at',
        'resolved_at',
        'downtime_minutes',
    ];

    protected $casts = [
        'breakdown_at' => 'datetime',
        'resolved_at'  => 'datetime',
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(LaundryEquipment::class, 'equipment_id');
    }

    public function isResolved(): bool
    {
        return $this->resolved_at !== null;
    }

    /**
     * Durasi downtime dalam format human-readable.
     */
    public function getDurationLabelAttribute(): string
    {
        $minutes = $this->downtime_minutes;

        if ($minutes === null) {
            if ($this->breakdown_at) {
                $minutes = (int) $this->breakdown_at->diffInMinutes(now());
            } else {
                return '–';
            }
        }

        if ($minutes < 60) {
            return "{$minutes} menit";
        }

        $hours = floor($minutes / 60);
        $mins  = $minutes % 60;

        if ($hours < 24) {
            return $mins > 0 ? "{$hours}j {$mins}m" : "{$hours} jam";
        }

        $days  = floor($hours / 24);
        $hrs   = $hours % 24;
        return $hrs > 0 ? "{$days}h {$hrs}j" : "{$days} hari";
    }
}
