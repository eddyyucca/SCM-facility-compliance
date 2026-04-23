@extends('layouts.app')
@section('title', 'Laporan Human Resources')
@section('page_title', 'Laporan Human Resources')
@section('breadcrumb', 'Laporan Human Resources')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title" style="font-size:.9rem;font-weight:700;">
            <i class="fas fa-users text-success mr-2"></i> Daftar Laporan Human Resources
        </h3>
        <div class="card-tools">
            <a href="{{ route('hr.dashboard') }}" class="btn btn-tool text-success">
                <i class="fas fa-chart-line mr-1"></i> Dashboard
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('hr.requests.index') }}" class="mb-3">
            <div class="row align-items-end">
                <div class="col-md-3 mb-2">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search"></i></span></div>
                        <input type="text" name="search" class="form-control" placeholder="Cari tiket / karyawan..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2 mb-2">
                    <select name="status" class="form-control form-control-sm">
                        <option value="">Semua Status</option>
                        <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="progress" {{ request('status') === 'progress' ? 'selected' : '' }}>Progress</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select name="service_type" class="form-control form-control-sm">
                        <option value="">Semua Layanan</option>
                        @foreach($serviceTypes as $serviceType)
                        <option value="{{ $serviceType }}" {{ request('service_type') === $serviceType ? 'selected' : '' }}>{{ $serviceType }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <div class="form-check" style="padding-left:1.4rem;">
                        <input type="checkbox" class="form-check-input" id="hr-overdue" name="overdue" value="1" {{ request()->boolean('overdue') ? 'checked' : '' }}>
                        <label for="hr-overdue" class="form-check-label font-weight-bold text-danger" style="font-size:.84rem;">Overdue SLA</label>
                    </div>
                </div>
                <div class="col-auto mb-2">
                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-filter mr-1"></i> Filter</button>
                    <a href="{{ route('hr.requests.index') }}" class="btn btn-secondary btn-sm ml-1">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-sm" style="font-size:.85rem;">
                <thead>
                    <tr>
                        <th>Tiket</th>
                        <th>Karyawan</th>
                        <th>Perusahaan / Departemen</th>
                        <th>Layanan</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>SLA</th>
                        <th>Tanggal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hrRequests as $item)
                    <tr class="{{ $item->isOverdue() ? 'overdue' : '' }}">
                        <td><a href="{{ route('hr.requests.show', $item) }}" class="ticket-link">{{ $item->ticket_number }}</a></td>
                        <td>
                            <div class="font-weight-bold">{{ $item->employee_name }}</div>
                            <small class="text-muted">{{ $item->employee_id ?: $item->phone }}</small>
                        </td>
                        <td>
                            <div>{{ $item->company_name }}</div>
                            <small class="text-muted">{{ $item->department }}</small>
                        </td>
                        <td>{{ $item->service_type }}</td>
                        <td><span class="badge {{ $item->priorityBadgeClass() }}">{{ $item->priorityLabel() }}</span></td>
                        <td><span class="badge {{ $item->statusBadgeClass() }}">{{ $item->statusLabel() }}</span></td>
                        <td>
                            @if($item->isOverdue())
                                <span class="text-danger font-weight-bold">OVERDUE</span><br>
                                <small class="text-danger">{{ $item->sla_deadline?->format('d M H:i') }}</small>
                            @elseif($item->sla_deadline && !in_array($item->status, ['closed', 'rejected']))
                                <small class="text-success">{{ $item->sla_deadline->format('d M H:i') }}</small>
                            @else
                                <small class="text-muted">-</small>
                            @endif
                        </td>
                        <td style="white-space:nowrap;">
                            <div>{{ $item->created_at->format('d M Y') }}</div>
                            <small class="text-muted">{{ $item->created_at->format('H:i') }}</small>
                        </td>
                        <td><a href="{{ route('hr.requests.show', $item) }}" class="btn btn-xs btn-outline-success"><i class="fas fa-eye"></i></a></td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center text-muted py-5">Tidak ada laporan Human Resources ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($hrRequests->hasPages())
        <div class="mt-3">{{ $hrRequests->links('vendor.pagination.custom') }}</div>
        @endif
    </div>
</div>

@endsection
