@extends('layouts.app')
@section('title', 'Dashboard Human Resources')
@section('page_title', 'Dashboard Human Resources')
@section('breadcrumb', 'Human Resources')

@section('content')

<form method="GET" action="{{ route('hr.dashboard') }}" class="date-filter-bar">
    <i class="fas fa-calendar-alt text-success"></i>
    <label>Dari:</label>
    <input type="text" id="date_from" name="date_from" class="form-control form-control-sm flatpickr-input"
           value="{{ $dateFrom->format('Y-m-d') }}" readonly style="width:130px;">
    <label>Sampai:</label>
    <input type="text" id="date_to" name="date_to" class="form-control form-control-sm flatpickr-input"
           value="{{ $dateTo->format('Y-m-d') }}" readonly style="width:130px;">
    <button type="submit" class="btn btn-success btn-sm ml-1">
        <i class="fas fa-filter mr-1"></i> Terapkan
    </button>
    <a href="{{ route('hr.requests.index') }}" class="btn btn-outline-success btn-sm ml-auto">
        <i class="fas fa-list mr-1"></i> Semua Laporan Human Resources
    </a>
</form>

<div class="row mb-3">
    <div class="col-6 col-md-2">
        <div class="small-box bg-gradient-success text-white">
            <div class="inner"><h3>{{ $summary['total'] }}</h3><p>Total</p></div>
            <div class="icon"><i class="fas fa-users"></i></div>
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
        <div class="small-box bg-gradient-primary text-white">
            <div class="inner"><h3>{{ $summary['closed'] }}</h3><p>Selesai</p></div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="small-box text-white" style="background:linear-gradient(135deg,#c0392b,#922b21);">
            <div class="inner"><h3>{{ $summary['overdue'] }}</h3><p>Overdue SLA</p></div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="small-box text-white" style="background:linear-gradient(135deg,#0f766e,#115e59);">
            <div class="inner"><h3>{{ $summary['resolved_on_time'] }}</h3><p>Tepat SLA</p></div>
            <div class="icon"><i class="fas fa-clock"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title" style="font-size:.9rem;font-weight:700;">
                    <i class="fas fa-chart-line text-success mr-2"></i> Tren Laporan Human Resources
                </h3>
            </div>
            <div class="card-body" style="height:260px;">
                <canvas id="hrTrendChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title" style="font-size:.9rem;font-weight:700;">
                    <i class="fas fa-chart-pie text-success mr-2"></i> Penyelesaian
                </h3>
            </div>
            <div class="card-body" style="height:260px;">
                <canvas id="hrResolutionChart"></canvas>
            </div>
        </div>
    </div>
</div>

@if($outstanding->count())
<div class="card border-danger mb-3" style="border-width:2px!important;">
    <div class="card-header" style="background:#fff5f5;">
        <h3 class="card-title text-danger" style="font-size:.9rem;font-weight:700;">
            <i class="fas fa-exclamation-triangle mr-2"></i> SLA Human Resources Terlampaui
        </h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr>
                        <th>Tiket</th>
                        <th>Karyawan</th>
                        <th>Layanan</th>
                        <th>Status</th>
                        <th>Deadline</th>
                        <th>Terlambat</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($outstanding as $item)
                    <tr class="overdue">
                        <td><a class="ticket-link" href="{{ route('hr.requests.show', $item) }}">{{ $item->ticket_number }}</a></td>
                        <td>{{ $item->employee_name }}</td>
                        <td>{{ $item->service_type }}</td>
                        <td><span class="badge {{ $item->statusBadgeClass() }}">{{ $item->statusLabel() }}</span></td>
                        <td class="text-danger font-weight-bold">{{ $item->sla_deadline?->format('d M Y H:i') }}</td>
                        <td class="text-danger">{{ abs(round($item->sla_deadline?->diffInHours(now()), 1)) }} jam</td>
                        <td><a href="{{ route('hr.requests.show', $item) }}" class="btn btn-xs btn-outline-success">Tindak</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title" style="font-size:.9rem;font-weight:700;">
                    <i class="fas fa-layer-group text-success mr-2"></i> Layanan Terbanyak
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    @forelse($serviceStats as $service)
                    <tr>
                        <td style="padding:10px 16px;">{{ $service['label'] }}</td>
                        <td class="text-right font-weight-bold" style="padding:10px 16px;">{{ $service['total'] }}</td>
                    </tr>
                    @empty
                    <tr><td class="text-center text-muted py-4">Belum ada data layanan.</td></tr>
                    @endforelse
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title" style="font-size:.9rem;font-weight:700;">
                    <i class="fas fa-clock text-success mr-2"></i> Laporan Terbaru
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-hover mb-0">
                    <thead><tr><th>Tiket</th><th>Karyawan</th><th>Layanan</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        @forelse($recent as $item)
                        <tr>
                            <td><a class="ticket-link" href="{{ route('hr.requests.show', $item) }}">{{ $item->ticket_number }}</a></td>
                            <td>{{ $item->employee_name }}</td>
                            <td>{{ $item->service_type }}</td>
                            <td><span class="badge {{ $item->statusBadgeClass() }}">{{ $item->statusLabel() }}</span></td>
                            <td><a href="{{ route('hr.requests.show', $item) }}" class="btn btn-xs btn-outline-success">Detail</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada laporan Human Resources.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
flatpickr('#date_from', { dateFormat: 'Y-m-d', locale: { firstDayOfWeek: 1 } });
flatpickr('#date_to', { dateFormat: 'Y-m-d', locale: { firstDayOfWeek: 1 } });

new Chart(document.getElementById('hrTrendChart'), {
    type: 'line',
    data: @json($chartData),
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

new Chart(document.getElementById('hrResolutionChart'), {
    type: 'doughnut',
    data: @json($resolutionChart),
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '62%',
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
    }
});
</script>
@endpush
