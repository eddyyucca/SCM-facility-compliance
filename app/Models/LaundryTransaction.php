<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaundryTransaction extends Model
{
    protected $fillable = [
        'tanggal', 'mess_id', 'pob',
        'bag_in', 'kg_in', 'bag_out', 'kg_out',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'pob'     => 'integer',
        'bag_in'  => 'integer',
        'kg_in'   => 'float',
        'bag_out' => 'integer',
        'kg_out'  => 'float',
    ];

    public function mess(): BelongsTo
    {
        return $this->belongsTo(LaundryMess::class, 'mess_id');
    }

    /** 
     * Target calculation:
     * Asumsi 1 Bag = 6 pcs = 2.5 kg
     * Asumsi standar 1 Orang (POB) = 1 Bag / hari.
     * Jadi Target Kg = POB * 2.5
     */
    public function getTargetKgAttribute(): float
    {
        return $this->pob * 2.5;
    }

    public function getTargetBagAttribute(): int
    {
        return $this->pob;
    }

    /**
     * Persentase Aktual (Masuk) vs Target Kg
     */
    public function getAchievementPercentageAttribute(): float
    {
        $target = $this->target_kg;
        if ($target <= 0) return 0;
        return ($this->kg_in / $target) * 100;
    }
}
