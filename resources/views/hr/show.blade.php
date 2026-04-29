@extends('layouts.app')
@section('title', 'Detail - ' . $hrRequest->ticket_number)
@section('page_title', 'Detail Human Resources')
@section('breadcrumb', $hrRequest->ticket_number)

@section('content')

<div class="card mb-3" style="background:linear-gradient(135deg,#0f766e,#115e59);border-radius:14px;overflow:hidden;">
    <div class="card-body py-3 px-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap:12px;">
            <div>
                <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.68);">Nomor Tiket Human Resources</div>
                <div style="font-size:1.6rem;font-weight:800;color:#fff;line-height:1.1;">{{ $hrRequest->ticket_number }}</div>
                <div style="margin-top:8px;display:flex;flex-wrap:wrap;gap:6px;">
                    <span class="badge {{ $hrRequest->statusBadgeClass() }}" style="font-size:.76rem;padding:5px 12px;">{{ $hrRequest->statusLabel() }}</span>
                    <span class="badge {{ $hrRequest->priorityBadgeClass() }}" style="font-size:.76rem;padding:5px 12px;">{{ $hrRequest->priorityLabel() }}</span>
                    @if($hrRequest->isOverdue())
                    <span class="badge badge-danger" style="font-size:.76rem;padding:5px 12px;">Overdue SLA</span>
                    @endif
                </div>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <div style="background:rgba(255,255,255,.13);border:1px solid rgba(255,255,255,.18);border-radius:10px;padding:10px 16px;min-width:140px;">
                    <div style="font-size:.66rem;font-weight:700;text-transform:uppercase;color:rgba(255,255,255,.65);">Dilaporkan</div>
                    <div style="font-size:.88rem;font-weight:700;color:#fff;">{{ $hrRequest->created_at->format('d M Y H:i') }}</div>
                </div>
                @if($hrRequest->resolved_at)
                <div style="background:rgba(255,255,255,.13);border:1px solid rgba(255,255,255,.18);border-radius:10px;padding:10px 16px;min-width:140px;">
                    <div style="font-size:.66rem;font-weight:700;text-transform:uppercase;color:rgba(255,255,255,.65);">Diselesaikan</div>
                    <div style="font-size:.88rem;font-weight:700;color:#fff;">{{ $hrRequest->resolved_at->format('d M Y H:i') }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header py-2">
                <h3 class="card-title mb-0" style="font-size:.88rem;font-weight:700;">
                    <i class="fas fa-id-card text-success mr-2"></i> Informasi Karyawan & Permintaan
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach([
                        'Nama Karyawan' => $hrRequest->employee_name,
                        'NIK / ID' => $hrRequest->employee_id ?: '-',
                        'Perusahaan' => $hrRequest->company_name,
                        'Departemen' => $hrRequest->department,
                        'Jabatan' => $hrRequest->position ?: '-',
                        'WhatsApp' => $hrRequest->phone,
                        'Email' => $hrRequest->email ?: '-',
                        'Periode Terkait' => $hrRequest->period ?: '-',
                    ] as $label => $value)
                    <div class="col-sm-6 mb-3">
                        <div style="font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:#6c757d;font-weight:700;margin-bottom:4px;">{{ $label }}</div>
                        <div style="font-size:.92rem;font-weight:600;color:#1a2340;">{{ $value }}</div>
                    </div>
                    @endforeach
                </div>

                <div style="font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:#6c757d;font-weight:700;margin-bottom:8px;">Jenis Layanan</div>
                <div class="mb-3" style="font-size:1rem;font-weight:800;color:#0f766e;">{{ $hrRequest->service_type }}</div>

                <div style="font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:#6c757d;font-weight:700;margin-bottom:8px;">Detail Permintaan</div>
                <div style="background:#f8f9fa;border-left:3px solid #0f766e;border-radius:0 10px 10px 0;padding:14px 16px;font-size:.88rem;line-height:1.75;color:#32455f;white-space:pre-line;">{{ $hrRequest->description }}</div>

                @if($hrRequest->admin_notes)
                <div style="margin-top:14px;font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:#198754;font-weight:700;margin-bottom:8px;">Catatan Superadmin</div>
                <div style="background:#f0fdf4;border-left:3px solid #198754;border-radius:0 10px 10px 0;padding:14px 16px;font-size:.88rem;line-height:1.75;color:#166534;white-space:pre-line;">{{ $hrRequest->admin_notes }}</div>
                @endif

                @if($hrRequest->attachments && count($hrRequest->attachments))
                <div style="margin-top:18px;">
                    <div style="font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:#6c757d;font-weight:700;margin-bottom:10px;">
                        <i class="fas fa-paperclip mr-1"></i> Lampiran
                    </div>
                        <div style="display:flex;flex-wrap:wrap;gap:8px;">
                        @foreach($hrRequest->attachments as $attachment)
                        <a href="{{ route('hr.attachments.show', ['filename' => basename($attachment)]) }}" target="_blank" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-file mr-1"></i> {{ basename($attachment) }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header py-2">
                <h3 class="card-title mb-0" style="font-size:.88rem;font-weight:700;">
                    <i class="fas fa-clock text-warning mr-2"></i> SLA & Penyelesaian
                </h3>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div style="font-size:.68rem;text-transform:uppercase;color:#6c757d;font-weight:700;margin-bottom:6px;">Target SLA</div>
                        <div style="font-size:1.3rem;font-weight:800;color:#0f766e;">{{ $hrRequest->slaTargetHours() }}<span style="font-size:.75rem;font-weight:400;color:#6c757d;"> jam</span></div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:.68rem;text-transform:uppercase;color:#6c757d;font-weight:700;margin-bottom:6px;">Deadline</div>
                        <div style="font-size:.82rem;font-weight:700;color:#1a2340;">{{ $hrRequest->sla_deadline?->format('d M Y') ?? '-' }}</div>
                        <div style="font-size:.72rem;color:#aaa;">{{ $hrRequest->sla_deadline?->format('H:i') ?? '' }}</div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:.68rem;text-transform:uppercase;color:#6c757d;font-weight:700;margin-bottom:6px;">Status SLA</div>
                        @if($hrRequest->isOverdue())
                            <div style="font-size:.85rem;font-weight:800;color:#dc3545;">Overdue</div>
                            <div style="font-size:.72rem;color:#dc3545;">{{ abs($hrRequest->slaHoursLeft()) }}j terlewat</div>
                        @elseif(in_array($hrRequest->status, ['closed', 'rejected']))
                            <div style="font-size:.85rem;font-weight:800;color:#198754;">Selesai</div>
                        @else
                            <div style="font-size:.85rem;font-weight:800;color:#198754;">Sisa {{ max(0, $hrRequest->slaHoursLeft()) }}j</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        {{-- Feedback / Rating --}}
        @if($hrRequest->status === 'closed')
        <div class="card mb-3">
            <div class="card-header py-2">
                <h3 class="card-title mb-0" style="font-size:.88rem;font-weight:700;">
                    <i class="fas fa-star text-warning mr-2"></i> Penilaian Pengguna
                </h3>
            </div>
            <div class="card-body">
                @if($hrRequest->rating)
                    <div class="d-flex align-items-center" style="gap:16px;flex-wrap:wrap;">
                        <div style="text-align:center;min-width:80px;">
                            <div style="font-size:2.4rem;font-weight:900;line-height:1;color:#f59f00;">{{ $hrRequest->rating }}</div>
                            <div style="font-size:1.2rem;color:#ffc107;">
                                @for($i=1;$i<=5;$i++)<span>{{ $i<=$hrRequest->rating ? '★' : '☆' }}</span>@endfor
                            </div>
                            <div style="font-size:.68rem;text-transform:uppercase;color:#6c757d;font-weight:700;margin-top:2px;">dari 5</div>
                        </div>
                        <div style="flex:1;">
                            @if($hrRequest->feedback_auto)
                                <span style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;border-radius:999px;font-size:.74rem;padding:3px 10px;display:inline-block;margin-bottom:6px;">
                                    <i class="fas fa-robot mr-1"></i> Dinilai otomatis (tidak ada feedback setelah 224 jam)
                                </span>
                            @else
                                <div style="font-size:.72rem;color:#6c757d;margin-bottom:6px;">
                                    <i class="fas fa-calendar-alt mr-1"></i> Dikirim {{ $hrRequest->feedback_at?->format('d M Y H:i') }}
                                </div>
                            @endif
                            @if($hrRequest->feedback_text)
                            <div style="background:#fffbeb;border-left:3px solid #ffc107;border-radius:0 8px 8px 0;padding:10px 14px;font-size:.86rem;line-height:1.6;color:#495057;font-style:italic;">
                                "{{ $hrRequest->feedback_text }}"
                            </div>
                            @else
                                <div style="font-size:.82rem;color:#adb5bd;">Tidak ada komentar tertulis.</div>
                            @endif
                        </div>
                    </div>
                @else
                    <div style="text-align:center;padding:16px 0;color:#adb5bd;">
                        <i class="fas fa-star-half-alt" style="font-size:1.6rem;margin-bottom:8px;display:block;color:#dee2e6;"></i>
                        <div style="font-size:.84rem;font-weight:600;">Belum ada penilaian</div>
                        <div style="font-size:.76rem;margin-top:4px;">Pengguna dapat memberi penilaian di halaman status tiket</div>
                    </div>
                @endif
            </div>
        </div>
        @endif

    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header py-2" style="background:linear-gradient(135deg,#0f766e,#115e59);">
                <h3 class="card-title text-white mb-0" style="font-size:.88rem;font-weight:700;">
                    <i class="fas fa-pen mr-2"></i> Tindak Lanjut Superadmin
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('hr.requests.status', $hrRequest) }}">
                    @csrf
                    @method('PATCH')
                    <div class="form-group mb-3">
                        <label class="font-weight-bold" style="font-size:.82rem;">Status</label>
                        <select name="status" class="form-control" style="border-radius:8px;">
                            <option value="open" {{ $hrRequest->status === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="progress" {{ $hrRequest->status === 'progress' ? 'selected' : '' }}>Progress</option>
                            <option value="closed" {{ $hrRequest->status === 'closed' ? 'selected' : '' }}>Closed - Selesai</option>
                            <option value="rejected" {{ $hrRequest->status === 'rejected' ? 'selected' : '' }}>Rejected - Ditolak</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold" style="font-size:.82rem;">Catatan Penanganan</label>
                        <textarea name="admin_notes" class="form-control" rows="5" placeholder="Tindak lanjut, hasil koordinasi, atau alasan penolakan..." style="border-radius:8px;resize:vertical;">{{ $hrRequest->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-success btn-block" style="border-radius:8px;font-weight:700;">
                        <i class="fas fa-save mr-1"></i> Simpan Penanganan
                    </button>
                </form>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header py-2">
                <h3 class="card-title mb-0" style="font-size:.88rem;font-weight:700;">
                    <i class="fas fa-info-circle text-success mr-2"></i> Ringkasan
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0" style="font-size:.83rem;">
                    <tr><td class="text-muted" style="padding:10px 16px;">Status</td><td style="padding:10px 16px;"><span class="badge {{ $hrRequest->statusBadgeClass() }}">{{ $hrRequest->statusLabel() }}</span></td></tr>
                    <tr><td class="text-muted" style="padding:10px 16px;">Prioritas</td><td style="padding:10px 16px;">{{ $hrRequest->priorityLabel() }}</td></tr>
                    <tr><td class="text-muted" style="padding:10px 16px;">Dibuat</td><td style="padding:10px 16px;">{{ $hrRequest->created_at->diffForHumans() }}</td></tr>
                    <tr><td class="text-muted" style="padding:10px 16px;">Lampiran</td><td style="padding:10px 16px;">{{ count($hrRequest->attachments ?? []) }} file</td></tr>
                </table>
            </div>
        </div>

        <a href="{{ route('hr.requests.index') }}" class="btn btn-secondary btn-block" style="border-radius:8px;font-weight:700;">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar HR
        </a>
    </div>
</div>

@endsection
