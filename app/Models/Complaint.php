<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'ticket_number', 'type', 'reporter_name', 'reporter_wa',
        'company_name', 'job_title',
        'department', 'building', 'room_number', 'location',
        'category', 'priority', 'status', 'description', 'photos',
        'admin_notes', 'sla_deadline', 'resolved_at',
    ];

    protected $casts = [
        'sla_deadline' => 'datetime',
        'resolved_at'  => 'datetime',
        'photos'       => 'array',
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

        $latestTicket = self::query()
            ->where('ticket_number', 'like', $prefix . '-%')
            ->orderByDesc('id')
            ->value('ticket_number');

        $nextNumber = 1;

        if ($latestTicket && preg_match('/^' . preg_quote($prefix, '/') . '-(?:\d{8}-)?(\d{4})$/', $latestTicket, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        }

        do {
            $ticket = sprintf('%s-%04d', $prefix, $nextNumber);
            $exists = self::where('ticket_number', $ticket)->exists();
            $nextNumber++;
        } while ($exists);

        return $ticket;
    }

    public static function computeSlaDeadline(string $type, string $priority): Carbon
    {
        $fallback = self::$slaHours[$type][$priority] ?? 24;
        $hours    = SlaSetting::getHours('ga', $type, $priority, $fallback);
        return now()->addHours($hours);
    }

    public function isOverdue(): bool
    {
        if (in_array($this->status, ['closed', 'rejected'])) return false;
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
            'receptionist' => 'Receptionist',
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
            'rejected' => 'status-rejected',
            default    => '',
        };
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'open'     => 'Open',
            'progress' => 'Progress',
            'closed'   => 'Closed',
            'rejected' => 'Rejected',
            default    => ucfirst($this->status),
        };
    }

    public function statusColor(): string
    {
        return match($this->status) {
            'open'     => '#dc3545',
            'progress' => '#fd7e14',
            'closed'   => '#198754',
            'rejected' => '#6c757d',
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
