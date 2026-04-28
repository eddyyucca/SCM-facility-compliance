<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlaSetting extends Model
{
    protected $fillable = ['module', 'type', 'priority', 'hours'];

    protected $casts = ['hours' => 'integer'];

    private static ?array $_cache = null;

    public static function clearCache(): void
    {
        static::$_cache = null;
    }

    /**
     * Ambil SLA hours dari DB (dengan in-memory cache per request).
     * Fallback ke $default jika setting belum ada.
     */
    public static function getHours(string $module, string $type, string $priority, int $default = 24): int
    {
        if (static::$_cache === null) {
            static::$_cache = static::all()
                ->keyBy(fn ($r) => "{$r->module}|{$r->type}|{$r->priority}")
                ->map(fn ($r) => $r->hours)
                ->all();
        }

        return static::$_cache["{$module}|{$type}|{$priority}"] ?? $default;
    }

    /** Label ramah untuk tampilan tipe GA */
    public static function gaTypeLabel(string $type): string
    {
        return match ($type) {
            'receptionist' => 'Receptionist',
            'hk'           => 'Housekeeping',
            'laundry'      => 'Laundry',
            default        => ucfirst($type),
        };
    }

    /** Label ramah untuk prioritas */
    public static function priorityLabel(string $priority): string
    {
        return match ($priority) {
            'rendah'   => 'Rendah',
            'sedang'   => 'Sedang',
            'tinggi'   => 'Tinggi',
            'urgent'   => 'Urgent',
            'normal'   => 'Normal',
            'penting'  => 'Penting',
            'mendesak' => 'Mendesak',
            default    => ucfirst($priority),
        };
    }

    /** Konversi jam ke string singkat, mis. "72 jam (3 hari)" */
    public static function hoursToHuman(int $hours): string
    {
        if ($hours < 24) {
            return "{$hours} jam";
        }

        $days = intdiv($hours, 24);
        $rem  = $hours % 24;

        return $rem > 0
            ? "{$hours} jam ({$days} hari {$rem} jam)"
            : "{$hours} jam ({$days} hari)";
    }
}
