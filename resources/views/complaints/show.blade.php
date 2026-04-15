@extends('layouts.app')
@section('title', 'Detail — ' . $complaint->ticket_number)
@section('page_title', 'Detail Laporan')
@section('breadcrumb', $complaint->ticket_number)

@section('content')

<div class="row">
    {{-- Left: Detail --}}
    <div class="col-md-8">

        {{-- Header card --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
                    <div>
                        <p class="text-muted mb-1" style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;">
                            Nomor Tiket
                        </p>
                        <h2 style="font-size:1.6rem;font-weight:800;color:#0a1628;letter-spacing:-.5px;">
                            {{ $complaint->ticket_number }}
                        </h2>
                        <div class="d-flex flex-wrap gap-2 mt-2" style="gap:6px;">
                            <span class="badge badge-lg {{ $complaint->type === 'receptionist' ? 'type-rec' : ($complaint->type === 'hk' ? 'type-hk' : 'type-ldy') }}" style="font-size:.78rem;padding:5px 10px;">
                                {{ $complaint->typeLabel() }}
                            </span>
                            <span class="badge badge-lg {{ $complaint->statusBadgeClass() }}" style="font-size:.78rem;padding:5px 10px;">
                                {{ $complaint->statusLabel() }}
                            </span>
                            @if($complaint->isOverdue())
                            <span class="badge badge-danger" style="font-size:.78rem;padding:5px 10px;">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Overdue SLA
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right text-muted" style="font-size:.82rem;">
                        <div>Dilaporkan: <strong>{{ $complaint->created_at->format('d M Y H:i') }}</strong></div>
                        @if($complaint->resolved_at)
                        <div class="text-success">Diselesaikan: <strong>{{ $complaint->resolved_at->format('d M Y H:i') }}</strong></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Complaint info --}}
        <div class="card mb-3">
            <div class="card-header py-2">
                <h3 class="card-title" style="font-size:.88rem;font-weight:700;">
                    <i class="fas fa-info-circle text-primary mr-2"></i> Informasi Laporan
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted d-block font-weight-bold text-uppercase" style="font-size:.7rem;letter-spacing:.05em;">Nama Pelapor</small>
                        <span style="font-size:.95rem;">{{ $complaint->reporter_name }}</span>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted d-block font-weight-bold text-uppercase" style="font-size:.7rem;letter-spacing:.05em;">WhatsApp</small>
                        <span style="font-size:.95rem;">{{ $complaint->reporter_wa ?? '—' }}</span>
                    </div>
                    @if($complaint->room_number)
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted d-block font-weight-bold text-uppercase" style="font-size:.7rem;letter-spacing:.05em;">No. Kamar</small>
                        <span style="font-size:.95rem;">{{ $complaint->room_number }}</span>
                    </div>
                    @endif
                    @if($complaint->location)
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted d-block font-weight-bold text-uppercase" style="font-size:.7rem;letter-spacing:.05em;">Lokasi / Area</small>
                        <span style="font-size:.95rem;">{{ $complaint->location }}</span>
                    </div>
                    @endif
                </div>

                <div class="mt-2">
                    <small class="text-muted d-block font-weight-bold text-uppercase mb-2" style="font-size:.7rem;letter-spacing:.05em;">Deskripsi Keluhan</small>
                    <div style="background:#f8f9fa;border-radius:10px;padding:14px;line-height:1.7;font-size:.92rem;border-left:3px solid #0d6efd;">
                        {{ $complaint->description }}
                    </div>
                </div>
            </div>
        </div>

        {{-- SLA info --}}
        <div class="card mb-3">
            <div class="card-header py-2">
                <h3 class="card-title" style="font-size:.88rem;font-weight:700;">
                    <i class="fas fa-clock text-warning mr-2"></i> Service Level Agreement (SLA)
                </h3>
            </div>
            <div class="card-body">
                @php
                    $slaHours = \App\Models\Complaint::$slaHours[$complaint->type][$complaint->priority] ?? 24;
                @endphp
                <div class="row text-center">
                    <div class="col-4">
                        <div style="padding:12px;background:#f8f9fa;border-radius:10px;">
                            <div style="font-size:.7rem;color:#6c757d;font-weight:700;text-transform:uppercase;margin-bottom:4px;">Target SLA</div>
                            <div style="font-size:1.4rem;font-weight:700;color:#0a1628;">{{ $slaHours }} jam</div>
                            <div style="font-size:.72rem;color:#aaa;margin-top:2px;">Prioritas: {{ ucfirst($complaint->priority) }}</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div style="padding:12px;background:#f8f9fa;border-radius:10px;">
                            <div style="font-size:.7rem;color:#6c757d;font-weight:700;text-transform:uppercase;margin-bottom:4px;">Deadline</div>
                            <div style="font-size:.95rem;font-weight:600;color:{{ $complaint->isOverdue() ? '#dc3545' : '#198754' }};">
                                {{ $complaint->sla_deadline?->format('d M Y') ?? '—' }}
                            </div>
                            <div style="font-size:.72rem;color:#aaa;margin-top:2px;">
                                {{ $complaint->sla_deadline?->format('H:i') ?? '' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div style="padding:12px;background:#f8f9fa;border-radius:10px;">
                            <div style="font-size:.7rem;color:#6c757d;font-weight:700;text-transform:uppercase;margin-bottom:4px;">Status SLA</div>
                            @if($complaint->isOverdue())
                                <div style="font-size:.9rem;font-weight:700;color:#dc3545;">
                                    Lewat {{ abs($complaint->slaHoursLeft()) }}j
                                </div>
                                <div style="font-size:.72rem;color:#dc3545;margin-top:2px;">⚠ Overdue</div>
                            @elseif($complaint->status === 'closed')
                                <div style="font-size:.9rem;font-weight:700;color:#198754;">Selesai ✓</div>
                                <div style="font-size:.72rem;color:#198754;margin-top:2px;">Tepat waktu</div>
                            @else
                                <div style="font-size:.9rem;font-weight:700;color:#198754;">
                                    +{{ $complaint->slaHoursLeft() }}j
                                </div>
                                <div style="font-size:.72rem;color:#aaa;margin-top:2px;">Sisa waktu</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Right: Update Status --}}
    <div class="col-md-4">
        <div class="card" style="position:sticky;top:70px;">
            <div class="card-header py-2" style="background:linear-gradient(135deg,#0d6efd,#0a58ca);">
                <h3 class="card-title text-white mb-0" style="font-size:.88rem;font-weight:700;">
                    <i class="fas fa-edit mr-2"></i> Update Status
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('complaints.status', $complaint) }}">
                    @csrf
                    @method('PATCH')

                    <div class="form-group">
                        <label class="col-form-label-sm font-weight-bold">Status</label>
                        <select name="status" class="form-control form-control-sm" style="border-radius:8px;">
                            <option value="open"     {{ $complaint->status === 'open'     ? 'selected':'' }}>Open</option>
                            <option value="progress" {{ $complaint->status === 'progress' ? 'selected':'' }}>Progress</option>
                            <option value="closed"   {{ $complaint->status === 'closed'   ? 'selected':'' }}>Closed</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label-sm font-weight-bold">Catatan Admin</label>
                        <textarea name="admin_notes" class="form-control form-control-sm"
                                  rows="4" placeholder="Tambahkan catatan penanganan..."
                                  style="border-radius:8px;">{{ $complaint->admin_notes }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-sm" style="border-radius:8px;">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </form>

                @if($complaint->admin_notes)
                <div class="mt-3 p-3" style="background:#f8f9fa;border-radius:10px;border-left:3px solid #0d6efd;">
                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;color:#6c757d;margin-bottom:4px;">Catatan Terakhir</div>
                    <p style="font-size:.84rem;margin:0;line-height:1.5;">{{ $complaint->admin_notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="mt-2">
            <a href="{{ route('complaints.index') }}" class="btn btn-secondary btn-block btn-sm" style="border-radius:8px;">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</div>

@endsection
