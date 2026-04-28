<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class MenuPermission extends Model
{
    protected $fillable = ['menu_key', 'menu_label', 'menu_section', 'sort_order', 'allowed_roles'];

    protected $casts = [
        'allowed_roles' => 'array',
    ];

    private static ?Collection $_cache = null;

    public static function clearCache(): void
    {
        static::$_cache = null;
    }

    public static function allCached(): Collection
    {
        if (static::$_cache === null) {
            static::$_cache = static::orderBy('sort_order')->get()->keyBy('menu_key');
        }

        return static::$_cache;
    }

    public static function isAllowed(string $menuKey, string $role): bool
    {
        $menu = static::allCached()->get($menuKey);

        if (! $menu) {
            return false;
        }

        return in_array($role, $menu->allowed_roles ?? []);
    }

    public static function allRoles(): array
    {
        return ['superadmin', 'ga', 'receptionist', 'hk', 'laundry', 'hr'];
    }

    public static function roleLabels(): array
    {
        return [
            'superadmin'   => 'Super Admin',
            'ga'           => 'GA',
            'receptionist' => 'Receptionist',
            'hk'           => 'Housekeeping',
            'laundry'      => 'Laundry',
            'hr'           => 'HR',
        ];
    }
}
