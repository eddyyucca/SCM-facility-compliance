<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\MenuPermission;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function canView(string $type): bool
    {
        if ($this->role === 'superadmin') return true;
        if ($this->role === 'hr') return false;
        if ($this->role === 'ga') return true; // GA bisa lihat semua tipe complaint
        return $this->role === $type;
    }

    /**
     * Tipe complaint GA yang boleh dilihat user ini.
     * Dipakai di DashboardController & ComplaintController.
     */
    public function gaTypes(): array
    {
        $all = ['receptionist', 'hk', 'laundry'];

        if (in_array($this->role, ['superadmin', 'ga'])) {
            return $all;
        }

        if (in_array($this->role, $all)) {
            return [$this->role];
        }

        return []; // hr — tidak punya akses ke GA
    }

    public function canAccessMenu(string $menuKey): bool
    {
        if ($this->role === 'superadmin') return true;
        return MenuPermission::isAllowed($menuKey, $this->role);
    }

    public function isHr(): bool
    {
        return $this->role === 'hr';
    }

    public function pushSubscriptions(): HasMany
    {
        return $this->hasMany(PushSubscription::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
