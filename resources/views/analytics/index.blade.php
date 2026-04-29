@extends('layouts.app')
@section('title', 'Analitik')
@section('page_title', 'Analitik Laporan')
@section('breadcrumb', 'Analitik')

@push('styles')
<style>
    .analytics-card {
        border-radius: 18px !important;
        overflow: hidden;
        border: 1px solid rgba(15, 23, 42, 0.04) !important;
        box-shadow: 0 14px 32px rgba(15, 23, 42, 0.08) !important;
    }

    .analytics-card .card-header {
        padding: 1rem 1.1rem !important;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border-bottom: 1px solid rgba(13, 110, 253, 0.08) !important;
    }

    .analytics-card .card-title {
        display: flex;
        align-items: center;
        gap: .55rem;
        margin: 0;
        font-size: .92rem !important;
        font-weight: 700 !important;
        color: #18263f;
    }

    .analytics-card .card-body {
        padding: 1.1rem 1.1rem 1.2rem;
    }

    .analytics-panel {
        position: relative;
    }

    .analytics-panel::before {
        content: "";
        position: absolute;
        inset: 0 0 auto 0;
        height: 4px;
        background: linear-gradient(90deg, var(--accent-start), var(--accent-end));
        z-index: 2;
    }

    .analytics-panel-primary {
        --accent-start: #0d6efd;
        --accent-end: #7ab8ff;
    }

    .analytics-panel-info {
        --accent-start: #0dcaf0;
        --accent-end: #7ee5ff;
    }

    .analytics-panel-warning {
        --accent-start: #f59f00;
        --accent-end: #ffd166;
    }

    .analytics-panel-success {
        --accent-start: #198754;
        --accent-end: #68d391;
    }

    .analytics-panel-danger {
        --accent-start: #dc3545;
        --accent-end: #ff8a8a;
    }

    .analytics-legend {
        display: flex;
        flex-wrap: wrap;
        gap: .6rem;
        justify-content: center;
        margin-top: 1rem;
        font-size: .78rem;
        color: #526072;
    }

    .analytics-legend-item {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .35rem .65rem;
        border-radius: 999px;
        background: #f4f7fb;
    }

    .analytics-legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }

    .analytics-sla-summary {
        text-align: center;
        margin-bottom: .75rem;
        padding: .85rem;
        border-radius: 14px;
        background: linear-gradient(180deg, #f8fbff 0%, #f3f7fc 100%);
    }

    .analytics-sla-rate {
        font-size: 2.1rem;
        line-height: 1;
        font-weight: 800;
        margin-bottom: .25rem;
    }

    .analytics-sla-card .card-body {
        padding-top: .95rem;
        padding-bottom: 1rem;
    }

    .analytics-sla-chart-wrap {
        max-width: 180px;
        margin: 0 auto;
    }

    .analytics-table-card .card-body {
        padding: 0;
    }

    .analytics-table-card .table thead th {
        background: #f8fbff;
        border-top: none;
        padding-top: .9rem;
        padding-bottom: .9rem;
    }

    .analytics-table-card .table tbody td {
        padding-top: .8rem;
        padding-bottom: .8rem;
    }

    @media (max-width: 768px) {
        .analytics-card .card-header,
        .analytics-card .card-body {
            padding-left: .9rem !important;
            padding-right: .9rem !important;
        }

        .analytics-sla-rate {
            font-size: 1.9rem;
        }
    }
</style>
@endpush

@section('content')

{{-- Date Filter --}}
<form method="GET" action="{{ route('analytics.index') }}">
    <div class="date-filter-bar mb-4">
        <i class="fas fa-calendar-alt text-primary"></i>
        <label class="mb-0">Dari:</label>
        <input type="text" id="af_from" name="date_from" class="form-control form-control-sm flatpickr-input"
               value="{{ $dateFrom->format('Y-m-d') }}" style="width:130px;" readonly>
        <label class="mb-0">Sampai:</label>
        <input type="text" id="af_to"   name="date_to"   class="form-control form-control-sm flatpickr-input"
               value="{{ $dateTo->format('Y-m-d') }}"   style="width:130px;" readonly>
        <div class="btn-group btn-group-sm ml-2">
            <a href="{{ route('analytics.index', ['date_from'=>now()->startOfMonth()->toDateString(),'date_to'=>now()->toDateString()]) }}"
               class="btn btn-outline-secondary btn-sm">Bulan Ini</a>
            <a href="{{ route('analytics.index', ['date_from'=>now()->subDays(29)->toDateString(),'date_to'=>now()->toDateString()]) }}"
               class="btn btn-outline-secondary btn-sm">30 Hari</a>
            <a href="{{ route('analytics.index', ['date_from'=>now()->startOfYear()->toDateString(),'date_to'=>now()->toDateString()]) }}"
               class="btn btn-outline-secondary btn-sm">Tahun Ini</a>
        </div>
        <button type="submit" class="btn btn-primary btn-sm ml-1">
            <i class="fas fa-filter mr-1"></i> Terapkan
        </button>
    </div>
</form>

{{-- ── Row 1: Summary KPIs ── --}}
<div class="row mb-4">
    @php $total = array_sum($byType); @endphp
    <div class="col-6 col-md-2 mb-2">
        <div class="small-box bg-primary text-white" style="border-radius:14px;">
            <div class="inner"><h3>{{ $total }}</h3><p>Total Laporan</p></div>
            <div class="icon"><i class="fas fa-clipboard-list"></i></div>
        </div>
    </div>
    <div class="col-6 col-md-2 mb-2">
        <div class="small-box bg-danger text-white" style="border-radius:14px;">
            <div class="inner"><h3>{{ $byStatus['open'] }}</h3><p>Open</p></div>
            <div class="icon"><i class="fas fa-inbox"></i></div>
        </div>
    </div>
    <div class="col-6 col-md-2 mb-2">
        <div class="small-box bg-warning" style="border-radius:14px;">
            <div class="inner"><h3>{{ $byStatus['progress'] }}</h3><p>Progress</p></div>
            <div class="icon"><i class="fas fa-spinner"></i></div>
        </div>
    </div>
    <div class="col-6 col-md-2 mb-2">
        <div class="small-box bg-success text-white" style="border-radius:14px;">
            <div class="inner"><h3>{{ $byStatus['closed'] }}</h3><p>Closed</p></div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
        </div>
    </div>
    <div class="col-6 col-md-2 mb-2">
        <div class="small-box text-white" style="background:linear-gradient(135deg,#6f42c1,#5a32a3);border-radius:14px;">
            <div class="inner"><h3>{{ $slaRate }}%</h3><p>SLA On-Time</p></div>
            <div class="icon"><i class="fas fa-stopwatch"></i></div>
        </div>
    </div>
    <div class="col-6 col-md-2 mb-2">
        <div class="small-box text-white" style="background:linear-gradient(135deg,#c0392b,#922b21);border-radius:14px;">
            <div class="inner"><h3>{{ $overdue }}</h3><p>Overdue SLA</p></div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>
</div>

{{-- ── Row 2: Charts ── --}}
<div class="row mb-4">
    {{-- Tren --}}
    <div class="col-md-8 mb-3">
        <div class="card analytics-card analytics-panel analytics-panel-primary analytics-sla-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line text-primary"></i> Tren Laporan Masuk
                </h3>
            </div>
            <div class="card-body">
                <div style="position:relative;height:220px;">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    {{-- Distribusi tipe --}}
    <div class="col-md-4 mb-3">
        <div class="card analytics-card analytics-panel analytics-panel-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie text-info"></i> Distribusi per Tipe
                </h3>
            </div>
            <div class="card-body d-flex flex-column align-items-center">
                <canvas id="typeChart" style="max-height:180px;"></canvas>
                <div class="analytics-legend">
                    @php $typeColors = ['receptionist'=>'#0d6efd','hk'=>'#198754','laundry'=>'#ffc107']; @endphp
                    @foreach($byType as $t => $cnt)
                    <span class="analytics-legend-item"><span class="analytics-legend-dot" style="background:{{ $typeColors[$t]??'#aaa' }};"></span>
                    {{ ['receptionist'=>'Receptionist','hk'=>'HK','laundry'=>'Laundry'][$t]??$t }}
                    ({{ $cnt }})</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    {{-- Hari dalam seminggu --}}
    <div class="col-md-6 mb-3">
        <div class="card analytics-card analytics-panel analytics-panel-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-calendar-week text-warning"></i> Distribusi Hari dalam Seminggu
                </h3>
            </div>
            <div class="card-body"><div style="position:relative;height:180px;"><canvas id="dowChart"></canvas></div></div>
        </div>
    </div>
    {{-- Per jam --}}
    <div class="col-md-6 mb-3">
        <div class="card analytics-card analytics-panel analytics-panel-success">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clock text-success"></i> Jam Paling Banyak Laporan
                </h3>
            </div>
            <div class="card-body"><div style="position:relative;height:180px;"><canvas id="hourChart"></canvas></div></div>
        </div>
    </div>
</div>

{{-- ── Row 3: Top Buildings ── --}}
<div class="row mb-4">
    <div class="col-md-7 mb-3">
        <div class="card analytics-card analytics-panel analytics-panel-danger">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-building text-danger"></i> Top Bangunan dengan Keluhan Terbanyak
                </h3>
            </div>
            <div class="card-body"><div style="position:relative;height:220px;"><canvas id="buildingChart"></canvas></div></div>
        </div>
    </div>
    <div class="col-md-5 mb-3">
        <div class="card analytics-card analytics-panel analytics-panel-primary analytics-sla-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-shield-alt text-primary"></i> SLA Compliance
                </h3>
            </div>
            <div class="card-body">
                <div class="analytics-sla-summary">
                    <div class="analytics-sla-rate" style="color:{{ $slaRate >= 80 ? '#198754' : ($slaRate >= 60 ? '#fd7e14' : '#dc3545') }};">
                        {{ $slaRate }}%
                    </div>
                    <div style="font-size:.82rem;color:#6c757d;">Tepat Waktu</div>
                </div>
                <div class="analytics-sla-chart-wrap">
                    <canvas id="slaChart" height="96"></canvas>
                </div>
                <div class="analytics-legend">
                    <span class="analytics-legend-item"><span class="analytics-legend-dot" style="background:#198754;"></span>Tepat Waktu ({{ $onTime }})</span>
                    <span class="analytics-legend-item"><span class="analytics-legend-dot" style="background:#dc3545;"></span>Terlambat ({{ $lateClose }})</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Building detail table ── --}}
