{{-- Type tab partial: $type, $label, $icon, $color, $stats --}}
@php
    $colorMap = [
        'primary' => ['box' => 'bg-primary', 'text' => '#0d6efd'],
        'success' => ['box' => 'bg-success', 'text' => '#198754'],
        'warning' => ['box' => 'bg-warning', 'text' => '#a16207'],
    ];
    $c = $colorMap[$color] ?? $colorMap['primary'];
@endphp

{{-- Mini stat cards --}}
<div class="row mb-3">
    <div class="col-6 col-md-3 mb-2">
        <div class="info-box" style="border-radius:12px;">
            <span class="info-box-icon {{ $c['box'] }} elevation-0" style="border-radius:12px 0 0 12px;">
                <i class="fas {{ $icon }}"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text" style="font-size:.78rem;">Total</span>
                <span class="info-box-number" style="font-weight:700;">{{ $stats['total'] ?? 0 }}</span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-2">
        <div class="info-box" style="border-radius:12px;">
            <span class="info-box-icon bg-danger elevation-0" style="border-radius:12px 0 0 12px;">
                <i class="fas fa-inbox"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text" style="font-size:.78rem;">Open</span>
                <span class="info-box-number font-weight-bold text-danger">{{ $stats['open'] ?? 0 }}</span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-2">
        <div class="info-box" style="border-radius:12px;">
            <span class="info-box-icon bg-warning elevation-0" style="border-radius:12px 0 0 12px;">
                <i class="fas fa-spinner"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text" style="font-size:.78rem;">Progress</span>
                <span class="info-box-number font-weight-bold" style="color:#856404;">{{ $stats['progress'] ?? 0 }}</span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-2">
        <div class="info-box" style="border-radius:12px;">
            <span class="info-box-icon bg-success elevation-0" style="border-radius:12px 0 0 12px;">
                <i class="fas fa-check"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text" style="font-size:.78rem;">Closed</span>
                <span class="info-box-number font-weight-bold text-success">{{ $stats['closed'] ?? 0 }}</span>
            </div>
        </div>
    </div>
</div>

@if(($stats['overdue'] ?? 0) > 0)
<div class="alert alert-danger py-2 px-3 mb-3" style="border-radius:10px;font-size:.85rem;">
    <i class="fas fa-exclamation-triangle mr-2"></i>
    <strong>{{ $stats['overdue'] }} laporan</strong> melewati batas SLA dan belum diselesaikan.
    <a href="{{ route('complaints.index', ['type' => $type, 'status' => 'open']) }}" class="ml-2 alert-link">Lihat sekarang →</a>
</div>
@endif

{{-- Recent complaints table --}}
<div class="card mb-0">
    <div class="card-header d-flex align-items-center justify-content-between py-2">
        <h3 class="card-title mb-0" style="font-size:.88rem;font-weight:700;">
            <i class="fas {{ $icon }} mr-2" style="color:{{ $c['text'] }};"></i>
            Laporan Terbaru — {{ $label }}
        </h3>
        <a href="{{ route('complaints.index', ['type' => $type]) }}"
           class="btn btn-sm btn-outline-primary" style="font-size:.78rem;">
            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr>
                        <th>Tiket</th>
                        <th>Pelapor</th>
                        @if($type === 'receptionist' || $type === 'laundry')
                        <th>Kamar</th>
                        @else
                        <th>Lokasi</th>
                        @endif
                        <th>Status</th>
                        <th>SLA</th>
                        <th>Waktu</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['recent'] ?? [] as $c)
                    <tr class="{{ $c->isOverdue() ? 'overdue' : '' }}">
                        <td>
                            <a href="{{ route('complaints.show', $c) }}" class="ticket-link">
                                {{ $c->ticket_number }}
                            </a>
                        </td>
                        <td>
                            <div style="font-weight:600;font-size:.85rem;">{{ $c->reporter_name }}</div>
                            @if($c->reporter_wa)
                            <div style="font-size:.75rem;color:#aaa;">{{ $c->reporter_wa }}</div>
                            @endif
                        </td>
                        <td style="font-size:.83rem;">{{ $c->room_number ?? $c->location ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $c->statusBadgeClass() }}" style="font-size:.74rem;">
                                {{ $c->statusLabel() }}
                            </span>
                        </td>
                        <td>
                            @if($c->isOverdue())
                                <span class="text-danger font-weight-bold" style="font-size:.76rem;">⚠ Overdue</span>
                            @elseif($c->sla_deadline && $c->status !== 'closed')
                                @php $h = $c->slaHoursLeft(); @endphp
                                <span style="font-size:.76rem;color:{{ $h < 4 ? '#dc3545' : '#198754' }};">
                                    {{ $h > 0 ? '+'.abs($h).'j' : abs($h).'j lalu' }}
                                </span>
                            @else
                                <span style="color:#aaa;font-size:.76rem;">—</span>
                            @endif
                        </td>
                        <td style="font-size:.76rem;color:#aaa;white-space:nowrap;">
                            {{ $c->created_at->diffForHumans() }}
                        </td>
                        <td>
                            <a href="{{ route('complaints.show', $c) }}"
                               class="btn btn-xs btn-outline-primary">Lihat</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4" style="font-size:.85rem;">
                            <i class="fas fa-inbox fa-2x mb-2 d-block text-light"></i>
                            Tidak ada laporan dalam periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
