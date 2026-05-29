<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaundryEquipment extends Model
{
    protected $table = 'laundry_equipment';

    protected $fillable = [
        'area_id', 'category', 'model_name', 'capacity_kg',
        'process_time_minutes', 'unit_number', 'status', 'remarks', 'sort_order',
    ];

    protected $casts = [
        'capacity_kg'          => 'float',
        'process_time_minutes' => 'integer',
        'unit_number'          => 'integer',
        'sort_order'           => 'integer',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(LaundryArea::class, 'area_id');
    }

    /* ── Accessors ── */

    public function isReady(): bool      { return $this->status === 'ready'; }
    public function isBreakdown(): bool  { return $this->status === 'breakdown'; }

    /** Label tampilan: "Speedqueen 15 kg #2" */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->model_name} #{$this->unit_number}";
    }

    /**
     * Berapa siklus yang bisa diselesaikan dalam $workingMinutes menit.
     */
    public function cyclesInPeriod(int $workingMinutes): int
    {
        if ($this->process_time_minutes <= 0) return 0;
        return (int) floor($workingMinutes / $this->process_time_minutes);
    }

    /**
     * Total output kg dalam satu periode kerja (hanya jika status ready).
     * Sesuai request, ditambahkan PA Benchmark 85% untuk hasil yang logis.
     */
    public function outputKg(int $workingMinutes, float $paBenchmark = 0.85): float
    {
        if ($this->isBreakdown()) return 0;
        return $this->cyclesInPeriod($workingMinutes) * $this->capacity_kg * $paBenchmark;
    }

    public function getPaColorAttribute(): string
    {
        return $this->isReady() ? 'success' : 'danger';
    }

    /**
     * Otomatis tentukan unit_number berikutnya untuk model ini di area ini.
     */
    public static function nextUnitNumber(int $areaId, string $category, string $modelName): int
    {
        return (int) self::where('area_id', $areaId)
            ->where('category', $category)
            ->where('model_name', $modelName)
            ->max('unit_number') + 1;
    }
}