<div class="card analytics-card analytics-panel analytics-panel-info analytics-table-card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h3 class="card-title mb-0">
            <i class="fas fa-table text-secondary"></i> Detail Keluhan per Bangunan
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr>
                        <th>Bangunan</th>
                        <th>Tipe</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Open</th>
                        <th class="text-center">Progress</th>
                        <th class="text-center">Closed</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buildingDetail as $building => $rows)
                        @foreach($rows as $i => $row)
                        <tr>
                            @if($i === 0)
                            <td rowspan="{{ $rows->count() }}" style="font-weight:700;border-right:1px solid #dee2e6;vertical-align:middle;">
                                <i class="fas fa-building text-muted mr-1" style="font-size:.75rem;"></i>
                                {{ $building }}
                            </td>
                            @endif
                            <td>
                                <span class="badge {{ ['receptionist'=>'type-rec','hk'=>'type-hk','laundry'=>'type-ldy'][$row->type]??'' }}" style="font-size:.72rem;">
                                    {{ ['receptionist'=>'Receptionist','hk'=>'HK','laundry'=>'Laundry'][$row->type]??$row->type }}
                                </span>
                            </td>
                            <td class="text-center font-weight-bold">{{ $row->cnt }}</td>
                            <td class="text-center text-danger">{{ $row->open_cnt }}</td>
                            <td class="text-center text-warning">{{ $row->prog_cnt }}</td>
                            <td class="text-center text-success">{{ $row->closed_cnt }}</td>
                        </tr>
                        @endforeach
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── Feedback / Rating Section ── --}}
<div class="card analytics-card analytics-panel analytics-panel-warning mt-3">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-star text-warning"></i> Penilaian & Feedback Pengguna</h3>
    </div>
    <div class="card-body">
        <div class="row">
            {{-- Summary --}}
            <div class="col-md-3 mb-3">
                <div style="text-align:center;padding:16px 10px;">
                    @if($avgRating !== null)
                    <div style="font-size:3rem;font-weight:900;line-height:1;color:#f59f00;">{{ number_format($avgRating,1) }}</div>
                    <div style="font-size:1.3rem;color:#ffc107;margin:4px 0;">
                        @for($i=1;$i<=5;$i++)
                            <span>{{ $i <= round($avgRating) ? '★' : '☆' }}</span>
                        @endfor
                    </div>
                    @else
                    <div style="font-size:2rem;font-weight:700;color:#adb5bd;">—</div>
                    @endif
                    <div style="font-size:.72rem;text-transform:uppercase;font-weight:700;color:#6c757d;letter-spacing:.05em;margin-top:6px;">Rata-rata Rating</div>
                </div>
                <div class="row text-center" style="font-size:.82rem;">
                    <div class="col-6">
                        <div style="font-size:1.3rem;font-weight:800;">{{ $ratedCount }}</div>
                        <div style="font-size:.7rem;color:#6c757d;text-transform:uppercase;">Dinilai</div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:1.3rem;font-weight:800;">{{ $participationRate }}%</div>
                        <div style="font-size:.7rem;color:#6c757d;text-transform:uppercase;">Partisipasi</div>
                    </div>
                </div>
                @if($autoRated > 0)
                <div style="text-align:center;margin-top:8px;">
                    <span style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;border-radius:999px;font-size:.72rem;padding:2px 10px;">
                        <i class="fas fa-robot mr-1"></i>{{ $autoRated }} auto-rated
                    </span>
                </div>
                @endif
            </div>

            {{-- Distribution --}}
            <div class="col-md-3 mb-3">
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;margin-bottom:10px;">Distribusi Bintang</div>
                @php $maxDist = max(1, max($ratingDist)); @endphp
                @foreach(array_reverse([1,2,3,4,5]) as $star)
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:5px;font-size:.82rem;">
                    <span style="min-width:20px;text-align:right;font-weight:600;">{{ $star }}</span>
                    <span style="color:#ffc107;font-size:.9rem;">★</span>
                    <div style="flex:1;background:#f0f4f8;border-radius:999px;height:6px;overflow:hidden;">
                        <div style="height:6px;border-radius:999px;background:#ffc107;width:{{ $ratingDist[$star] > 0 ? round($ratingDist[$star]/$maxDist*100) : 0 }}%;transition:width .4s;"></div>
                    </div>
                    <span style="min-width:24px;font-weight:700;color:#495057;">{{ $ratingDist[$star] }}</span>
                </div>
                @endforeach
            </div>

            {{-- Recent feedback with text --}}
            <div class="col-md-6 mb-3">
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;margin-bottom:10px;">Komentar Terbaru</div>
                @forelse($recentFeedback as $fb)
                <div style="background:#fffbeb;border-left:3px solid #ffc107;border-radius:6px;padding:8px 12px;margin-bottom:8px;font-size:.83rem;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                        <span style="font-weight:700;color:#18263f;">{{ $fb->reporter_name }}</span>
                        <span style="color:#ffc107;font-size:.9rem;">{{ str_repeat('★', $fb->rating) }}{{ str_repeat('☆', 5 - $fb->rating) }}</span>
                    </div>
                    <div style="color:#495057;line-height:1.5;">"{{ $fb->feedback_text }}"</div>
                    <div style="font-size:.72rem;color:#adb5bd;margin-top:4px;">{{ $fb->feedback_at?->format('d M Y H:i') }} — {{ $fb->typeLabel() }}</div>
                </div>
                @empty
                <div style="color:#adb5bd;font-size:.84rem;padding:16px 0;text-align:center;">Belum ada komentar dari pengguna.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
