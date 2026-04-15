@extends('layouts.app')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')

{{-- ── Date Filter Bar ── --}}
<form method="GET" action="{{ route('dashboard') }}" id="date-form">
    <div class="date-filter-bar">
        <i class="fas fa-calendar-alt text-primary"></i>
        <label>Dari:</label>
        <input type="text" id="date_from" name="date_from"
               class="form-control form-control-sm flatpickr-input"
               value="{{ $dateFrom->format('Y-m-d') }}"
               placeholder="Tanggal awal" readonly style="width:130px;">
        <label>Sampai:</label>
        <input type="text" id="date_to" name="date_to"
               class="form-control form-control-sm flatpickr-input"
               value="{{ $dateTo->format('Y-m-d') }}"
               placeholder="Tanggal akhir" readonly style="width:130px;">

        {{-- Preset buttons --}}
        <div class="btn-group btn-group-sm ml-2" role="group">
            <a href="{{ route('dashboard', ['date_from' => now()->toDateString(), 'date_to' => now()->toDateString()]) }}"
               class="btn btn-outline-secondary btn-sm {{ $dateFrom->isToday() && $dateTo->isToday() ? 'active' : '' }}">
               Hari Ini
            </a>
            <a href="{{ route('dashboard', ['date_from' => now()->startOfWeek()->toDateString(), 'date_to' => now()->toDateString()]) }}"
               class="btn btn-outline-secondary btn-sm">
               Minggu Ini
            </a>
            <a href="{{ route('dashboard', ['date_from' => now()->startOfMonth()->toDateString(), 'date_to' => now()->toDateString()]) }}"
               class="btn btn-outline-secondary btn-sm">
               Bulan Ini
            </a>
            <a href="{{ route('dashboard', ['date_from' => now()->subDays(29)->toDateString(), 'date_to' => now()->toDateString()]) }}"
               class="btn btn-outline-secondary btn-sm">
               30 Hari
            </a>
        </div>

        <button type="submit" class="btn btn-primary btn-sm ml-1">
            <i class="fas fa-filter mr-1"></i> Terapkan
        </button>

        <span class="ml-auto" style="font-size:.78rem;color:#aaa;">
            {{ $dateFrom->format('d M Y') }} — {{ $dateTo->format('d M Y') }}
        </span>
    </div>
</form>

{{-- ── Summary Cards (always visible) ── --}}
<div class="row mb-3">
    <div class="col-6 col-md-2">
        <div class="small-box bg-gradient-primary text-white">
            <div class="inner"><h3>{{ $summary['total'] }}</h3><p>Total Laporan</p></div>
            <div class="icon"><i class="fas fa-clipboard-list"></i></div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="small-box bg-gradient-danger text-white">
            <div class="inner"><h3>{{ $summary['open'] }}</h3><p>Open</p></div>
            <div class="icon"><i class="fas fa-inbox"></i></div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="small-box bg-gradient-warning">
            <div class="inner"><h3>{{ $summary['progress'] }}</h3><p>Progress</p></div>
            <div class="icon"><i class="fas fa-spinner"></i></div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="small-box bg-gradient-success text-white">
            <div class="inner"><h3>{{ $summary['closed'] }}</h3><p>Closed</p></div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="small-box text-white" style="background:linear-gradient(135deg,#c0392b,#922b21);">
            <div class="inner"><h3>{{ $summary['overdue'] }}</h3><p>🚨 Overdue SLA</p></div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>
</div>

