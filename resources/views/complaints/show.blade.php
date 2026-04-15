@extends('layouts.app')
@section('title', 'Detail — ' . $complaint->ticket_number)
@section('page_title', 'Detail Laporan')
@section('breadcrumb', $complaint->ticket_number)

@section('content')

{{-- Ticket header strip --}}
<div class="card mb-3" style="background:linear-gradient(135deg,#0d6efd,#0a4db1);border-radius:14px;overflow:hidden;">
    <div class="card-body py-3 px-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap:12px;">
            <div>
                <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.65);">Nomor Tiket</div>
                <div style="font-size:1.6rem;font-weight:800;color:#fff;letter-spacing:-.04em;line-height:1.1;">{{ $complaint->ticket_number }}</div>
                <div style="margin-top:8px;display:flex;flex-wrap:wrap;gap:6px;">
                    <span class="badge {{ $complaint->statusBadgeClass() }}" style="font-size:.76rem;padding:5px 12px;">{{ $complaint->statusLabel() }}</span>
                    <span class="badge {{ $complaint->type==='receptionist'?'type-rec':($complaint->type==='hk'?'type-hk':'type-ldy') }}" style="font-size:.76rem;padding:5px 12px;">{{ $complaint->typeLabel() }}</span>
                    @if($complaint->isOverdue())
                    <span class="badge badge-danger" style="font-size:.76rem;padding:5px 12px;"><i class="fas fa-exclamation-triangle mr-1"></i>Overdue SLA</span>
                    @endif
                </div>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <div style="background:rgba(255,255,255,.13);border:1px solid rgba(255,255,255,.18);border-radius:10px;padding:10px 16px;min-width:140px;">
                    <div style="font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:rgba(255,255,255,.6);">Dilaporkan</div>
                    <div style="font-size:.88rem;font-weight:700;color:#fff;margin-top:2px;">{{ $complaint->created_at->format('d M Y H:i') }}</div>
                </div>
                @if($complaint->resolved_at)
                <div style="background:rgba(255,255,255,.13);border:1px solid rgba(255,255,255,.18);border-radius:10px;padding:10px 16px;min-width:140px;">
                    <div style="font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:rgba(255,255,255,.6);">Diselesaikan</div>
                    <div style="font-size:.88rem;font-weight:700;color:#fff;margin-top:2px;">{{ $complaint->resolved_at->format('d M Y H:i') }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Left: Detail info --}}
    <div class="col-lg-8">

        {{-- Reporter & complaint info --}}
        <div class="card mb-3">
            <div class="card-header py-2">
                <h3 class="card-title mb-0" style="font-size:.88rem;font-weight:700;">
                    <i class="fas fa-id-card text-primary mr-2"></i> Informasi Pelapor & Laporan
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <div style="font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:#6c757d;font-weight:700;margin-bottom:4px;">Nama Pelapor</div>
                        <div style="font-size:.92rem;font-weight:700;color:#1a2340;">{{ $complaint->reporter_name }}</div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div style="font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:#6c757d;font-weight:700;margin-bottom:4px;">No. WhatsApp</div>
                        <div style="font-size:.92rem;font-weight:600;color:#1a2340;font-family:monospace;">{{ $complaint->reporter_wa ?: '—' }}</div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div style="font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:#6c757d;font-weight:700;margin-bottom:4px;">Bangunan / Area</div>
                        <div style="font-size:.92rem;font-weight:600;color:#1a2340;">{{ $complaint->building ?: '—' }}</div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div style="font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:#6c757d;font-weight:700;margin-bottom:4px;">No. Kamar</div>
                        <div style="font-size:.92rem;font-weight:600;color:#1a2340;">{{ $complaint->room_number ?: '—' }}</div>
                    </div>
                </div>

                <div style="font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:#6c757d;font-weight:700;margin-bottom:8px;">Deskripsi Keluhan</div>
                <div style="background:#f8f9fa;border-left:3px solid #0d6efd;border-radius:0 10px 10px 0;padding:14px 16px;font-size:.88rem;line-height:1.75;color:#32455f;white-space:pre-line;">{{ $complaint->description }}</div>

                @if($complaint->admin_notes)
                <div style="margin-top:14px;font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:#198754;font-weight:700;margin-bottom:8px;">Catatan Admin</div>
                <div style="background:#f0fdf4;border-left:3px solid #198754;border-radius:0 10px 10px 0;padding:14px 16px;font-size:.88rem;line-height:1.75;color:#166534;white-space:pre-line;">{{ $complaint->admin_notes }}</div>
                @endif
            </div>
        </div>

        {{-- SLA --}}
        <div class="card mb-3">
            <div class="card-header py-2">
                <h3 class="card-title mb-0" style="font-size:.88rem;font-weight:700;">
                    <i class="fas fa-clock text-warning mr-2"></i> SLA & Waktu
                </h3>
            </div>
            <div class="card-body">
                @php $slaHours = \App\Models\Complaint::$slaHours[$complaint->type][$complaint->priority] ?? 24; @endphp
                <div class="row text-center">
                    <div class="col-4">
                        <div style="font-size:.68rem;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;font-weight:700;margin-bottom:6px;">Target SLA</div>
                        <div style="font-size:1.3rem;font-weight:800;color:#0d6efd;">{{ $slaHours }}<span style="font-size:.75rem;font-weight:400;color:#6c757d;"> jam</span></div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:.68rem;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;font-weight:700;margin-bottom:6px;">Deadline</div>
                        <div style="font-size:.82rem;font-weight:700;color:#1a2340;">{{ $complaint->sla_deadline?->format('d M Y') ?? '—' }}</div>
                        <div style="font-size:.72rem;color:#aaa;">{{ $complaint->sla_deadline?->format('H:i') ?? '' }}</div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:.68rem;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;font-weight:700;margin-bottom:6px;">Status SLA</div>
                        @if($complaint->isOverdue())
                            <div style="font-size:.85rem;font-weight:800;color:#dc3545;">⚠ Overdue</div>
                            <div style="font-size:.72rem;color:#dc3545;">{{ abs($complaint->slaHoursLeft()) }}j terlewat</div>
                        @elseif($complaint->status === 'closed')
                            <div style="font-size:.85rem;font-weight:800;color:#198754;">✓ Tepat Waktu</div>
                        @else
                            <div style="font-size:.85rem;font-weight:800;color:#198754;">Sisa {{ max(0, $complaint->slaHoursLeft()) }}j</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Right: Update & summary --}}
    <div class="col-lg-4">

        {{-- Update status form --}}
        <div class="card mb-3">
            <div class="card-header py-2" style="background:linear-gradient(135deg,#0d6efd,#0a58ca);">
                <h3 class="card-title text-white mb-0" style="font-size:.88rem;font-weight:700;">
                    <i class="fas fa-pen mr-2"></i> Update Penanganan
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('complaints.status', $complaint) }}">
                    @csrf
                    @method('PATCH')
                    <div class="form-group mb-3">
                        <label class="font-weight-bold" style="font-size:.82rem;">Status</label>
                        <select name="status" class="form-control" style="border-radius:8px;">
                            <option value="open"     {{ $complaint->status === 'open'     ? 'selected' : '' }}>Open</option>
                            <option value="progress" {{ $complaint->status === 'progress' ? 'selected' : '' }}>Progress</option>
                            <option value="closed"   {{ $complaint->status === 'closed'   ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold" style="font-size:.82rem;">Catatan Admin</label>
                        <textarea name="admin_notes" class="form-control" rows="5"
                                  placeholder="Tindak lanjut, hasil pengecekan, dll..."
                                  style="border-radius:8px;resize:vertical;">{{ $complaint->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" style="border-radius:8px;font-weight:700;">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </form>
            </div>
        </div>

        {{-- Quick summary --}}
        <div class="card mb-3">
            <div class="card-header py-2">
                <h3 class="card-title mb-0" style="font-size:.88rem;font-weight:700;">
                    <i class="fas fa-info-circle text-primary mr-2"></i> Ringkasan
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0" style="font-size:.83rem;">
                    <tr>
                        <td class="text-muted" style="width:45%;padding:10px 16px;">Status</td>
                        <td style="padding:10px 16px;font-weight:700;">
                            <span class="badge {{ $complaint->statusBadgeClass() }}">{{ $complaint->statusLabel() }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted" style="padding:10px 16px;">Tipe</td>
                        <td style="padding:10px 16px;font-weight:600;">{{ $complaint->typeLabel() }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted" style="padding:10px 16px;">Prioritas</td>
                        <td style="padding:10px 16px;font-weight:600;">{{ ucfirst($complaint->priority) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted" style="padding:10px 16px;">Dibuat</td>
                        <td style="padding:10px 16px;font-size:.78rem;">{{ $complaint->created_at->diffForHumans() }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <a href="{{ route('complaints.index') }}" class="btn btn-secondary btn-block" style="border-radius:8px;font-weight:700;">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
        </a>
    </div>
</div>

@endsection
