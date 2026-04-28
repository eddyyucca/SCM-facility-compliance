<?php

namespace App\Models;

use App\Models\SlaSetting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class HrRequest extends Model
{
    protected $fillable = [
        'ticket_number',
        'employee_name',
        'employee_id',
        'company_name',
        'department',
        'position',
        'phone',
        'email',
        'service_type',
        'priority',
        'period',
        'status',
        'description',
        'attachments',
        'admin_notes',
        'sla_deadline',
        'resolved_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'sla_deadline' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public static array $slaHours = [
        'normal' => 72,
        'penting' => 24,
        'mendesak' => 8,
    ];

    public static function generateTicket(): string
    {
        $prefix = 'HR';
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

    public static function computeSlaDeadline(string $priority): Carbon
    {
        $fallback = self::$slaHours[$priority] ?? self::$slaHours['normal'];
        $hours    = SlaSetting::getHours('hr', 'hr_request', $priority, $fallback);
        return now()->addHours($hours);
    }

    public function typeLabel(): string
    {
        return 'Human Resources';
    }

    public function reporterName(): string
    {
        return $this->employee_name;
    }

    public function reporterPhone(): ?string
    {
        return $this->phone;
    }

    public function slaTargetHours(): int
    {
        return self::$slaHours[$this->priority] ?? self::$slaHours['normal'];
    }

    public function isOverdue(): bool
    {
        if (in_array($this->status, ['closed', 'rejected'], true)) {
            return false;
        }

        return $this->sla_deadline && now()->isAfter($this->sla_deadline);
    }

    public function slaHoursLeft(): float
    {
        if (!$this->sla_deadline) {
            return 0;
        }

        return round(now()->diffInMinutes($this->sla_deadline, false) / 60, 1);
    }

    public function priorityLabel(): string
    {
        return match ($this->priority) {
            'normal' => 'Normal',
            'penting' => 'Penting',
            'mendesak' => 'Mendesak',
            default => ucfirst($this->priority),
        };
    }

    public function priorityBadgeClass(): string
    {
        return match ($this->priority) {
            'mendesak' => 'badge-urgent',
            'penting' => 'badge-high',
            'normal' => 'badge-medium',
            default => '',
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'open' => 'status-open',
            'progress' => 'status-progress',
            'closed' => 'status-closed',
            'rejected' => 'status-rejected',
            default => '',
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'open' => 'Open',
            'progress' => 'Progress',
            'closed' => 'Closed',
            'rejected' => 'Rejected',
            default => ucfirst($this->status),
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'open' => '#dc3545',
            'progress' => '#fd7e14',
            'closed' => '#198754',
            'rejected' => '#6c757d',
            default => '#6c757d',
        };
    }
}