@php
    $trendDs = collect($trendDataset)->map(function ($d) {
        return [
            'label' => $d['label'],
            'data' => $d['data'],
            'borderColor' => $d['color'],
            'backgroundColor' => $d['color'] . '33',
            'borderWidth' => 2,
            'fill' => false,
            'tension' => 0.35,
        ];
    })->values();
@endphp
<script>
// Trend
const trendDs = @json($trendDs);

new Chart(document.getElementById('trendChart').getContext('2d'), {
    type: 'line',
    data: { labels: @json($trendLabels), datasets: trendDs },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position:'top', labels:{ boxWidth:12, font:{size:11} } } }, scales: { y:{ beginAtZero:true, ticks:{stepSize:1} } } }
});

// Type donut
new Chart(document.getElementById('typeChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: ['Receptionist','Housekeeping','Laundry'],
        datasets:[{ data:[ @json($byType['receptionist']??0), @json($byType['hk']??0), @json($byType['laundry']??0) ], backgroundColor:['#0d6efd','#198754','#ffc107'], borderWidth:2, borderColor:'#fff' }]
    },
    options: { responsive:true, cutout:'65%', plugins:{legend:{display:false}} }
});

// Day of week
new Chart(document.getElementById('dowChart').getContext('2d'), {
    type:'bar',
    data:{ labels: @json($dowLabels), datasets:[{ label:'Laporan', data: @json($dowData), backgroundColor:'rgba(13,110,253,.65)', borderColor:'#0d6efd', borderWidth:1 }] },
    options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true,ticks:{stepSize:1}}} }
});

