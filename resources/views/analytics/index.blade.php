@extends('layouts.app')
@section('title', 'Analitik')
@section('page_title', 'Analitik Laporan')
@section('breadcrumb', 'Analitik')

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
        <div class="card h-100">
            <div class="card-header py-2">
                <h3 class="card-title" style="font-size:.88rem;font-weight:700;">
                    <i class="fas fa-chart-line text-primary mr-2"></i> Tren Laporan Masuk
                </h3>
            </div>
            <div class="card-body">
                <canvas id="trendChart" height="100"></canvas>
            </div>
        </div>
    </div>
    {{-- Distribusi tipe --}}
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-header py-2">
                <h3 class="card-title" style="font-size:.88rem;font-weight:700;">
                    <i class="fas fa-chart-pie text-info mr-2"></i> Distribusi per Tipe
                </h3>
            </div>
            <div class="card-body d-flex flex-column align-items-center">
                <canvas id="typeChart" style="max-height:180px;"></canvas>
                <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:14px;justify-content:center;font-size:.78rem;">
                    @php $typeColors = ['receptionist'=>'#0d6efd','hk'=>'#198754','laundry'=>'#ffc107']; @endphp
                    @foreach($byType as $t => $cnt)
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:{{ $typeColors[$t]??'#aaa' }};margin-right:4px;"></span>
                    {{ ['receptionist'=>'Resepsionis','hk'=>'HK','laundry'=>'Laundry'][$t]??$t }}
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
        <div class="card">
            <div class="card-header py-2">
                <h3 class="card-title" style="font-size:.88rem;font-weight:700;">
                    <i class="fas fa-calendar-week text-warning mr-2"></i> Distribusi Hari dalam Seminggu
                </h3>
            </div>
            <div class="card-body"><canvas id="dowChart" height="120"></canvas></div>
        </div>
    </div>
    {{-- Per jam --}}
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header py-2">
                <h3 class="card-title" style="font-size:.88rem;font-weight:700;">
                    <i class="fas fa-clock text-success mr-2"></i> Jam Paling Banyak Laporan
                </h3>
            </div>
            <div class="card-body"><canvas id="hourChart" height="120"></canvas></div>
        </div>
    </div>
</div>

{{-- ── Row 3: Top Buildings ── --}}
<div class="row mb-4">
    <div class="col-md-7 mb-3">
        <div class="card">
            <div class="card-header py-2">
                <h3 class="card-title" style="font-size:.88rem;font-weight:700;">
                    <i class="fas fa-building text-danger mr-2"></i> Top Bangunan dengan Keluhan Terbanyak
                </h3>
            </div>
            <div class="card-body"><canvas id="buildingChart" height="160"></canvas></div>
        </div>
    </div>
    <div class="col-md-5 mb-3">
        <div class="card">
            <div class="card-header py-2">
                <h3 class="card-title" style="font-size:.88rem;font-weight:700;">
                    <i class="fas fa-shield-alt text-primary mr-2"></i> SLA Compliance
                </h3>
            </div>
            <div class="card-body">
                <div style="text-align:center;margin-bottom:16px;">
                    <div style="font-size:2.5rem;font-weight:800;color:{{ $slaRate >= 80 ? '#198754' : ($slaRate >= 60 ? '#fd7e14' : '#dc3545') }};">
                        {{ $slaRate }}%
                    </div>
                    <div style="font-size:.82rem;color:#6c757d;">Tepat Waktu</div>
                </div>
                <canvas id="slaChart" height="140"></canvas>
                <div style="display:flex;justify-content:center;gap:16px;margin-top:10px;font-size:.78rem;">
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#198754;margin-right:4px;"></span>Tepat Waktu ({{ $onTime }})</span>
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#dc3545;margin-right:4px;"></span>Terlambat ({{ $lateClose }})</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Building detail table ── --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between py-2">
        <h3 class="card-title mb-0" style="font-size:.88rem;font-weight:700;">
            <i class="fas fa-table text-secondary mr-2"></i> Detail Keluhan per Bangunan
        </h3>
    </div>
    <div class="card-body p-0">
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
                                    {{ ['receptionist'=>'Resepsionis','hk'=>'HK','laundry'=>'Laundry'][$row->type]??$row->type }}
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
    options: { responsive: true, plugins: { legend: { position:'top', labels:{ boxWidth:12, font:{size:11} } } }, scales: { y:{ beginAtZero:true, ticks:{stepSize:1} } } }
});

// Type donut
new Chart(document.getElementById('typeChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: ['Resepsionis','Housekeeping','Laundry'],
        datasets:[{ data:[ @json($byType['receptionist']??0), @json($byType['hk']??0), @json($byType['laundry']??0) ], backgroundColor:['#0d6efd','#198754','#ffc107'], borderWidth:2, borderColor:'#fff' }]
    },
    options: { responsive:true, cutout:'65%', plugins:{legend:{display:false}} }
});

// Day of week
new Chart(document.getElementById('dowChart').getContext('2d'), {
    type:'bar',
    data:{ labels: @json($dowLabels), datasets:[{ label:'Laporan', data: @json($dowData), backgroundColor:'rgba(13,110,253,.65)', borderColor:'#0d6efd', borderWidth:1 }] },
    options:{ responsive:true, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true,ticks:{stepSize:1}}} }
});

// Hour chart
new Chart(document.getElementById('hourChart').getContext('2d'), {
    type:'bar',
    data:{ labels: Array.from({length:24},(_,i)=>i+'h'), datasets:[{ label:'Laporan', data: @json($hourData), backgroundColor:'rgba(25,135,84,.65)', borderColor:'#198754', borderWidth:1 }] },
    options:{ responsive:true, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true,ticks:{stepSize:1}}} }
});

// Top buildings
const bLabels = @json($topBuildings->pluck('building'));
const bData   = @json($topBuildings->pluck('cnt'));
new Chart(document.getElementById('buildingChart').getContext('2d'), {
    type:'bar',
    data:{ labels:bLabels, datasets:[{ label:'Keluhan', data:bData, backgroundColor:'rgba(220,53,69,.65)', borderColor:'#dc3545', borderWidth:1 }] },
    options:{ indexAxis:'y', responsive:true, plugins:{legend:{display:false}}, scales:{x:{beginAtZero:true,ticks:{stepSize:1}}} }
});

// SLA donut
new Chart(document.getElementById('slaChart').getContext('2d'), {
    type:'doughnut',
    data:{ labels:['Tepat Waktu','Terlambat'], datasets:[{ data:[ {{ $onTime }}, {{ $lateClose }} ], backgroundColor:['#198754','#dc3545'], borderWidth:2, borderColor:'#fff' }] },
    options:{ responsive:true, cutout:'65%', plugins:{legend:{display:false}} }
});

// Flatpickr
flatpickr('#af_from', { dateFormat:'Y-m-d' });
flatpickr('#af_to',   { dateFormat:'Y-m-d' });
</script>
@endpush
