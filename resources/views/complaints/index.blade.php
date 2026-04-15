@extends('layouts.app')
@section('title', 'Semua Laporan')
@section('page_title', 'Semua Laporan')
@section('breadcrumb', 'Laporan')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title" style="font-size:.9rem;font-weight:700;">
            <i class="fas fa-clipboard-list text-primary mr-2"></i> Daftar Laporan
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>

    <div class="card-body">
        {{-- Filters --}}
        <form method="GET" action="{{ route('complaints.index') }}" class="mb-3">
            <div class="row align-items-end g-2">
                <div class="col-md-3 mb-2">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" name="search" class="form-control"
                               placeholder="Cari tiket / nama..." value="{{ request('search') }}">
                    </div>
                </div>

                @if(count($types) > 1)
                <div class="col-md-2 mb-2">
                    <select name="type" class="form-control form-control-sm">
                        <option value="">Semua Tipe</option>
                        <option value="receptionist" {{ request('type') === 'receptionist' ? 'selected':'' }}>Receptionist</option>
                        <option value="hk"           {{ request('type') === 'hk'           ? 'selected':'' }}>Housekeeping</option>
                        <option value="laundry"      {{ request('type') === 'laundry'      ? 'selected':'' }}>Laundry</option>
                    </select>
                </div>
                @endif

                <div class="col-md-2 mb-2">
                    <select name="status" class="form-control form-control-sm">
                        <option value="">Semua Status</option>
                        <option value="open"     {{ request('status') === 'open'     ? 'selected':'' }}>Open</option>
                        <option value="progress" {{ request('status') === 'progress' ? 'selected':'' }}>Progress</option>
                        <option value="closed"   {{ request('status') === 'closed'   ? 'selected':'' }}>Closed</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected':'' }}>Rejected</option>
                    </select>
                </div>

                <div class="col-md-2 mb-2">
                    <div class="form-check mt-1" style="padding-left:1.4rem;">
                        <input type="checkbox" class="form-check-input" id="overdue-check"
                               name="overdue" value="1" {{ request()->boolean('overdue') ? 'checked' : '' }}>
                        <label class="form-check-label font-weight-bold text-danger" for="overdue-check"
                               style="font-size:.84rem;">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Overdue SLA
                        </label>
                    </div>
                </div>
                <div class="col-auto mb-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('complaints.index') }}" class="btn btn-secondary btn-sm ml-1">
                        <i class="fas fa-times mr-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover table-sm" style="font-size:.85rem;">
                <thead>
                    <tr>
                        <th>Tiket</th>
                        @if(count($types) > 1)<th>Tipe</th>@endif
                        <th>Pelapor</th>
                        <th>Kamar / Lokasi</th>
                        <th>Status</th>
                        <th>SLA Deadline</th>
                        <th>Tanggal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($complaints as $c)
                    <tr class="{{ $c->isOverdue() ? 'overdue' : '' }}">
                        <td>
                            <a href="{{ route('complaints.show', $c) }}" class="ticket-link">
                                {{ $c->ticket_number }}
                            </a>
                        </td>
                        @if(count($types) > 1)
                        <td>
                            <span class="badge {{ $c->type === 'receptionist' ? 'type-rec' : ($c->type === 'hk' ? 'type-hk' : 'type-ldy') }}">
                                {{ $c->typeLabel() }}
                            </span>
                        </td>
                        @endif
                        <td>
                            <div class="font-weight-600">{{ $c->reporter_name }}</div>
                            @if($c->reporter_wa)
                            <div style="font-size:.75rem;color:#aaa;">{{ $c->reporter_wa }}</div>
                            @endif
                        </td>
                        <td>{{ $c->room_number ?? $c->location ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $c->statusBadgeClass() }}">{{ $c->statusLabel() }}</span>
                        </td>
                        <td>
                            @if($c->isOverdue())
                                <span class="text-danger font-weight-bold">⚠ OVERDUE</span><br>
                                <small class="text-danger">{{ $c->sla_deadline?->format('d M H:i') }}</small>
                            @elseif($c->sla_deadline && $c->status !== 'closed')
                                <small class="text-success">{{ $c->sla_deadline->format('d M H:i') }}</small>
                            @else
                                <small class="text-muted">—</small>
                            @endif
                        </td>
                        <td style="white-space:nowrap;">
                            <div>{{ $c->created_at->format('d M Y') }}</div>
                            <small class="text-muted">{{ $c->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <a href="{{ route('complaints.show', $c) }}"
                               class="btn btn-xs btn-outline-primary">
                               <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="fas fa-search fa-2x mb-2 d-block text-light"></i>
                            Tidak ada laporan ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($complaints->hasPages())
        <div class="mt-3">
            {{ $complaints->links('vendor.pagination.custom') }}
        </div>
        @endif
    </div>
</div>

@endsection
