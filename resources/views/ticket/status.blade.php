<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Status Tiket {{ $complaint->ticket_number }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root{--primary:#0d6efd;--primary-l:#e7f0ff;--line:#dde6f5;--text:#1a2340;--muted:#6b7a99;}
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Figtree',sans-serif;background:linear-gradient(135deg,#e8eefa,#f0f4fc);min-height:100vh;padding:24px 16px;}
        .container{max-width:600px;margin:0 auto;}
        .topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;}
        .topbar .brand{font-size:.9rem;font-weight:700;color:#0a1628;}
        .topbar a{padding:7px 14px;background:var(--primary);color:#fff;border-radius:8px;font-size:.82rem;font-weight:600;text-decoration:none;}
        .card{background:#fff;border-radius:18px;box-shadow:0 8px 32px rgba(13,110,253,.1);padding:24px;margin-bottom:16px;}
        .ticket-num{font-size:1.4rem;font-weight:800;color:#0a1628;margin-bottom:8px;}
        .badges{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px;}
        .badge{display:inline-block;padding:4px 12px;border-radius:999px;font-size:.76rem;font-weight:700;}
        .step-timeline{position:relative;padding-left:28px;margin-top:16px;}
        .step{position:relative;padding-bottom:20px;}
        .step:last-child{padding-bottom:0;}
        .step::before{content:'';position:absolute;left:-20px;top:6px;bottom:0;width:2px;background:#e9ecef;}
        .step:last-child::before{display:none;}
        .step-dot{position:absolute;left:-25px;top:2px;width:12px;height:12px;border-radius:50%;background:#e9ecef;border:2px solid #fff;}
        .step-dot.done{background:var(--primary);}
        .step-dot.active{background:#198754;box-shadow:0 0 0 4px rgba(25,135,84,.2);}
        .step-dot.rejected{background:#6c757d;box-shadow:0 0 0 4px rgba(108,117,125,.2);}
        .step-dot.pending{background:#dee2e6;}
        .step-title{font-size:.85rem;font-weight:700;margin-bottom:2px;}
        .step-desc{font-size:.78rem;color:var(--muted);}
        .info-row{display:flex;gap:6px;margin-bottom:8px;font-size:.85rem;}
        .info-row .lbl{color:var(--muted);min-width:100px;}
        .info-row .val{font-weight:600;}
        .desc-box{background:#f8f9fa;border-radius:10px;padding:12px;font-size:.85rem;line-height:1.6;border-left:3px solid var(--primary);}
        .admin-box{background:#eff6ff;border-radius:10px;padding:12px;font-size:.84rem;line-height:1.6;border-left:3px solid #198754;margin-top:10px;}
        .admin-box.rejected{background:#f8f9fa;border-left-color:#6c757d;}
        .overdue-warn{background:#fff3cd;border:1px solid #ffc107;border-radius:10px;padding:10px 14px;font-size:.82rem;color:#856404;margin-bottom:12px;}
        .rejected-notice{background:#f8f9fa;border:1px solid #dee2e6;border-radius:10px;padding:12px 16px;font-size:.84rem;color:#495057;margin-bottom:12px;display:flex;align-items:flex-start;gap:10px;}
        .rejected-notice i{color:#6c757d;margin-top:2px;flex-shrink:0;}
    </style>
</head>
<body>
<div class="container">
    <div class="topbar">
        <div class="brand"><i class="fas fa-building mr-2"></i>GA Facility</div>
        <a href="{{ url('/') }}"><i class="fas fa-home mr-1"></i> Form Laporan</a>
    </div>

    @if($complaint->isOverdue())
    <div class="overdue-warn">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        <strong>Perhatian:</strong> Laporan ini melewati batas waktu SLA. Tim GA sedang menindaklanjuti.
    </div>
    @endif

    @if($complaint->status === 'rejected')
    <div class="rejected-notice">
        <i class="fas fa-ban fa-lg"></i>
        <div>
            <strong>Laporan Ditolak</strong><br>
            Permintaan Anda tidak dapat diproses. Silakan hubungi tim GA secara langsung jika ada pertanyaan lebih lanjut.
        </div>
    </div>
    @endif

    {{-- Header --}}
    <div class="card">
        <div class="ticket-num">{{ $complaint->ticket_number }}</div>
        <div class="badges">
            <span class="badge" style="background:{{ ['receptionist'=>'#cfe2ff','hk'=>'#d1e7dd','laundry'=>'#fff3cd'][$complaint->type] ?? '#e9ecef' }};color:{{ ['receptionist'=>'#084298','hk'=>'#0f5132','laundry'=>'#664d03'][$complaint->type] ?? '#495057' }};">
                {{ $complaint->typeLabel() }}
            </span>
            <span class="badge" style="background:{{ $complaint->statusColor() }}22;color:{{ $complaint->statusColor() }};">
                {{ $complaint->statusLabel() }}
            </span>
        </div>

        {{-- Progress timeline --}}
        @php $isRejected = $complaint->status === 'rejected'; @endphp
        <div class="step-timeline">
            <div class="step">
                <div class="step-dot done"></div>
                <div class="step-title">Open — Laporan Diterima</div>
                <div class="step-desc">{{ $complaint->created_at->format('d M Y H:i') }}</div>
            </div>
            @if(!$isRejected)
            <div class="step">
                <div class="step-dot {{ in_array($complaint->status, ['progress','closed']) ? ($complaint->status === 'progress' ? 'active' : 'done') : 'pending' }}"></div>
                <div class="step-title" style="{{ $complaint->status === 'open' ? 'color:#aaa' : '' }}">Progress — Sedang Ditangani</div>
                <div class="step-desc">{{ $complaint->status === 'open' ? 'Menunggu tindakan tim GA' : 'Tim GA sedang menangani laporan' }}</div>
            </div>
            <div class="step">
                <div class="step-dot {{ $complaint->status === 'closed' ? 'active' : 'pending' }}"></div>
                <div class="step-title" style="{{ $complaint->status !== 'closed' ? 'color:#aaa' : '' }}">Closed — Selesai</div>
                <div class="step-desc">
                    {{ $complaint->resolved_at ? $complaint->resolved_at->format('d M Y H:i') : 'Belum diselesaikan' }}
                </div>
            </div>
            @else
            <div class="step">
                <div class="step-dot rejected"></div>
                <div class="step-title" style="color:#6c757d;">Rejected — Laporan Ditolak</div>
                <div class="step-desc">
                    {{ $complaint->resolved_at ? $complaint->resolved_at->format('d M Y H:i') : '—' }}
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Detail --}}
    <div class="card">
        <div style="font-size:.82rem;font-weight:700;text-transform:uppercase;color:var(--muted);letter-spacing:.05em;margin-bottom:14px;">Detail Laporan</div>
        <div class="info-row"><span class="lbl">Pelapor</span><span class="val">{{ $complaint->reporter_name }}</span></div>
        @if($complaint->building)
        <div class="info-row"><span class="lbl">Bangunan</span><span class="val">{{ $complaint->building }}</span></div>
        @endif
        @if($complaint->room_number)
        <div class="info-row"><span class="lbl">No. Kamar</span><span class="val">{{ $complaint->room_number }}</span></div>
        @endif
        <div class="info-row"><span class="lbl">Target SLA</span>
            <span class="val" style="color:{{ $complaint->isOverdue() ? '#dc3545' : '#198754' }};">
                {{ $complaint->sla_deadline?->format('d M Y H:i') ?? '—' }}
                {{ $complaint->isOverdue() ? '⚠' : '' }}
            </span>
        </div>
        <div style="margin-top:12px;">
            <div style="font-size:.74rem;font-weight:700;text-transform:uppercase;color:var(--muted);margin-bottom:6px;">Deskripsi</div>
            <div class="desc-box">{{ $complaint->description }}</div>
        </div>
        @if($complaint->admin_notes)
        <div class="admin-box {{ $complaint->status === 'rejected' ? 'rejected' : '' }}">
            @if($complaint->status === 'rejected')
                <strong style="font-size:.75rem;text-transform:uppercase;letter-spacing:.04em;color:#6c757d;">Alasan Penolakan:</strong>
            @else
                <strong style="font-size:.75rem;text-transform:uppercase;letter-spacing:.04em;color:#198754;">Catatan Tim GA:</strong>
            @endif
            <br>{{ $complaint->admin_notes }}
        </div>
        @endif
    </div>

    <div style="text-align:center;padding:16px 0;font-size:.78rem;color:var(--muted);">
        GA Facility Complaint Management — {{ now()->format('Y') }}
    </div>
</div>
</body>
</html>