{{-- ── Tabs (Summary + per type) ── --}}
<div class="card">
    <div class="card-header p-0" style="border-radius:14px 14px 0 0;background:#f8f9fa;">
        <ul class="nav nav-tabs" id="dashTabs" role="tablist" style="border-bottom:none;padding:0 16px;">
            <li class="nav-item">
                <a class="nav-link active" id="tab-summary-link" data-toggle="tab" href="#tab-summary" role="tab">
                    <i class="fas fa-chart-bar mr-1"></i> Ringkasan
                </a>
            </li>
            @if(in_array('receptionist', $userTypes))
            <li class="nav-item">
                <a class="nav-link" id="tab-rcp-link" data-toggle="tab" href="#tab-rcp" role="tab">
                    <i class="fas fa-concierge-bell mr-1"></i> Resepsionis
                    @if(isset($typeStats['receptionist']['open']) && $typeStats['receptionist']['open'] > 0)
                        <span class="badge badge-danger badge-pill ml-1">{{ $typeStats['receptionist']['open'] }}</span>
                    @endif
                </a>
            </li>
            @endif
            @if(in_array('hk', $userTypes))
            <li class="nav-item">
                <a class="nav-link" id="tab-hk-link" data-toggle="tab" href="#tab-hk" role="tab">
                    <i class="fas fa-broom mr-1"></i> Housekeeping
                    @if(isset($typeStats['hk']['open']) && $typeStats['hk']['open'] > 0)
                        <span class="badge badge-danger badge-pill ml-1">{{ $typeStats['hk']['open'] }}</span>
                    @endif
                </a>
            </li>
            @endif
            @if(in_array('laundry', $userTypes))
            <li class="nav-item">
                <a class="nav-link" id="tab-ldy-link" data-toggle="tab" href="#tab-laundry" role="tab">
                    <i class="fas fa-tshirt mr-1"></i> Laundry
                    @if(isset($typeStats['laundry']['open']) && $typeStats['laundry']['open'] > 0)
                        <span class="badge badge-danger badge-pill ml-1">{{ $typeStats['laundry']['open'] }}</span>
                    @endif
                </a>
            </li>
            @endif
        </ul>
    </div>

    <div class="tab-content p-0" style="border:1px solid #dee2e6;border-top:none;border-radius:0 0 14px 14px;">

        {{-- ── Tab: Ringkasan ── --}}
        <div class="tab-pane fade show active p-3" id="tab-summary" role="tabpanel">
            <div class="row">
                {{-- Trend chart --}}
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title" style="font-size:.9rem;font-weight:700;">
                                <i class="fas fa-chart-line text-primary mr-2"></i>
                                Tren Laporan Masuk
                                <small class="text-muted ml-2" style="font-size:.75rem;">
                                    {{ $dateFrom->format('d M') }} — {{ $dateTo->format('d M Y') }}
                                </small>
                            </h3>
                        </div>
                        <div class="card-body" style="padding:12px 16px;">
                            <canvas id="trendChart" height="90"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Donut chart --}}
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title" style="font-size:.9rem;font-weight:700;">
                                <i class="fas fa-chart-pie text-info mr-2"></i> Status
                            </h3>
                        </div>
                        <div class="card-body" style="padding:12px;display:flex;flex-direction:column;align-items:center;">
                            <canvas id="statusChart" style="max-height:180px;"></canvas>
                            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:12px;justify-content:center;font-size:.77rem;">
                                <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#dc3545;margin-right:4px;"></span>Open</span>
                                <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#ffc107;margin-right:4px;"></span>In Progress</span>
                                <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#198754;margin-right:4px;"></span>Resolved</span>
                                <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#6c757d;margin-right:4px;"></span>Closed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Outstanding SLA --}}
            @if($outstanding->count() > 0)
            <div class="card border-danger mb-3" style="border-width:2px!important;">
                <div class="card-header" style="background:#fff5f5;border-bottom:1px solid #f8d7da;">
                    <h3 class="card-title text-danger" style="font-size:.88rem;font-weight:700;">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Outstanding — SLA Terlampaui ({{ $outstanding->count() }})
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Tiket</th>
                                    <th>Tipe</th>
                                    <th>Pelapor</th>
                                    <th>Status</th>
                                    <th>SLA Deadline</th>
                                    <th>Terlambat</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($outstanding as $c)
                                <tr class="overdue">
                                    <td><a href="{{ route('complaints.show', $c) }}" class="ticket-link">{{ $c->ticket_number }}</a></td>
                                    <td><span class="badge {{ $c->type === 'receptionist' ? 'type-rec' : ($c->type === 'hk' ? 'type-hk' : 'type-ldy') }}">{{ $c->typeLabel() }}</span></td>
                                    <td>{{ $c->reporter_name }}</td>
                                    <td><span class="badge {{ $c->statusBadgeClass() }}">{{ $c->statusLabel() }}</span></td>
                                    <td class="text-danger font-weight-bold" style="font-size:.82rem;">{{ $c->sla_deadline?->format('d M Y H:i') }}</td>
                                    <td class="text-danger" style="font-size:.82rem;">{{ abs(round($c->sla_deadline?->diffInHours(now()), 1)) }} jam</td>
                                    <td><a href="{{ route('complaints.show', $c) }}" class="btn btn-xs btn-outline-primary">Lihat</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- ── Tab: Resepsionis ── --}}
        @if(in_array('receptionist', $userTypes))
        <div class="tab-pane fade p-3" id="tab-rcp" role="tabpanel">
            @include('dashboard._type_tab', ['type' => 'receptionist', 'label' => 'Resepsionis', 'icon' => 'fa-concierge-bell', 'color' => 'primary', 'stats' => $typeStats['receptionist'] ?? []])
        </div>
        @endif

        {{-- ── Tab: Housekeeping ── --}}
        @if(in_array('hk', $userTypes))
        <div class="tab-pane fade p-3" id="tab-hk" role="tabpanel">
            @include('dashboard._type_tab', ['type' => 'hk', 'label' => 'Housekeeping', 'icon' => 'fa-broom', 'color' => 'success', 'stats' => $typeStats['hk'] ?? []])
        </div>
        @endif

        {{-- ── Tab: Laundry ── --}}
        @if(in_array('laundry', $userTypes))
        <div class="tab-pane fade p-3" id="tab-laundry" role="tabpanel">
            @include('dashboard._type_tab', ['type' => 'laundry', 'label' => 'Laundry', 'icon' => 'fa-tshirt', 'color' => 'warning', 'stats' => $typeStats['laundry'] ?? []])
        </div>
        @endif

    </div>
</div>

@endsection

@push('scripts')
<script>
// ── Flatpickr ──
flatpickr('#date_from', {
    dateFormat: 'Y-m-d',
    locale: { firstDayOfWeek: 1 },
    onChange: function(sel, str) {
        fpTo.set('minDate', str);
    }
});
const fpTo = flatpickr('#date_to', {
    dateFormat: 'Y-m-d',
    locale: { firstDayOfWeek: 1 },
    minDate: document.getElementById('date_from').value,
});

// ── Trend Chart ──
const trendCtx  = document.getElementById('trendChart').getContext('2d');
const chartData = @json($chartData);

new Chart(trendCtx, {
    type: 'line',
    data: chartData,
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } }
        },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } } },
            x: { ticks: { font: { size: 10 } } }
        }
    }
});

// ── Status Donut ──
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Open','Progress','Closed'],
        datasets: [{
            data: [{{ $summary['open'] }}, {{ $summary['progress'] }}, {{ $summary['closed'] }}],
            backgroundColor: ['#dc3545','#ffc107','#198754'],
            borderWidth: 2, borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: { legend: { display: false } }
    }
});
</script>
@endpush
