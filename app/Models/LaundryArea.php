<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaundryArea extends Model
{
    protected $fillable = ['name', 'description', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function equipment(): HasMany
    {
        return $this->hasMany(LaundryEquipment::class, 'area_id')
            ->orderBy('category')
            ->orderBy('model_name')
            ->orderBy('unit_number');
    }

    public function messes(): HasMany
    {
        return $this->hasMany(LaundryMess::class, 'area_id');
    }

    public function washers(): HasMany
    {
        return $this->hasMany(LaundryEquipment::class, 'area_id')
            ->where('category', 'washer')
            ->orderBy('model_name')->orderBy('unit_number');
    }

    public function dryers(): HasMany
    {
        return $this->hasMany(LaundryEquipment::class, 'area_id')
            ->where('category', 'dryer')
            ->orderBy('model_name')->orderBy('unit_number');
    }

    /* ── Aggregates ── */

    public function washerCount(): int         { return $this->equipment->where('category','washer')->count(); }
    public function dryerCount(): int          { return $this->equipment->where('category','dryer')->count(); }
    public function washerReady(): int         { return $this->equipment->where('category','washer')->where('status','ready')->count(); }
    public function dryerReady(): int          { return $this->equipment->where('category','dryer')->where('status','ready')->count(); }
    public function washerBreakdown(): int     { return $this->equipment->where('category','washer')->where('status','breakdown')->count(); }
    public function dryerBreakdown(): int      { return $this->equipment->where('category','dryer')->where('status','breakdown')->count(); }

    public function washerPa(): float
    {
        $total = $this->washerCount();
        return $total > 0 ? round(($this->washerReady() / $total) * 100, 1) : 100.0;
    }

    public function dryerPa(): float
    {
        $total = $this->dryerCount();
        return $total > 0 ? round(($this->dryerReady() / $total) * 100, 1) : 100.0;
    }

    public function overallPa(): float
    {
        $total = $this->washerCount() + $this->dryerCount();
        $ready = $this->washerReady() + $this->dryerReady();
        return $total > 0 ? round(($ready / $total) * 100, 1) : 100.0;
    }

    /** Total output kg semua washer ready dalam periode $workingMinutes menit. */
    public function washerOutputKg(int $workingMinutes): float
    {
        return $this->equipment
            ->where('category', 'washer')
            ->where('status', 'ready')
            ->sum(fn($e) => $e->outputKg($workingMinutes));
    }

    /** Total output kg semua dryer ready dalam periode $workingMinutes menit. */
    public function dryerOutputKg(int $workingMinutes): float
    {
        return $this->equipment
            ->where('category', 'dryer')
            ->where('status', 'ready')
            ->sum(fn($e) => $e->outputKg($workingMinutes));
    }
}
