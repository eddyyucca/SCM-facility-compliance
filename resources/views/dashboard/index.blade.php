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
            <div class="inner"><h3 id="stat-total">{{ $summary['total'] }}</h3><p>Total Laporan</p></div>
            <div class="icon"><i class="fas fa-clipboard-list"></i></div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="small-box bg-gradient-danger text-white">
            <div class="inner"><h3 id="stat-open">{{ $summary['open'] }}</h3><p>Open</p></div>
            <div class="icon"><i class="fas fa-inbox"></i></div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="small-box bg-gradient-warning">
            <div class="inner"><h3 id="stat-progress">{{ $summary['progress'] }}</h3><p>Progress</p></div>
            <div class="icon"><i class="fas fa-spinner"></i></div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="small-box bg-gradient-success text-white">
            <div class="inner"><h3 id="stat-closed">{{ $summary['closed'] }}</h3><p>Closed</p></div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="small-box text-dark" style="background:#e2e3e5;">
            <div class="inner"><h3 id="stat-rejected">{{ $summary['rejected'] }}</h3><p>Rejected</p></div>
            <div class="icon"><i class="fas fa-ban"></i></div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="small-box text-white" style="background:linear-gradient(135deg,#c0392b,#922b21);">
            <div class="inner"><h3 id="stat-overdue">{{ $summary['overdue'] }}</h3><p>Overdue SLA</p></div>
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
                    <i class="fas fa-concierge-bell mr-1"></i> Receptionist
                    <span class="badge badge-danger badge-pill ml-1" id="badge-rcp-total"
                          style="{{ ($typeStats['receptionist']['tab_total'] ?? 0) > 0 ? '' : 'display:none' }}">{{ $typeStats['receptionist']['tab_total'] ?? 0 }}</span>
                </a>
            </li>
            @endif
            @if(in_array('hk', $userTypes))
            <li class="nav-item">
                <a class="nav-link" id="tab-hk-link" data-toggle="tab" href="#tab-hk" role="tab">
                    <i class="fas fa-broom mr-1"></i> Housekeeping
                    <span class="badge badge-danger badge-pill ml-1" id="badge-hk-total"
                          style="{{ ($typeStats['hk']['tab_total'] ?? 0) > 0 ? '' : 'display:none' }}">{{ $typeStats['hk']['tab_total'] ?? 0 }}</span>
                </a>
            </li>
            @endif
            @if(in_array('laundry', $userTypes))
            <li class="nav-item">
                <a class="nav-link" id="tab-ldy-link" data-toggle="tab" href="#tab-laundry" role="tab">
                    <i class="fas fa-tshirt mr-1"></i> Laundry
                    <span class="badge badge-danger badge-pill ml-1" id="badge-ldy-total"
                          style="{{ ($typeStats['laundry']['tab_total'] ?? 0) > 0 ? '' : 'display:none' }}">{{ $typeStats['laundry']['tab_total'] ?? 0 }}</span>
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
                            <div style="position:relative;height:200px;">
                                <canvas id="trendChart"></canvas>
                            </div>
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
                            <div style="position:relative;height:180px;width:100%;">
                                <canvas id="statusChart"></canvas>
                            </div>
                            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:12px;justify-content:center;font-size:.77rem;">
                                <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#dc3545;margin-right:4px;"></span>Open</span>
                                <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#ffc107;margin-right:4px;"></span>Progress</span>
                                <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#198754;margin-right:4px;"></span>Closed</span>
                                <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#adb5bd;margin-right:4px;"></span>Rejected</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Outstanding SLA --}}
            <div id="outstanding-section" style="{{ $outstanding->count() === 0 ? 'display:none' : '' }}">
            <div class="card border-danger mb-3" style="border-width:2px!important;">
                <div class="card-header d-flex align-items-center justify-content-between" style="background:#fff5f5;border-bottom:1px solid #f8d7da;">
                    <h3 class="card-title text-danger mb-0" style="font-size:.88rem;font-weight:700;">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Outstanding — SLA Terlampaui (<span id="outstanding-count">{{ $outstanding->count() }}</span>)
                    </h3>
                    <span id="live-dot" title="Live update aktif"
                          style="display:inline-flex;align-items:center;gap:5px;font-size:.72rem;color:#198754;">
                        <span style="width:8px;height:8px;border-radius:50%;background:#198754;display:inline-block;animation:livepulse 2s infinite;"></span>
                        Live
                    </span>
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
                            <tbody id="outstanding-tbody">
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
            </div>
        </div>

        {{-- ── Tab: Receptionist ── --}}
        @if(in_array('receptionist', $userTypes))
        <div class="tab-pane fade p-3" id="tab-rcp" role="tabpanel">
            @include('dashboard._type_tab', ['type' => 'receptionist', 'label' => 'Receptionist', 'icon' => 'fa-concierge-bell', 'color' => 'primary', 'stats' => $typeStats['receptionist'] ?? []])
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
<style>
@keyframes livepulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: .4; transform: scale(1.3); }
}
@keyframes flashNum {
    0%   { color: inherit; }
    30%  { color: #0d6efd; }
    100% { color: inherit; }
}
.num-updated { animation: flashNum .6s ease; }
</style>
<script>
// ── Date range — diset dari PHP agar selalu konsisten dengan initial render ──
let activeDateFrom = '{{ $dateFrom->format("Y-m-d") }}';
let activeDateTo   = '{{ $dateTo->format("Y-m-d") }}';

// ── Flatpickr ──
flatpickr('#date_from', {
    dateFormat: 'Y-m-d',
    locale: { firstDayOfWeek: 1 },
    onChange: function(sel, str) {
        fpTo.set('minDate', str);
        activeDateFrom = str;
    }
});
const fpTo = flatpickr('#date_to', {
    dateFormat: 'Y-m-d',
    locale: { firstDayOfWeek: 1 },
    minDate: activeDateFrom,
    onChange: function(sel, str) {
        activeDateTo = str;
    }
});

// ── Trend Chart ──
const trendCtx  = document.getElementById('trendChart').getContext('2d');
const trendChart = new Chart(trendCtx, {
    type: 'line',
    data: @json($chartData),
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } } },
            x: { ticks: { font: { size: 10 } } }
        }
    }
});

