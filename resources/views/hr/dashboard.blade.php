@extends('layouts.app')
@section('title', 'Dashboard Human Resources')
@section('page_title', 'Dashboard Human Resources')
@section('breadcrumb', 'Human Resources')

@push('styles')
<style>
    .stat-card {
        border-radius: 14px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 2px 10px rgba(0,0,0,.07);
        background: #fff;
        transition: transform .15s;
        min-height: 80px;
    }
    .stat-card:hover { transform: translateY(-2px); }
    .stat-icon {
        width: 46px; height: 46px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; flex-shrink: 0;
    }
    .stat-value { font-size: 1.75rem; font-weight: 800; line-height: 1; }
    .stat-label { font-size: .72rem; color: #6c757d; font-weight: 600; margin-top: 2px; text-transform: uppercase; letter-spacing: .04em; }

    .perf-card {
        border-radius: 14px; background: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,.07);
        padding: 16px 20px;
    }
    .perf-card .perf-title {
        font-size: .75rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .06em; color: #6c757d; margin-bottom: 12px;
    }
    .perf-row { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
    .perf-row:last-child { margin-bottom: 0; }
    .perf-bar-wrap { flex: 1; background: #f0f4f8; border-radius: 999px; height: 6px; overflow: hidden; }
    .perf-bar { height: 6px; border-radius: 999px; transition: width .4s; }
    .perf-badge {
        font-size: .8rem; font-weight: 700; min-width: 34px; text-align: right;
    }
    .perf-dot {
        width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0;
    }

    .sla-rate-ring {
        width: 90px; height: 90px;
    }
    .avg-big { font-size: 2rem; font-weight: 800; line-height: 1; }
    .avg-unit { font-size: .75rem; color: #6c757d; font-weight: 600; }

    .loading-overlay {
        position: absolute; inset: 0;
        background: rgba(255,255,255,.7);
        display: flex; align-items: center; justify-content: center;
        border-radius: 14px; z-index: 10;
        transition: opacity .2s;
    }
    .section-wrap { position: relative; }

    .period-badge {
        display: inline-block; padding: 3px 10px;
        background: #e8f5f3; color: #0f766e;
        border-radius: 999px; font-size: .72rem; font-weight: 700;
        margin-left: 6px;
    }
</style>
@endpush

@section('content')

{{-- Filter Bar --}}
<div class="date-filter-bar" id="filter-bar">
    <i class="fas fa-calendar-alt text-success"></i>
    <label>Dari:</label>
    <input type="text" id="date_from" name="date_from"
           class="form-control form-control-sm flatpickr-input"
           value="{{ $dateFrom->format('Y-m-d') }}" readonly style="width:130px;">
    <label>Sampai:</label>
    <input type="text" id="date_to" name="date_to"
           class="form-control form-control-sm flatpickr-input"
           value="{{ $dateTo->format('Y-m-d') }}" readonly style="width:130px;">
    <button type="button" id="btn-apply" class="btn btn-success btn-sm ml-1">
        <i class="fas fa-filter mr-1"></i> Terapkan
    </button>
    <a href="{{ route('hr.requests.index') }}" class="btn btn-outline-success btn-sm ml-auto">
        <i class="fas fa-list mr-1"></i> Semua Laporan HR
    </a>
</div>

{{-- ── ROW 1: Status Summary ── --}}
<div class="row mb-3" id="row-status">
    {{-- Total --}}
    <div class="col-6 col-md-4 col-lg-2 mb-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f5f3;">
                <i class="fas fa-layer-group" style="color:#0f766e;"></i>
            </div>
            <div>
                <div class="stat-value text-success" id="s-total">{{ $summary['total'] }}</div>
                <div class="stat-label">Total</div>
            </div>
        </div>
    </div>
    {{-- Open --}}
    <div class="col-6 col-md-4 col-lg-2 mb-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff0f0;">
                <i class="fas fa-inbox" style="color:#dc3545;"></i>
            </div>
            <div>
                <div class="stat-value text-danger" id="s-open">{{ $summary['open'] }}</div>
                <div class="stat-label">Open</div>
            </div>
        </div>
    </div>
    {{-- Progress --}}
    <div class="col-6 col-md-4 col-lg-2 mb-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff8e6;">
                <i class="fas fa-spinner" style="color:#fd7e14;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#fd7e14;" id="s-progress">{{ $summary['progress'] }}</div>
                <div class="stat-label">Progress</div>
            </div>
        </div>
    </div>
    {{-- Selesai --}}
    <div class="col-6 col-md-4 col-lg-2 mb-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f8ef;">
                <i class="fas fa-check-circle" style="color:#198754;"></i>
            </div>
            <div>
                <div class="stat-value text-success" id="s-closed">{{ $summary['closed'] }}</div>
                <div class="stat-label">Selesai</div>
            </div>
        </div>
    </div>
    {{-- Ditolak --}}
    <div class="col-6 col-md-4 col-lg-2 mb-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f5f5f5;">
                <i class="fas fa-times-circle" style="color:#6c757d;"></i>
            </div>
            <div>
                <div class="stat-value text-secondary" id="s-rejected">{{ $summary['rejected'] }}</div>
                <div class="stat-label">Ditolak</div>
            </div>
        </div>
    </div>
    {{-- Overdue --}}
    <div class="col-6 col-md-4 col-lg-2 mb-3">
        <div class="stat-card" style="border-left: 3px solid #dc3545;">
            <div class="stat-icon" style="background:#fff0f0;">
                <i class="fas fa-exclamation-triangle" style="color:#dc3545;"></i>
            </div>
            <div>
                <div class="stat-value text-danger" id="s-overdue">{{ $summary['overdue'] }}</div>
                <div class="stat-label">Overdue SLA</div>
            </div>
        </div>
    </div>
</div>

{{-- ── ROW 2: Performance Detail ── --}}
<div class="row mb-3">

    {{-- SLA Performance --}}
    <div class="col-md-4 mb-3">
        <div class="perf-card h-100">
            <div class="perf-title"><i class="fas fa-tachometer-alt mr-1"></i> Performa SLA</div>
            <div class="d-flex align-items-center gap-3 mb-3">
                <div style="position:relative;width:80px;height:80px;flex-shrink:0;">
                    <canvas id="slaRingChart" width="80" height="80"></canvas>
                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column;">
                        <span id="s-sla-rate-txt" style="font-size:1rem;font-weight:800;color:#0f766e;line-height:1;">
                            {{ $summary['sla_rate'] !== null ? $summary['sla_rate'].'%' : '—' }}
                        </span>
                        <span style="font-size:.6rem;color:#6c757d;font-weight:600;">Tepat SLA</span>
                    </div>
                </div>
                <div style="flex:1;">
                    <div class="perf-row">
                        <div class="perf-dot" style="background:#198754;"></div>
                        <div style="font-size:.78rem;flex:1;">Tepat Waktu</div>
                        <div class="perf-badge text-success" id="s-on-time">{{ $summary['resolved_on_time'] }}</div>
                    </div>
                    <div class="perf-row">
                        <div class="perf-dot" style="background:#dc3545;"></div>
                        <div style="font-size:.78rem;flex:1;">Terlambat</div>
                        <div class="perf-badge text-danger" id="s-late">{{ $summary['resolved_late'] }}</div>
                    </div>
                    <div class="perf-row mt-2">
                        <i class="fas fa-clock text-muted mr-1" style="font-size:.8rem;"></i>
                        <div style="font-size:.78rem;flex:1;">Rata-rata selesai</div>
                        <div style="font-size:.82rem;font-weight:700;color:#0f766e;" id="s-avg-hours">
                            @if($summary['avg_hours'] !== null)
                                {{ $summary['avg_hours'] }} jam
                            @else
                                —
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Priority Breakdown --}}
    <div class="col-md-4 mb-3">
        <div class="perf-card h-100">
            <div class="perf-title"><i class="fas fa-flag mr-1"></i> Breakdown Prioritas</div>
            @php
                $totalForPct = max($summary['total'], 1);
            @endphp
            <div class="perf-row">
                <div class="perf-dot" style="background:#dc3545;"></div>
                <div style="font-size:.82rem;font-weight:600;min-width:78px;">Mendesak</div>
                <div class="perf-bar-wrap">
                    <div class="perf-bar" id="bar-mendesak"
                         style="width:{{ round($summary['priority_mendesak']/$totalForPct*100) }}%;background:#dc3545;"></div>
                </div>
                <div class="perf-badge text-danger" id="s-mendesak">{{ $summary['priority_mendesak'] }}</div>
            </div>
            <div class="perf-row">
                <div class="perf-dot" style="background:#fd7e14;"></div>
                <div style="font-size:.82rem;font-weight:600;min-width:78px;">Penting</div>
                <div class="perf-bar-wrap">
                    <div class="perf-bar" id="bar-penting"
                         style="width:{{ round($summary['priority_penting']/$totalForPct*100) }}%;background:#fd7e14;"></div>
                </div>
                <div class="perf-badge" style="color:#fd7e14;" id="s-penting">{{ $summary['priority_penting'] }}</div>
            </div>
            <div class="perf-row">
                <div class="perf-dot" style="background:#0d6efd;"></div>
                <div style="font-size:.82rem;font-weight:600;min-width:78px;">Normal</div>
                <div class="perf-bar-wrap">
                    <div class="perf-bar" id="bar-normal"
                         style="width:{{ round($summary['priority_normal']/$totalForPct*100) }}%;background:#0d6efd;"></div>
                </div>
                <div class="perf-badge text-primary" id="s-normal">{{ $summary['priority_normal'] }}</div>
            </div>
        </div>
    </div>

    {{-- Layanan Terbanyak --}}
    <div class="col-md-4 mb-3">
        <div class="perf-card h-100">
            <div class="perf-title"><i class="fas fa-layer-group mr-1"></i> Layanan Terbanyak</div>
            <div id="service-stats-wrap">
                @forelse($serviceStats as $svc)
                <div class="perf-row">
                    <div style="font-size:.8rem;flex:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $svc['label'] }}
                    </div>
                    <span class="badge badge-pill" style="background:#e8f5f3;color:#0f766e;font-size:.78rem;font-weight:700;">
                        {{ $svc['total'] }}
                    </span>
                </div>
                @empty
                <div class="text-muted text-center py-3" style="font-size:.82rem;">Belum ada data periode ini.</div>
                @endforelse
            </div>
        </div>
    </div>

</div>

{{-- ── ROW 3: Charts ── --}}
<div class="row mb-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title" style="font-size:.9rem;font-weight:700;">
                    <i class="fas fa-chart-line text-success mr-2"></i> Tren Laporan Human Resources
                    <span class="period-badge" id="chart-period-label">
                        {{ $dateFrom->format('d M') }} – {{ $dateTo->format('d M Y') }}
                    </span>
                </h3>
            </div>
            <div class="card-body" style="height:240px;position:relative;">
                <canvas id="hrTrendChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title" style="font-size:.9rem;font-weight:700;">
                    <i class="fas fa-chart-pie text-success mr-2"></i> Komposisi Penyelesaian
                </h3>
            </div>
            <div class="card-body" style="height:240px;position:relative;">
                <canvas id="hrResolutionChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ── Overdue SLA (always current, not date-filtered) ── --}}
@if($outstanding->count())
<div class="card mb-3" style="border: 2px solid #dc3545 !important;">
    <div class="card-header" style="background:#fff5f5;">
        <h3 class="card-title text-danger" style="font-size:.9rem;font-weight:700;">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            SLA Terlampaui — Perlu Tindakan Segera
        </h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr>
                        <th>Tiket</th><th>Karyawan</th><th>Layanan</th>
                        <th>Prioritas</th><th>Status</th><th>Deadline</th><th>Terlambat</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($outstanding as $item)
                    <tr class="overdue">
                        <td><a class="ticket-link" href="{{ route('hr.requests.show', $item) }}">{{ $item->ticket_number }}</a></td>
                        <td>{{ $item->employee_name }}</td>
                        <td>{{ $item->service_type }}</td>
                        <td><span class="badge {{ $item->priorityBadgeClass() }}">{{ $item->priorityLabel() }}</span></td>
                        <td><span class="badge {{ $item->statusBadgeClass() }}">{{ $item->statusLabel() }}</span></td>
                        <td class="text-danger font-weight-bold">{{ $item->sla_deadline?->format('d M Y H:i') }}</td>
                        <td class="text-danger">{{ abs(round($item->sla_deadline?->diffInHours(now()), 1)) }} jam</td>
                        <td><a href="{{ route('hr.requests.show', $item) }}" class="btn btn-xs btn-outline-danger">Tindak</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

{{-- ── Laporan Terbaru ── --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h3 class="card-title mb-0" style="font-size:.9rem;font-weight:700;">
            <i class="fas fa-clock text-success mr-2"></i>
            Laporan Terbaru
            <span class="period-badge" id="recent-period-label">
                {{ $dateFrom->format('d M') }} – {{ $dateTo->format('d M Y') }}
            </span>
        </h3>
        <a href="{{ route('hr.requests.index') }}" class="btn btn-xs btn-outline-success">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr><th>Tiket</th><th>Karyawan</th><th>Layanan</th><th>Prioritas</th><th>Status</th><th></th></tr>
                </thead>
                <tbody id="recent-tbody">
                    @forelse($recent as $item)
                    <tr>
                        <td><a class="ticket-link" href="{{ $item['url'] }}">{{ $item['ticket_number'] }}</a></td>
                        <td>{{ $item['employee_name'] }}</td>
                        <td>{{ $item['service_type'] }}</td>
                        <td><span class="badge {{ $item['priority_class'] }}">{{ $item['priority_label'] }}</span></td>
                        <td><span class="badge {{ $item['status_class'] }}">{{ $item['status_label'] }}</span></td>
                        <td><a href="{{ $item['url'] }}" class="btn btn-xs btn-outline-success">Detail</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada laporan pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const STATS_URL   = '{{ route('api.hr-dashboard-stats') }}';
const CSRF_TOKEN  = document.querySelector('meta[name=csrf-token]').content;

// ── Flatpickr ──
flatpickr('#date_from', { dateFormat: 'Y-m-d', locale: { firstDayOfWeek: 1 } });
flatpickr('#date_to',   { dateFormat: 'Y-m-d', locale: { firstDayOfWeek: 1 } });

// ── SLA Ring (doughnut kecil) ──
const slaCtx  = document.getElementById('slaRingChart').getContext('2d');
const slaRate = {{ $summary['sla_rate'] ?? 0 }};
let slaRing = new Chart(slaCtx, {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [slaRate, Math.max(100 - slaRate, 0)],
            backgroundColor: ['#0f766e', '#e8f5f3'],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: false,
        cutout: '74%',
        plugins: { legend: { display: false }, tooltip: { enabled: false } },
        animation: { duration: 600 },
    }
});

// ── Trend Chart ──
const trendCtx = document.getElementById('hrTrendChart').getContext('2d');
let trendChart = new Chart(trendCtx, {
    type: 'line',
    data: @json($chartData),
    options: {
        responsive: true, maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

// ── Resolution Chart ──
const resCtx = document.getElementById('hrResolutionChart').getContext('2d');
let resChart = new Chart(resCtx, {
    type: 'doughnut',
    data: @json($resolutionChart),
    options: {
        responsive: true, maintainAspectRatio: false, cutout: '58%',
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 11, font: { size: 11 } } } }
    }
});

// ── Helpers ──
function el(id) { return document.getElementById(id); }

function setText(id, val) {
    const e = el(id);
    if (e) e.textContent = val;
}

function formatAvgHours(hours) {
    if (hours === null || hours === undefined) return '—';
    if (hours >= 24) return (hours / 24).toFixed(1) + ' hari';
    return hours + ' jam';
}

function updateSlaRing(rate) {
    slaRing.data.datasets[0].data = [rate ?? 0, Math.max(100 - (rate ?? 0), 0)];
    slaRing.update();
    el('s-sla-rate-txt').textContent = rate !== null ? rate + '%' : '—';
}

function updateBars(mendesak, penting, normal) {
    const total = Math.max(mendesak + penting + normal, 1);
    el('bar-mendesak').style.width = (mendesak / total * 100) + '%';
    el('bar-penting').style.width  = (penting  / total * 100) + '%';
    el('bar-normal').style.width   = (normal   / total * 100) + '%';
}

function updateServiceStats(stats) {
    const wrap = el('service-stats-wrap');
    if (!stats || stats.length === 0) {
        wrap.innerHTML = '<div class="text-muted text-center py-3" style="font-size:.82rem;">Belum ada data periode ini.</div>';
        return;
    }
    wrap.innerHTML = stats.map(s => `
        <div class="perf-row">
            <div style="font-size:.8rem;flex:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${s.label}</div>
            <span class="badge badge-pill" style="background:#e8f5f3;color:#0f766e;font-size:.78rem;font-weight:700;">${s.total}</span>
        </div>`).join('');
}

function updateRecent(items) {
    const tbody = el('recent-tbody');
    if (!items || items.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Belum ada laporan pada periode ini.</td></tr>';
        return;
    }
    tbody.innerHTML = items.map(r => `
        <tr>
            <td><a class="ticket-link" href="${r.url}">${r.ticket_number}</a></td>
            <td>${r.employee_name}</td>
            <td>${r.service_type}</td>
            <td><span class="badge ${r.priority_class}">${r.priority_label}</span></td>
            <td><span class="badge ${r.status_class}">${r.status_label}</span></td>
            <td><a href="${r.url}" class="btn btn-xs btn-outline-success">Detail</a></td>
        </tr>`).join('');
}

function updatePeriodLabels(from, to) {
    const fmt = d => {
        const dt = new Date(d);
        return dt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
    };
    const label = fmt(from) + ' – ' + fmt(to);
    document.querySelectorAll('.period-badge').forEach(e => e.textContent = label);
}

// ── Main load function ──
async function loadStats(dateFrom, dateTo) {
    const btn = el('btn-apply');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Memuat…';

    try {
        const params = new URLSearchParams({ date_from: dateFrom, date_to: dateTo, _ts: Date.now() });
        const res    = await fetch(`${STATS_URL}?${params}`, {
            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
        });
        if (!res.ok) throw new Error('Gagal memuat data.');
        const data = res.status === 204 ? {} : await res.json();

        const s = data.summary;

        // Status row
        setText('s-total',    s.total);
        setText('s-open',     s.open);
        setText('s-progress', s.progress);
        setText('s-closed',   s.closed);
        setText('s-rejected', s.rejected);
        setText('s-overdue',  s.overdue);

        // SLA Performance
        updateSlaRing(s.sla_rate);
        setText('s-on-time',  s.resolved_on_time);
        setText('s-late',     s.resolved_late);
        setText('s-avg-hours', formatAvgHours(s.avg_hours));

        // Priority bars
        setText('s-mendesak', s.priority_mendesak);
        setText('s-penting',  s.priority_penting);
        setText('s-normal',   s.priority_normal);
        updateBars(s.priority_mendesak, s.priority_penting, s.priority_normal);

        // Service stats
        updateServiceStats(data.serviceStats);

        // Charts
        trendChart.data = data.chartData;
        trendChart.update();
        resChart.data = data.resolutionChart;
        resChart.update();

        // Recent table
        updateRecent(data.recent);

        // Period labels
        updatePeriodLabels(dateFrom, dateTo);

    } catch (err) {
        console.error(err);
        alert('Gagal memuat data. Silakan coba lagi.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-filter mr-1"></i> Terapkan';
    }
}

// ── Event: Apply button ──
el('btn-apply').addEventListener('click', () => {
    const from = el('date_from').value;
    const to   = el('date_to').value;
    if (!from || !to) { alert('Pilih rentang tanggal terlebih dahulu.'); return; }
    if (from > to)    { alert('Tanggal awal tidak boleh lebih besar dari tanggal akhir.'); return; }
    loadStats(from, to);
});
</script>
@endpush
