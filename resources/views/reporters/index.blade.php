@extends('layouts.app')
@section('title', 'Analitik Pelapor')
@section('page_title', 'Analitik Pelapor')
@section('breadcrumb', 'Pelapor')

@section('content')

{{-- Filter bar --}}
<form method="GET" action="{{ route('reporters.index') }}">
    <div class="date-filter-bar mb-3">
        <i class="fas fa-users text-primary"></i>
        <div class="btn-group btn-group-sm">
            @foreach(['today'=>'Hari Ini','week'=>'Minggu Ini','month'=>'Bulan Ini','custom'=>'Custom'] as $key => $lbl)
            <a href="{{ route('reporters.index', ['preset'=>$key]) }}"
               class="btn btn-outline-secondary btn-sm {{ $preset===$key ? 'active' : '' }}">{{ $lbl }}</a>
            @endforeach
        </div>
        <div id="custom-dates" style="{{ $preset==='custom' ? '' : 'display:none' }};display:flex;gap:8px;align-items:center;">
            <input type="text" id="rep_from" name="date_from" class="form-control form-control-sm"
                   value="{{ $dateFrom->format('Y-m-d') }}" style="width:120px;" readonly>
            <span>—</span>
            <input type="text" id="rep_to" name="date_to" class="form-control form-control-sm"
                   value="{{ $dateTo->format('Y-m-d') }}" style="width:120px;" readonly>
            <button type="submit" class="btn btn-primary btn-sm">Terapkan</button>
        </div>
        <span class="ml-auto" style="font-size:.78rem;color:#aaa;">
            {{ $dateFrom->format('d M') }} — {{ $dateTo->format('d M Y') }}
        </span>
    </div>
</form>

{{-- Summary --}}
<div class="row mb-3">
    <div class="col-md-4 mb-2">
        <div class="small-box bg-primary text-white" style="border-radius:14px;">
            <div class="inner"><h3>{{ $totalComplaints }}</h3><p>Total Laporan</p></div>
            <div class="icon"><i class="fas fa-clipboard-list"></i></div>
        </div>
    </div>
    <div class="col-md-4 mb-2">
        <div class="small-box bg-info text-white" style="border-radius:14px;">
            <div class="inner"><h3>{{ $uniqueReporters }}</h3><p>Pelapor Unik</p></div>
            <div class="icon"><i class="fas fa-users"></i></div>
        </div>
    </div>
    <div class="col-md-4 mb-2">
        <div class="small-box bg-warning" style="border-radius:14px;">
            <div class="inner">
                <h3>{{ $topReporter['total'] ?? 0 }}</h3>
                <p>Laporan Terbanyak<br><small>{{ $topReporter['name'] ?? '—' }}</small></p>
            </div>
            <div class="icon"><i class="fas fa-medal"></i></div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Tabel pelapor --}}
    <div class="{{ $detailPhone ? 'col-md-7' : 'col-12' }}">
        <div class="card">
            <div class="card-header py-2">
                <h3 class="card-title mb-0" style="font-size:.88rem;font-weight:700;">
                    <i class="fas fa-list-ol text-primary mr-2"></i>
                    Peringkat Pelapor — Laporan Terbanyak
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>No. Telpon</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Open</th>
                                <th class="text-center">Progress</th>
                                <th class="text-center">Closed</th>
                                @if(count($userTypes)>1)<th>Tipe</th>@endif
                                <th>Bangunan</th>
                                <th>Terakhir</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reporters as $i => $r)
                            <tr class="{{ $r['phone']===$detailPhone ? 'table-primary' : '' }}">
                                <td style="font-weight:700;color:#aaa;font-size:.82rem;">{{ $i+1 }}</td>
                                <td>
                                    <div style="font-weight:600;font-size:.85rem;">{{ $r['name'] }}</div>
                                </td>
                                <td style="font-size:.85rem;font-family:monospace;">{{ $r['phone'] }}</td>
                                <td class="text-center">
                                    <span style="font-weight:800;font-size:.95rem;color:#0d6efd;">{{ $r['total'] }}</span>
                                </td>
                                <td class="text-center text-danger">{{ $r['open'] }}</td>
                                <td class="text-center text-warning">{{ $r['progress'] }}</td>
                                <td class="text-center text-success">{{ $r['closed'] }}</td>
                                @if(count($userTypes)>1)
                                <td style="font-size:.75rem;">
                                    @if($r['receptionist']) <span class="badge type-rec">R:{{ $r['receptionist'] }}</span> @endif
                                    @if($r['hk'])           <span class="badge type-hk">H:{{ $r['hk'] }}</span>           @endif
                                    @if($r['laundry'])      <span class="badge type-ldy">L:{{ $r['laundry'] }}</span>      @endif
                                </td>
                                @endif
                                <td style="font-size:.75rem;max-width:110px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $r['buildings'] }}">
                                    {{ $r['buildings'] ?: '—' }}
                                </td>
                                <td style="font-size:.75rem;color:#aaa;white-space:nowrap;">
                                    {{ $r['last_report'] ? $r['last_report']->diffForHumans() : '—' }}
                                </td>
                                <td>
                                    @if($r['phone'] !== '—')
                                    <a href="{{ route('reporters.index', array_merge(request()->query(), ['phone'=>$r['phone']])) }}"
                                       class="btn btn-xs btn-outline-primary">
                                       <i class="fas fa-eye"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="11" class="text-center text-muted py-5">Tidak ada data pelapor.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail complaints from selected reporter --}}
    @if($detailPhone && $detailComplaints)
    <div class="col-md-5">
        <div class="card">
            <div class="card-header py-2 d-flex align-items-center justify-content-between">
                <h3 class="card-title mb-0" style="font-size:.85rem;font-weight:700;">
                    <i class="fas fa-phone text-primary mr-1"></i> {{ $detailPhone }}
                </h3>
                <a href="{{ route('reporters.index', array_diff_key(request()->query(), ['phone'=>''])) }}"
                   class="btn btn-xs btn-outline-secondary">✕</a>
            </div>
            <div class="card-body p-0" style="max-height:500px;overflow-y:auto;">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr><th>Tiket</th><th>Tipe</th><th>Bangunan</th><th>Status</th><th>Tgl</th></tr>
                        </thead>
                        <tbody>
                            @foreach($detailComplaints as $c)
                            <tr>
                                <td><a href="{{ route('complaints.show',$c) }}" class="ticket-link" style="font-size:.8rem;">{{ $c->ticket_number }}</a></td>
                                <td><span class="badge {{ $c->type==='receptionist'?'type-rec':($c->type==='hk'?'type-hk':'type-ldy') }}" style="font-size:.7rem;">{{ $c->typeLabel() }}</span></td>
                                <td style="font-size:.78rem;">{{ $c->building ?? '—' }}</td>
                                <td><span class="badge {{ $c->statusBadgeClass() }}" style="font-size:.7rem;">{{ $c->statusLabel() }}</span></td>
                                <td style="font-size:.75rem;color:#aaa;white-space:nowrap;">{{ $c->created_at->format('d M') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
flatpickr('#rep_from', { dateFormat:'Y-m-d' });
flatpickr('#rep_to',   { dateFormat:'Y-m-d' });
document.querySelectorAll('[href*="preset=custom"]').forEach(a => {
    a.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('custom-dates').style.display='flex';
    });
});
</script>
@endpush
