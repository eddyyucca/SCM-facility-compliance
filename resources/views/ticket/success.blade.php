<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Terkirim - {{ $complaint->ticket_number }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root{--primary:#0d6efd;--primary-d:#0a58ca;--primary-l:#e7f0ff;--green:#198754;--green-l:#d1e7dd;--line:#dde6f5;}
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Figtree',sans-serif;background:linear-gradient(135deg,#e8eefa,#f0f4fc,#e4eeff);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;}
        .card{background:#fff;border-radius:22px;box-shadow:0 16px 60px rgba(13,110,253,.13);padding:36px;max-width:480px;width:100%;text-align:center;}
        .success-icon{width:64px;height:64px;background:var(--green-l);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;font-size:1.8rem;color:var(--green);}
        .title{font-size:1.3rem;font-weight:800;color:#0a1628;margin-bottom:6px;}
        .subtitle{font-size:.88rem;color:#6b7a99;margin-bottom:22px;line-height:1.6;}
        .ticket-box{background:linear-gradient(135deg,var(--primary-l),#dceffe);border-radius:14px;padding:18px 20px;margin-bottom:20px;}
        .ticket-label{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--primary);margin-bottom:4px;}
        .ticket-number{font-size:1.6rem;font-weight:800;color:#0a1628;letter-spacing:-.5px;font-variant-numeric:tabular-nums;}
        .info-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;text-align:left;}
        .info-item .lbl{font-size:.7rem;font-weight:700;text-transform:uppercase;color:#aaa;letter-spacing:.05em;}
        .info-item .val{font-size:.88rem;font-weight:600;color:#1a2340;margin-top:2px;}
        .divider{height:1px;background:var(--line);margin:18px 0;}
        /* QR Code */
        .qr-section{margin-bottom:20px;}
        .qr-section p{font-size:.8rem;color:#6b7a99;margin-bottom:10px;line-height:1.5;}
        .qr-img{border:3px solid var(--primary-l);border-radius:14px;padding:8px;background:#fff;display:inline-block;}
        .qr-img img{display:block;border-radius:8px;}
        /* SLA info */
        .sla-info{background:#f8f9fa;border-radius:12px;padding:12px 16px;font-size:.82rem;color:#495057;margin-bottom:20px;text-align:left;border-left:3px solid var(--primary);}
        /* Buttons */
        .btn-row{display:flex;gap:10px;flex-wrap:wrap;}
        .btn-primary{flex:1;padding:12px;background:linear-gradient(135deg,var(--primary),var(--primary-d));color:#fff;border:none;border-radius:10px;font:inherit;font-size:.88rem;font-weight:700;cursor:pointer;text-decoration:none;text-align:center;transition:filter .15s;}
        .btn-primary:hover{filter:brightness(1.06);color:#fff;}
        .btn-secondary{flex:1;padding:12px;background:#f0f4fc;color:var(--primary);border:1px solid var(--line);border-radius:10px;font:inherit;font-size:.88rem;font-weight:700;cursor:pointer;text-decoration:none;text-align:center;}
        .btn-secondary:hover{background:var(--primary-l);}
        @media(max-width:400px){.info-grid{grid-template-columns:1fr;}.btn-row{flex-direction:column;}}
    </style>
</head>
<body>
<div class="card">
    <div class="success-icon"><i class="fas fa-check"></i></div>
    <div class="title">Laporan Berhasil Dikirim!</div>
    <div class="subtitle">
        Laporan Anda telah diterima. Tim {{ $complaint instanceof \App\Models\HrRequest ? 'Human Resources' : 'GA' }} akan menindaklanjuti sesuai prioritas.<br>
        Simpan nomor tiket berikut untuk memantau status.
    </div>

    {{-- Ticket Number --}}
    <div class="ticket-box">
        <div class="ticket-label">Nomor Tiket</div>
        <div class="ticket-number">{{ $complaint->ticket_number }}</div>
    </div>

    {{-- Info Grid --}}
    <div class="info-grid">
        <div class="info-item">
            <div class="lbl">Tipe</div>
            <div class="val">{{ $complaint->typeLabel() }}</div>
        </div>
        @if($complaint instanceof \App\Models\HrRequest)
        <div class="info-item">
            <div class="lbl">Departemen</div>
            <div class="val">{{ $complaint->department }}</div>
        </div>
        @elseif($complaint->building)
        <div class="info-item">
            <div class="lbl">Bangunan</div>
            <div class="val">{{ $complaint->building }}</div>
        </div>
        @endif
        @if(!($complaint instanceof \App\Models\HrRequest) && $complaint->room_number)
        <div class="info-item">
            <div class="lbl">No. Kamar</div>
            <div class="val">{{ $complaint->room_number }}</div>
        </div>
        @endif
        <div class="info-item">
            <div class="lbl">Status</div>
            <div class="val" style="color:#dc3545;">Open</div>
        </div>
        <div class="info-item">
            <div class="lbl">Waktu Laporan</div>
            <div class="val">{{ $complaint->created_at->format('d M Y H:i') }}</div>
        </div>
        <div class="info-item">
            <div class="lbl">Target SLA</div>
            <div class="val">{{ $complaint->sla_deadline?->format('d M Y H:i') }}</div>
        </div>
    </div>

    <div class="divider"></div>

    {{-- QR Code --}}
    <div class="qr-section">
        <p>
            <i class="fas fa-qrcode mr-1 text-primary"></i>
            <strong>Scan QR Code ini</strong> untuk mengecek status tiket Anda kapan saja tanpa perlu mengingat nomor tiket.
        </p>
        <div class="qr-img">
            @php
                $qrUrl = url('/tiket/' . $complaint->ticket_number);
                $qrApi = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($qrUrl) . '&bgcolor=ffffff&color=0a1628&qzone=1&format=png';
            @endphp
            <img src="{{ $qrApi }}" width="200" height="200" alt="QR Code {{ $complaint->ticket_number }}"
                 onerror="this.style.display='none';document.getElementById('qr-fallback').style.display='block'">
            <div id="qr-fallback" style="display:none;width:200px;height:200px;line-height:200px;text-align:center;color:#aaa;font-size:.8rem;">
                QR tidak tersedia
            </div>
        </div>
        <div style="font-size:.72rem;color:#aaa;margin-top:8px;">{{ $qrUrl }}</div>
    </div>

    <div class="sla-info">
        <i class="fas fa-clock text-primary mr-1"></i>
        <strong>Estimasi Penanganan:</strong>
        {{ method_exists($complaint, 'slaTargetHours') ? $complaint->slaTargetHours() : (\App\Models\Complaint::$slaHours[$complaint->type][$complaint->priority] ?? 24) }} jam
        dari waktu laporan diterima.
    </div>

    <div class="btn-row">
        <a href="{{ url('/') }}" class="btn-secondary">
            <i class="fas fa-plus mr-1"></i> Laporan Baru
        </a>
        <a href="{{ route('ticket.show', $complaint->ticket_number) }}" class="btn-primary">
            <i class="fas fa-eye mr-1"></i> Cek Status
        </a>
    </div>
</div>
</body>
</html>