// Hour chart
new Chart(document.getElementById('hourChart').getContext('2d'), {
    type:'bar',
    data:{ labels: Array.from({length:24},(_,i)=>i+'h'), datasets:[{ label:'Laporan', data: @json($hourData), backgroundColor:'rgba(25,135,84,.65)', borderColor:'#198754', borderWidth:1 }] },
    options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true,ticks:{stepSize:1}}} }
});

// Top buildings
const bLabels = @json($topBuildings->pluck('building'));
const bData   = @json($topBuildings->pluck('cnt'));
new Chart(document.getElementById('buildingChart').getContext('2d'), {
    type:'bar',
    data:{ labels:bLabels, datasets:[{ label:'Keluhan', data:bData, backgroundColor:'rgba(220,53,69,.65)', borderColor:'#dc3545', borderWidth:1 }] },
    options:{ indexAxis:'y', responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{x:{beginAtZero:true,ticks:{stepSize:1}}} }
});

// SLA donut
new Chart(document.getElementById('slaChart').getContext('2d'), {
    type:'doughnut',
    data:{ labels:['Tepat Waktu','Terlambat'], datasets:[{ data:[ {{ $onTime }}, {{ $lateClose }} ], backgroundColor:['#198754','#dc3545'], borderWidth:2, borderColor:'#fff' }] },
    options:{ responsive:true, maintainAspectRatio:true, radius:'82%', cutout:'68%', plugins:{legend:{display:false}} }
});

// Flatpickr
flatpickr('#af_from', { dateFormat:'Y-m-d' });
flatpickr('#af_to',   { dateFormat:'Y-m-d' });
</script>
@endpush