// ── Status Donut ──
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Open','Progress','Closed','Rejected'],
        datasets: [{
            data: [{{ $summary['open'] }}, {{ $summary['progress'] }}, {{ $summary['closed'] }}, {{ $summary['rejected'] }}],
            backgroundColor: ['#dc3545','#ffc107','#198754','#adb5bd'],
            borderWidth: 2, borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: { legend: { display: false } }
    }
});

// ── Live Dashboard Update ──
let _dashRefreshing = false;

function setNum(id, val) {
    const el = document.getElementById(id);
    if (!el || String(el.textContent).trim() === String(val)) return;
    el.textContent = val;
    el.classList.remove('num-updated');
    void el.offsetWidth;
    el.classList.add('num-updated');
}

function setBadge(id, val) {
    const el = document.getElementById(id);
    if (!el) return;
    el.textContent = val;
    el.style.display = val > 0 ? '' : 'none';
}

function renderOutstanding(list) {
    const section = document.getElementById('outstanding-section');
    const tbody   = document.getElementById('outstanding-tbody');
    const count   = document.getElementById('outstanding-count');
    if (!section || !tbody) return;

    if (!list || list.length === 0) {
        section.style.display = 'none';
        return;
    }

    section.style.display = '';
    if (count) count.textContent = list.length;

    tbody.innerHTML = list.map(c => `
        <tr class="overdue">
            <td><a href="${c.url}" class="ticket-link">${c.ticket}</a></td>
            <td><span class="badge ${c.type_badge}">${c.type_label}</span></td>
            <td>${c.reporter}</td>
            <td><span class="badge ${c.status_badge}">${c.status_label}</span></td>
            <td class="text-danger font-weight-bold" style="font-size:.82rem;">${c.sla_deadline ?? '—'}</td>
            <td class="text-danger" style="font-size:.82rem;">${c.late_hours} jam</td>
            <td><a href="${c.url}" class="btn btn-xs btn-outline-primary">Lihat</a></td>
        </tr>`).join('');
}

window.refreshDashboard = async function() {
    if (_dashRefreshing) return;
    _dashRefreshing = true;
    try {
        const params = new URLSearchParams({
            date_from: activeDateFrom,
            date_to: activeDateTo,
            _ts: Date.now().toString(),
        });
        const res = await fetch(`/api/dashboard-stats?${params}`, {
            cache: 'no-store',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
        });
        if (!res.ok) return;
        const d = await res.json();

        // Summary cards
        setNum('stat-total',    d.summary.total);
        setNum('stat-open',     d.summary.open);
        setNum('stat-progress', d.summary.progress);
        setNum('stat-closed',   d.summary.closed);
        setNum('stat-rejected', d.summary.rejected ?? 0);
        setNum('stat-overdue',  d.summary.overdue);

        // Donut chart
        statusChart.data.datasets[0].data = [
            d.summary.open, d.summary.progress, d.summary.closed, d.summary.rejected ?? 0
        ];
        statusChart.update('none');

        // Trend chart
        trendChart.data.labels = d.chartData.labels;
        trendChart.data.datasets.forEach((ds, i) => {
            if (d.chartData.datasets[i]) {
                ds.data = d.chartData.datasets[i].data;
            }
        });
        trendChart.update('none');

        // Per-type stats
        ['receptionist','hk','laundry'].forEach(type => {
            const s = d.typeStats[type];
            if (!s) return;
            setNum(`stat-${type}-total`,    s.total);
            setNum(`stat-${type}-open`,     s.open);
            setNum(`stat-${type}-progress`, s.progress);
            setNum(`stat-${type}-closed`,   s.closed);
            setNum(`stat-${type}-rejected`, s.rejected ?? 0);
            setNum(`stat-${type}-overdue`,  s.overdue);

            const al = document.getElementById(`overdue-alert-${type}`);
            if (al) al.style.display = s.overdue > 0 ? '' : 'none';
        });

        // Tab open badges
        setBadge('badge-rcp-total', d.typeStats.receptionist?.tab_total ?? 0);
        setBadge('badge-hk-total',  d.typeStats.hk?.tab_total ?? 0);
        setBadge('badge-ldy-total', d.typeStats.laundry?.tab_total ?? 0);

        // Outstanding table
        renderOutstanding(d.outstanding);

    } catch(e) {}
    finally { _dashRefreshing = false; }
};

// Dashboard manages its own refresh interval (30s), independent of notification polling
setInterval(window.refreshDashboard, 30000);
</script>
@endpush
