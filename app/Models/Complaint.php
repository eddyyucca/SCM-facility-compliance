<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Complaint extends Model
{
    protected $fillable = [
        'ticket_number', 'type', 'reporter_name', 'reporter_wa',
        'department', 'building', 'room_number', 'location',
        'category', 'priority', 'status', 'description',
        'admin_notes', 'sla_deadline', 'resolved_at',
    ];

    protected $casts = [
        'sla_deadline' => 'datetime',
        'resolved_at'  => 'datetime',
    ];

    // SLA in hours per type + priority
    public static array $slaHours = [
        'receptionist' => ['rendah' => 72, 'sedang' => 24, 'tinggi' => 8,   'urgent' => 2],
        'hk'           => ['rendah' => 24, 'sedang' => 8,  'tinggi' => 4,   'urgent' => 1],
        'laundry'      => ['rendah' => 120,'sedang' => 48, 'tinggi' => 24,  'urgent' => 8],
    ];

    public static function generateTicket(string $type): string
    {
        $prefix = match($type) {
            'receptionist' => 'RCP',
            'hk'           => 'HKP',
            'laundry'      => 'LDY',
            default        => 'GEN',
        };
        $date  = now()->format('Ymd');
        $count = self::whereDate('created_at', today())
                     ->where('type', $type)
                     ->count() + 1;
        return sprintf('%s-%s-%04d', $prefix, $date, $count);
    }

    public static function computeSlaDeadline(string $type, string $priority): Carbon
    {
        $hours = self::$slaHours[$type][$priority] ?? 24;
        return now()->addHours($hours);
    }

    public function isOverdue(): bool
    {
        if ($this->status === 'closed') return false;
        return $this->sla_deadline && now()->isAfter($this->sla_deadline);
    }

    public function slaHoursLeft(): float
    {
        if (!$this->sla_deadline) return 0;
        return round(now()->diffInMinutes($this->sla_deadline, false) / 60, 1);
    }

    public function typeLabel(): string
    {
        return match($this->type) {
            'receptionist' => 'Resepsionis',
            'hk'           => 'Housekeeping',
            'laundry'      => 'Laundry',
            default        => ucfirst($this->type),
        };
    }

    public function priorityBadgeClass(): string
    {
        return match($this->priority) {
            'urgent' => 'badge-urgent',
            'tinggi' => 'badge-high',
            'sedang' => 'badge-medium',
            'rendah' => 'badge-low',
            default  => '',
        };
    }

    public function statusBadgeClass(): string
    {
        return match($this->status) {
            'open'     => 'status-open',
            'progress' => 'status-progress',
            'closed'   => 'status-closed',
            default    => '',
        };
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'open'     => 'Open',
            'progress' => 'Progress',
            'closed'   => 'Closed',
            default    => ucfirst($this->status),
        };
    }

    public function statusColor(): string
    {
        return match($this->status) {
            'open'     => '#dc3545',
            'progress' => '#fd7e14',
            'closed'   => '#198754',
            default    => '#6c757d',
        };
    }

    /** All buildings flat list */
    public static function buildingList(): array
    {
        $groups = config('buildings', []);
        $flat   = [];
        foreach ($groups as $items) {
            foreach ($items as $b) {
                $flat[] = $b;
            }
        }
        return $flat;
    }
}
