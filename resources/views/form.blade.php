<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f766e">
    <title>Form Layanan SEDIA</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js@11.2.1/public/assets/styles/choices.min.css">
    <style>
        :root {
            --teal:#0f766e;
            --teal-d:#115e59;
            --teal-l:#e6fffb;
            --navy:#172554;
            --amber:#f59e0b;
            --rose:#dc2626;
            --rose-l:#fee2e2;
            --ink:#152033;
            --muted:#64748b;
            --line:#dbe5ef;
            --soft:#f7fafc;
            --panel:#ffffff;
            --shadow:0 18px 55px rgba(23,37,84,.12);
        }
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Figtree',sans-serif;background:#eef5f7;min-height:100vh;color:var(--ink);}
        body:before{content:"";position:fixed;inset:0;background:linear-gradient(135deg,#f8fafc 0%,#eef7f3 45%,#eaf1ff 100%);z-index:-2;}
        .topbar{height:66px;background:var(--teal);border-bottom:1px solid var(--teal-d);box-shadow:0 10px 28px rgba(15,118,110,.18);display:flex;align-items:center;justify-content:space-between;padding:0 clamp(16px,4vw,42px);position:sticky;top:0;z-index:20;}
        .brand{display:flex;align-items:center;gap:12px;min-width:0;}
        .brand-logo{width:132px;height:44px;object-fit:contain;flex-shrink:0;}
        .brand-copy{line-height:1.1;min-width:0;}
        .brand-name{font-size:.98rem;font-weight:800;color:var(--navy);}
        .brand-sub{font-size:.72rem;font-weight:600;color:var(--muted);margin-top:3px;}
        .top-actions{display:flex;align-items:center;gap:10px;}
        .language-switch{display:flex;align-items:center;gap:4px;border:1px solid rgba(255,255,255,.5);background:#fff;border-radius:10px;padding:4px;}
        .lang-btn{border:0;background:transparent;color:var(--muted);border-radius:7px;padding:6px 9px;font:inherit;font-size:.78rem;font-weight:800;cursor:pointer;line-height:1;}
        .lang-btn.active{background:var(--teal);color:#fff;}
        .login-link{display:inline-flex;align-items:center;gap:7px;border:1px solid var(--line);background:#fff;color:var(--navy);border-radius:8px;padding:9px 13px;text-decoration:none;font-size:.82rem;font-weight:700;white-space:nowrap;}
        .shell{width:min(1040px,calc(100% - 32px));margin:0 auto;padding:28px 0 56px;}
        .service-hero{display:grid;grid-template-columns:minmax(0,1fr) 360px;gap:22px;align-items:stretch;margin-bottom:18px;}
        .hero-copy{padding:26px 0 22px;}
        .eyebrow{display:inline-flex;align-items:center;gap:8px;color:var(--teal-d);font-size:.78rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;margin-bottom:12px;}
        h1{font-size:clamp(1.85rem,4vw,3.15rem);line-height:1.02;color:var(--navy);letter-spacing:0;font-weight:800;max-width:700px;}
        .lead{font-size:1rem;line-height:1.65;color:#42526a;max-width:640px;margin-top:14px;}
        .service-switch{background:rgba(255,255,255,.88);border:1px solid var(--line);border-radius:18px;padding:12px;box-shadow:var(--shadow);display:grid;gap:10px;align-self:center;}
        .service-card{width:100%;border:1.5px solid var(--line);border-radius:12px;background:#fff;padding:16px;text-align:left;cursor:pointer;display:grid;grid-template-columns:44px 1fr auto;gap:12px;align-items:center;font:inherit;color:var(--ink);transition:border-color .18s,box-shadow .18s,transform .18s;}
        .service-card:hover{border-color:#9ccbc6;transform:translateY(-1px);}
        .service-card.active{border-color:var(--teal);box-shadow:0 10px 28px rgba(15,118,110,.16);background:linear-gradient(180deg,#fff,#f1fffc);}
        .service-icon{width:44px;height:44px;border-radius:10px;display:grid;place-items:center;background:#eef6ff;color:var(--navy);font-size:1.1rem;}
        .service-card.active .service-icon{background:var(--teal-l);color:var(--teal-d);}
        .service-title{font-size:1rem;font-weight:800;color:var(--navy);}
        .service-desc{font-size:.8rem;color:var(--muted);margin-top:3px;line-height:1.35;}
        .service-check{width:24px;height:24px;border-radius:999px;border:1px solid var(--line);display:grid;place-items:center;color:transparent;}
        .service-card.active .service-check{background:var(--teal);border-color:var(--teal);color:#fff;}
        .section{display:none;}
        .section.active{display:block;}
        .form-layout{display:grid;grid-template-columns:300px minmax(0,1fr);gap:20px;align-items:start;}
        .side-panel{position:sticky;top:86px;background:#10233f;color:#e5edf7;border-radius:18px;padding:22px;border:1px solid rgba(255,255,255,.1);box-shadow:var(--shadow);}
        .side-panel.hr{background:#123a36;}
        .side-kicker{font-size:.76rem;font-weight:800;color:#9ee8de;text-transform:uppercase;letter-spacing:.08em;margin-bottom:10px;}
        .side-title{font-size:1.35rem;font-weight:800;line-height:1.14;margin-bottom:12px;}
        .side-list{display:grid;gap:11px;margin-top:18px;}
        .side-item{display:flex;gap:10px;align-items:flex-start;font-size:.86rem;line-height:1.45;color:#d8e4ef;}
        .side-item i{color:#f8c56a;margin-top:3px;width:16px;text-align:center;}
        .form-card{background:var(--panel);border:1px solid var(--line);border-radius:18px;padding:24px;box-shadow:var(--shadow);}
        .form-head{display:flex;gap:14px;align-items:center;border-bottom:1px solid var(--line);padding-bottom:18px;margin-bottom:18px;}
        .form-mark{width:52px;height:52px;border-radius:14px;display:grid;place-items:center;background:var(--teal-l);color:var(--teal-d);font-size:1.25rem;flex-shrink:0;}
        .form-title{font-size:1.18rem;font-weight:800;color:var(--navy);}
        .form-sub{font-size:.86rem;color:var(--muted);line-height:1.45;margin-top:4px;}
        .grid2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
        .field{display:grid;gap:6px;margin-bottom:15px;}
        .field label{font-size:.84rem;font-weight:800;color:#26354d;}
        .req{color:var(--rose);}
        .field input,.field select,.field textarea{width:100%;border:1.5px solid var(--line);border-radius:10px;background:#fbfdff;padding:11px 13px;font:inherit;font-size:.9rem;color:var(--ink);transition:border-color .18s,box-shadow .18s,background .18s;}
        .field textarea{min-height:126px;resize:vertical;line-height:1.5;}
        .field input:focus,.field select:focus,.field textarea:focus{outline:none;border-color:var(--teal);box-shadow:0 0 0 3px rgba(15,118,110,.12);background:#fff;}
        .hint{font-size:.76rem;color:var(--muted);line-height:1.45;}
        .choice-row{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;}
        .radio-tile{position:relative;}
        .radio-tile input{position:absolute;opacity:0;pointer-events:none;}
        .radio-tile span{min-height:58px;border:1.5px solid var(--line);border-radius:10px;background:#fbfdff;display:flex;align-items:center;justify-content:center;text-align:center;padding:10px;font-size:.83rem;font-weight:800;color:#334155;cursor:pointer;}
        .radio-tile input:checked + span{border-color:var(--teal);background:var(--teal-l);color:var(--teal-d);box-shadow:0 0 0 3px rgba(15,118,110,.08);}
        .upload-box{position:relative;border:2px dashed #b8c7d9;border-radius:14px;background:linear-gradient(180deg,#fbfdff,#f3f8fc);min-height:118px;display:grid;place-items:center;text-align:center;padding:18px;cursor:pointer;transition:border-color .18s,box-shadow .18s;}
        .upload-box:hover,.upload-box.is-dragover{border-color:var(--teal);box-shadow:0 0 0 4px rgba(15,118,110,.09);}
        .upload-box input[type=file]{position:absolute;inset:0;opacity:0;cursor:pointer;}
        .upload-copy{display:grid;gap:7px;pointer-events:none;color:var(--muted);font-size:.78rem;}
        .upload-copy i{width:42px;height:42px;border-radius:999px;background:var(--teal-l);color:var(--teal-d);display:grid;place-items:center;margin:0 auto;font-size:1rem;}
        .upload-copy strong{font-size:.95rem;color:#2b3b54;}
        .preview-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:12px;margin-top:12px;}
        .preview-card{border:1px solid var(--line);border-radius:12px;overflow:hidden;background:#fff;}
        .preview-card img{width:100%;height:96px;object-fit:cover;display:block;}
        .preview-meta{padding:8px 9px;font-size:.71rem;color:var(--muted);line-height:1.35;background:#f8fbff;}
        .actions{display:flex;gap:10px;align-items:center;justify-content:flex-end;margin-top:8px;}
        .btn{border:0;border-radius:10px;padding:12px 18px;font:inherit;font-size:.91rem;font-weight:800;cursor:pointer;display:inline-flex;gap:8px;align-items:center;justify-content:center;text-decoration:none;}
        .btn-primary{background:linear-gradient(135deg,var(--teal),var(--teal-d));color:#fff;box-shadow:0 10px 24px rgba(15,118,110,.24);}
        .btn-secondary{background:#edf2f7;color:#24344d;}
        .alert-err{padding:13px 15px;border-radius:12px;background:var(--rose-l);border:1px solid #f6b6b6;color:#8f1d1d;font-size:.86rem;margin-bottom:16px;line-height:1.5;}
        .preview-note{display:none;margin-top:12px;padding:12px 14px;border-radius:10px;background:#fff7ed;border:1px solid #fed7aa;color:#9a3412;font-size:.85rem;line-height:1.45;}
        .building-other-wrap{display:none;margin-top:8px;}
        .choices{margin-bottom:0;}
        .choices__inner{min-height:auto;padding:4px 10px !important;border:1.5px solid var(--line) !important;border-radius:10px !important;background:#fbfdff !important;font-size:.9rem;}
        .is-focused .choices__inner,.is-open .choices__inner{border-color:var(--teal) !important;box-shadow:0 0 0 3px rgba(15,118,110,.12);background:#fff !important;}
        .choices__input{background:transparent !important;margin-bottom:0 !important;font-size:.9rem !important;}
        .choices__list--dropdown,.choices__list[aria-expanded]{border:1px solid var(--line) !important;border-radius:10px !important;box-shadow:0 14px 28px rgba(23,37,84,.12);}
        .choices__list--dropdown .choices__item--selectable.is-highlighted,.choices__list[aria-expanded] .choices__item--selectable.is-highlighted{background:#eefbf8 !important;color:var(--teal-d) !important;}
        .ticket-strip{margin-top:18px;background:rgba(255,255,255,.78);border:1px solid var(--line);border-radius:14px;padding:14px;display:flex;gap:12px;align-items:center;color:var(--muted);font-size:.84rem;}
        .ticket-strip i{color:var(--teal);font-size:1.05rem;}
        .ticket-card{background:#fff;border:1px solid var(--line);border-radius:18px;padding:24px;box-shadow:var(--shadow);}
        .ticket-search{display:flex;gap:10px;margin-top:18px;}
        .ticket-search input{flex:1;min-width:0;border:1.5px solid var(--line);border-radius:10px;background:#fbfdff;padding:12px 13px;font:inherit;font-size:.92rem;color:var(--ink);}
        .ticket-search input:focus{outline:none;border-color:var(--teal);box-shadow:0 0 0 3px rgba(15,118,110,.12);background:#fff;}
        .ticket-result{display:none;margin-top:16px;border:1px solid var(--line);border-radius:14px;background:#fbfdff;padding:16px;}
        .ticket-result.show{display:block;}
        .ticket-number{font-size:1.35rem;font-weight:800;color:var(--navy);letter-spacing:0;}
        .status-badge{display:inline-flex;align-items:center;gap:6px;border-radius:999px;padding:5px 11px;font-size:.76rem;font-weight:800;margin-top:7px;}
        .ticket-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:15px;}
        .ticket-label{font-size:.72rem;font-weight:800;text-transform:uppercase;letter-spacing:.04em;color:var(--muted);margin-bottom:3px;}
        .ticket-value{font-size:.88rem;color:#27364d;line-height:1.4;}
        .ticket-desc{margin-top:14px;border-radius:10px;background:#fff;padding:12px;border:1px solid #edf2f7;color:#40516a;font-size:.88rem;line-height:1.55;}
        .ticket-empty{color:#b42318;font-size:.9rem;line-height:1.5;}
        .ticket-loading{display:none;margin-top:12px;color:var(--muted);font-size:.86rem;}
        @media(max-width:860px){
            .service-hero,.form-layout{grid-template-columns:1fr;}
            .side-panel{position:static;}
            .hero-copy{padding-bottom:0;}
            .ticket-grid{grid-template-columns:1fr 1fr;}
        }
        @media(max-width:560px){
            .topbar{height:auto;padding:12px 14px;gap:12px;}
            .top-actions{gap:8px;}
            .lang-btn{padding:6px 7px;}
            .brand-logo{width:104px;height:38px;}
            .shell{width:min(100% - 24px,1040px);padding-top:18px;}
            .service-card{grid-template-columns:38px 1fr auto;padding:13px;}
            .service-icon{width:38px;height:38px;}
            .form-card,.side-panel{border-radius:14px;padding:16px;}
            .form-head{align-items:flex-start;}
            .grid2,.choice-row{grid-template-columns:1fr;}
            .ticket-search{flex-direction:column;}
            .ticket-grid{grid-template-columns:1fr;}
            .actions{flex-direction:column;}
            .btn{width:100%;}
        }
    </style>
</head>
<body>

<header class="topbar">
    <div class="brand">
        <img src="{{ asset('img/sedia.png') }}" alt="SEDIA Logo" class="brand-logo">
    </div>
    <div class="top-actions">
        <div class="language-switch" aria-label="Language selector">
            <button type="button" class="lang-btn active" data-lang="id" onclick="setLanguage('id')">ID</button>
            <button type="button" class="lang-btn" data-lang="en" onclick="setLanguage('en')">EN</button>
            <button type="button" class="lang-btn" data-lang="zh" onclick="setLanguage('zh')">中文</button>
        </div>
        <a href="{{ route('login') }}" class="login-link"><i class="fas fa-lock"></i> Admin</a>
    </div>
</header>

<main class="shell">
    <section class="service-hero">
        <div class="hero-copy">
            <div class="eyebrow"><i class="fas fa-layer-group"></i> Pilih layanan</div>
            <h1>Form satu halaman untuk kebutuhan Human Resources dan fasilitas.</h1>
        </div>

        <div class="service-switch" aria-label="Pilihan layanan">
            <button type="button" class="service-card active" data-service="hr" onclick="switchService('hr')">
                <span class="service-icon"><i class="fas fa-user-tie"></i></span>
                <span>
                    <span class="service-title">Human Resources</span>
                    <span class="service-desc">Permintaan dan konsultasi karyawan</span>
                </span>
                <span class="service-check"><i class="fas fa-check"></i></span>
            </button>
            <button type="button" class="service-card" data-service="ga" onclick="switchService('ga')">
                <span class="service-icon"><i class="fas fa-building-circle-exclamation"></i></span>
                <span>
                    <span class="service-title">General Affair</span>
                    <span class="service-desc">Pengaduan fasilitas dan area kerja</span>
                </span>
                <span class="service-check"><i class="fas fa-check"></i></span>
            </button>
            <button type="button" class="service-card" data-service="ticket" onclick="switchService('ticket')">
                <span class="service-icon"><i class="fas fa-magnifying-glass-location"></i></span>
                <span>
                    <span class="service-title">Cek Tiket</span>
                    <span class="service-desc">Pantau status tiket Human Resources dan General Affair</span>
                </span>
                <span class="service-check"><i class="fas fa-check"></i></span>
            </button>
        </div>
    </section>

    @if($errors->any())
        <div class="alert-err">
            <strong><i class="fas fa-triangle-exclamation"></i> Mohon perbaiki:</strong><br>
            @foreach($errors->all() as $e)- {{ $e }}<br>@endforeach
        </div>
    @endif

    <section class="section active" id="section-hr">
        <div class="form-layout">
            <aside class="side-panel hr">
                <div class="side-kicker">Human Resources service</div>
                <div class="side-title">Kebutuhan karyawan masuk lewat jalur yang lebih rapi.</div>
                <div class="side-list">
                    <div class="side-item"><i class="fas fa-id-card"></i><span>Data pelapor dibuat ringkas agar Human Resources mudah mengenali konteks karyawan.</span></div>
                    <div class="side-item"><i class="fas fa-clipboard-list"></i><span>Jenis layanan dipisahkan untuk surat, payroll, absensi, benefit, dan konsultasi.</span></div>
                    <div class="side-item"><i class="fas fa-paperclip"></i><span>Lampiran disediakan untuk dokumen pendukung seperti foto, bukti, atau file administrasi.</span></div>
                </div>
            </aside>

            <div class="form-card">
                <div class="form-head">
                    <div class="form-mark"><i class="fas fa-user-tie"></i></div>
                    <div>
                        <div class="form-title">Form Layanan Human Resources</div>
                        <div class="form-sub">Preview tampilan untuk pengajuan kebutuhan Human Resources karyawan.</div>
                    </div>
                </div>

                <form id="hr-request-form" method="POST" action="{{ route('hr-requests.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid2">
                        <div class="field">
                            <label>Nama Karyawan <span class="req">*</span></label>
                            <input type="text" name="hr_name" placeholder="Nama lengkap" required>
                        </div>
                        <div class="field">
                            <label>NIK / ID Karyawan</label>
                            <input type="text" name="hr_employee_id" placeholder="Contoh: SCM-00123">
                        </div>
                    </div>

                    <div class="grid2">
                        <div class="field">
                            <label>Perusahaan <span class="req">*</span></label>
                            <select name="hr_company" required>
                                <option value="">Pilih perusahaan</option>
                                @foreach(collect(config('companies', []))->unique()->sort()->values() as $company)
                                    <option value="{{ $company }}">{{ $company }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>Departemen / Bagian <span class="req">*</span></label>
                            <input type="text" name="hr_department" placeholder="Contoh: Produksi, Finance, HSE" required>
                        </div>
                    </div>

                    <div class="grid2">
                        <div class="field">
                            <label>Jabatan</label>
                            <input type="text" name="hr_position" placeholder="Contoh: Operator, Staff, Supervisor">
                        </div>
                        <div class="field">
                            <label>Nomor WhatsApp <span class="req">*</span></label>
                            <input type="tel" name="hr_phone" placeholder="08xxxxxxxxxx" required>
                        </div>
                    </div>

                    <div class="field">
                            <label>Jenis Layanan Human Resources <span class="req">*</span></label>
                        <select name="hr_service" required>
                            <option value="">Pilih jenis layanan</option>
                            <option>Surat keterangan kerja</option>
                            <option>Payroll / slip gaji</option>
                            <option>Absensi, cuti, atau izin</option>
                            <option>BPJS, asuransi, atau benefit</option>
                            <option>Rekrutmen / onboarding</option>
                            <option>Konsultasi hubungan kerja</option>
                            <option>Lainnya</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>Tingkat Kebutuhan <span class="req">*</span></label>
                        <div class="choice-row">
                            <label class="radio-tile"><input type="radio" name="hr_priority" value="normal" required checked><span>Normal</span></label>
                            <label class="radio-tile"><input type="radio" name="hr_priority" value="penting"><span>Penting</span></label>
                            <label class="radio-tile"><input type="radio" name="hr_priority" value="mendesak"><span>Mendesak</span></label>
                        </div>
                    </div>

                    <div class="grid2">
                        <div class="field">
                            <label>Tanggal / Periode Terkait</label>
                            <input type="text" name="hr_period" placeholder="Contoh: April 2026 atau 20/04/2026">
                        </div>
                        <div class="field">
                            <label>Email</label>
                            <input type="email" name="hr_email" placeholder="nama@perusahaan.com">
                        </div>
                    </div>

                    <div class="field">
                        <label>Detail Permintaan <span class="req">*</span></label>
                        <textarea name="hr_description" placeholder="Tuliskan kebutuhan Human Resources secara jelas..." required></textarea>
                    </div>

                    <div class="field">
                        <label>Lampiran Pendukung</label>
                        <label class="upload-box">
                            <input type="file" name="hr_attachments[]" multiple accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.xls,.xlsx">
                            <span class="upload-copy">
                                <i class="fas fa-paperclip"></i>
                                <strong>Klik atau drag lampiran ke sini</strong>
                                <span>Opsional. Bisa berupa foto atau dokumen pendukung.</span>
                            </span>
                        </label>
                    </div>

                    <div class="actions">
                        <button type="reset" class="btn btn-secondary"><i class="fas fa-rotate-left"></i> Reset</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim Laporan Human Resources</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="ticket-strip">
            <i class="fas fa-qrcode"></i>
            <span>Setelah laporan Human Resources dikirim, sistem akan membuat nomor tiket dan QR untuk cek status.</span>
        </div>
    </section>

    <section class="section" id="section-ga">
        <div class="form-layout">
            <aside class="side-panel">
                <div class="side-kicker">General Affair facility</div>
                <div class="side-title">Pengaduan fasilitas langsung masuk ke tiket General Affair.</div>
                <div class="side-list">
                    <div class="side-item"><i class="fas fa-bed"></i><span>Receptionist untuk fasilitas kamar dan kebutuhan penghuni.</span></div>
                    <div class="side-item"><i class="fas fa-broom"></i><span>Housekeeping untuk kebersihan area, toilet, sampah, dan lingkungan kerja.</span></div>
                    <div class="side-item"><i class="fas fa-shirt"></i><span>Laundry untuk laporan cucian tertukar, rusak, belum selesai, atau kurang jumlah.</span></div>
                </div>
            </aside>

            <div class="form-card">
                <div class="form-head">
                    <div class="form-mark"><i class="fas fa-building-circle-exclamation"></i></div>
                    <div>
                        <div class="form-title">Form Pengaduan Fasilitas General Affair</div>
                        <div class="form-sub">Isi data fasilitas agar laporan bisa ditangani oleh tim yang sesuai.</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('complaint.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="field">
                        <label>Tipe Komplain <span class="req">*</span></label>
                        <select name="type" id="type-select" required onchange="onTypeChange(this.value)">
                            <option value="">Pilih tipe komplain</option>
                            <option value="receptionist" {{ old('type') === 'receptionist' ? 'selected' : '' }}>Receptionist - Fasilitas Kamar</option>
                            <option value="hk" {{ old('type') === 'hk' ? 'selected' : '' }}>Housekeeping - Kebersihan Area</option>
                            <option value="laundry" {{ old('type') === 'laundry' ? 'selected' : '' }}>Laundry - Laporan Cucian</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>Bangunan / Area <span class="req">*</span></label>
                        @php
                            $buildingOptions = collect(config('buildings', []))
                                ->flatten()
                                ->unique()
                                ->sort(SORT_NATURAL | SORT_FLAG_CASE)
                                ->values();
                            $oldBuilding = old('building');
                            $isOtherBuilding = filled($oldBuilding) && !$buildingOptions->contains($oldBuilding);
                        @endphp
                        <select id="building-select" name="building" required>
                            <option value="">Pilih bangunan / area</option>
                            @foreach($buildingOptions as $b)
                                <option value="{{ $b }}" @selected($oldBuilding === $b)>{{ $b }}</option>
                            @endforeach
                            <option value="__other__" @selected($isOtherBuilding)>Lainnya</option>
                        </select>
                        <div class="building-other-wrap" id="building-other-wrap" style="{{ $isOtherBuilding ? 'display:block;' : '' }}">
                            <input type="text" id="building-other" name="building_other" value="{{ $isOtherBuilding ? $oldBuilding : '' }}" placeholder="Tulis nama bangunan / area lainnya">
                        </div>
                    </div>

                    <div class="field">
                        <label>Perusahaan <span class="req">*</span></label>
                        @php
                            $companyOptions = collect(config('companies', []))->unique()->sort()->values();
                            $oldCompany = old('company_name');
                            $isOtherCompany = filled($oldCompany) && !$companyOptions->contains($oldCompany);
                        @endphp
                        <select id="company-select" name="company_name" required>
                            <option value="">Pilih perusahaan</option>
                            @foreach($companyOptions as $company)
                                <option value="{{ $company }}" @selected($oldCompany === $company)>{{ $company }}</option>
                            @endforeach
                            <option value="__other__" @selected($isOtherCompany)>Lainnya</option>
                        </select>
                        <div class="building-other-wrap" id="company-other-wrap" style="{{ $isOtherCompany ? 'display:block;' : '' }}">
                            <input type="text" id="company-other" name="company_other" value="{{ $isOtherCompany ? $oldCompany : '' }}" placeholder="Tulis nama perusahaan lainnya">
                        </div>
                    </div>

                    <div class="grid2">
                        <div class="field">
                            <label>Nama Pelapor <span class="req">*</span></label>
                            <input type="text" name="reporter_name" value="{{ old('reporter_name') }}" placeholder="Nama lengkap" required>
                        </div>
                        <div class="field">
                            <label>Nomor WhatsApp</label>
                            <input type="tel" name="reporter_wa" value="{{ old('reporter_wa') }}" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>

                    <div class="grid2">
                        <div class="field">
                            <label>Jabatan</label>
                            <input type="text" name="job_title" value="{{ old('job_title') }}" placeholder="Contoh: Supervisor, Operator">
                        </div>
                        <div class="field" id="field-room" style="{{ in_array(old('type',''), ['receptionist','laundry']) ? '' : 'display:none' }}">
                            <label id="room-label">No. Kamar <span class="req" id="room-req">*</span></label>
                            <input type="text" name="room_number" id="room-input" value="{{ old('room_number') }}" placeholder="Contoh: 101">
                        </div>
                    </div>

                    <div class="field">
                        <label>Deskripsi Keluhan <span class="req">*</span></label>
                        <textarea name="description" id="desc-area" placeholder="Ceritakan masalah yang Anda alami secara detail..." required>{{ old('description') }}</textarea>
                    </div>

                    <div class="field">
                        <label>Upload Foto</label>
                        <label class="upload-box" for="photos-input" id="photo-upload-box">
                            <input type="file" id="photos-input" name="photos[]" accept=".jpg,.jpeg,.png,.webp,image/*" multiple>
                            <span class="upload-copy">
                                <i class="fas fa-camera"></i>
                                <strong>Klik atau drag foto ke sini</strong>
                                <span>Opsional. Maksimal 6 foto dengan format JPG, PNG, atau WEBP.</span>
                            </span>
                        </label>
                        <div id="photo-preview-grid" class="preview-grid" style="display:none;"></div>
                    </div>

                    <div class="actions">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim Laporan General Affair</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="ticket-strip">
            <i class="fas fa-qrcode"></i>
            <span>Setelah laporan General Affair dikirim, sistem akan membuat nomor tiket dan QR untuk cek status.</span>
        </div>
    </section>

    <section class="section" id="section-ticket">
        <div class="form-layout">
            <aside class="side-panel">
                <div class="side-kicker">Ticket center</div>
                <div class="side-title">Satu tempat untuk cek perkembangan tiket Human Resources dan General Affair.</div>
                <div class="side-list">
                    <div class="side-item"><i class="fas fa-ticket"></i><span>Masukkan nomor tiket yang diterima setelah laporan atau request dikirim.</span></div>
                    <div class="side-item"><i class="fas fa-arrows-rotate"></i><span>Status akan menampilkan progres penanganan dari tim terkait.</span></div>
                    <div class="side-item"><i class="fas fa-circle-info"></i><span>Saat ini data tiket Human Resources dan General Affair sudah aktif dan dapat dipantau dalam satu halaman.</span></div>
                </div>
            </aside>

            <div class="ticket-card">
                <div class="form-head">
                    <div class="form-mark"><i class="fas fa-magnifying-glass-location"></i></div>
                    <div>
                        <div class="form-title">Cek Status Tiket Human Resources / General Affair</div>
                        <div class="form-sub">Gunakan nomor tiket dari Human Resources atau General Affair untuk melihat status terakhir dalam satu halaman.</div>
                    </div>
                </div>

                <div class="ticket-search">
                    <input type="text" id="cek-input" placeholder="Contoh: HR-0001, RCP-0001, HKP-0001, LDY-0001" onkeydown="if(event.key==='Enter')cekTiket()">
                    <button type="button" class="btn btn-primary" onclick="cekTiket()"><i class="fas fa-search"></i> Cek Tiket</button>
                </div>

                <div class="ticket-loading" id="cek-loading">
                    <i class="fas fa-spinner fa-spin"></i> Mencari tiket...
                </div>

                <div class="ticket-result" id="cek-result">
                    <div id="cek-content"></div>
                </div>
            </div>
        </div>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/choices.js@11.2.1/public/assets/scripts/choices.min.js"></script>
<script>
const translations = {
    id: {},
    en: {
        'Form Layanan SEDIA': 'SEDIA Service Form',
        'Admin': 'Admin',
        'Pilih layanan': 'Select service',
        'Form satu halaman untuk kebutuhan Human Resources dan fasilitas.': 'One-page form for Human Resources requests and facility complaints.',
        'Mulai dari pilihan Human Resources atau General Affair, lalu isi form yang sesuai. Kedua layanan sudah aktif dan dapat digunakan langsung dari halaman ini.': 'Choose Human Resources or General Affair, then fill in the matching form. Both services are active and ready to use directly from this page.',
        'Human Resources': 'Human Resources',
        'Permintaan dan konsultasi karyawan': 'Employee requests and consultation',
        'Pengaduan fasilitas dan area kerja': 'Facility and workplace complaints',
        'Cek Tiket': 'Check Ticket',
        'Pantau status tiket Human Resources dan General Affair': 'Track Human Resources and General Affair ticket status',
        'Human Resources service': 'Human Resources service',
        'Kebutuhan karyawan masuk lewat jalur yang lebih rapi.': 'Employee requests are collected through a cleaner flow.',
        'Data pelapor dibuat ringkas agar Human Resources mudah mengenali konteks karyawan.': 'Reporter data is kept concise so Human Resources can understand the employee context quickly.',
        'Jenis layanan dipisahkan untuk surat, payroll, absensi, benefit, dan konsultasi.': 'Service types are separated for letters, payroll, attendance, benefits, and consultation.',
        'Lampiran disediakan untuk dokumen pendukung seperti foto, bukti, atau file administrasi.': 'Attachments are available for supporting documents such as photos, evidence, or administrative files.',
        'Form Layanan Human Resources': 'Human Resources Service Form',
        'Preview tampilan untuk pengajuan kebutuhan Human Resources karyawan.': 'UI preview for employee Human Resources requests.',
        'Nama Karyawan': 'Employee Name',
        'Nama lengkap': 'Full name',
        'NIK / ID Karyawan': 'Employee ID',
        'Contoh: SCM-00123': 'Example: SCM-00123',
        'Perusahaan': 'Company',
        'Departemen / Bagian': 'Department / Section',
        'Contoh: Produksi, Finance, HSE': 'Example: Production, Finance, HSE',
        'Jabatan': 'Position',
        'Contoh: Operator, Staff, Supervisor': 'Example: Operator, Staff, Supervisor',
        'Contoh: Supervisor, Operator': 'Example: Supervisor, Operator',
        'Nomor WhatsApp': 'WhatsApp Number',
        '08xxxxxxxxxx': '08xxxxxxxxxx',
        'Jenis Layanan Human Resources': 'Human Resources Service Type',
        'Tingkat Kebutuhan': 'Request Priority',
        'Normal': 'Normal',
        'Penting': 'Important',
        'Mendesak': 'Urgent',
        'Tanggal / Periode Terkait': 'Related Date / Period',
        'Contoh: April 2026 atau 20/04/2026': 'Example: April 2026 or 20/04/2026',
        'Email': 'Email',
        'nama@perusahaan.com': 'name@company.com',
        'Detail Permintaan': 'Request Details',
        'Tuliskan kebutuhan Human Resources secara jelas...': 'Write the Human Resources request clearly...',
        'Lampiran Pendukung': 'Supporting Attachment',
        'Klik atau drag lampiran ke sini': 'Click or drag attachments here',
        'Opsional. Bisa berupa foto atau dokumen pendukung.': 'Optional. Can be photos or supporting documents.',
        'Reset': 'Reset',
        'Kirim Laporan Human Resources': 'Submit Human Resources Report',
        'Setelah laporan Human Resources dikirim, sistem akan membuat nomor tiket dan QR untuk cek status.': 'After the Human Resources report is submitted, the system will create a ticket number and QR code for status checks.',
        'General Affair facility': 'General Affair facility',
        'Pengaduan fasilitas langsung masuk ke tiket General Affair.': 'Facility complaints go directly into General Affair tickets.',
        'Receptionist untuk fasilitas kamar dan kebutuhan penghuni.': 'Receptionist for room facilities and resident needs.',
        'Housekeeping untuk kebersihan area, toilet, sampah, dan lingkungan kerja.': 'Housekeeping for area cleanliness, toilets, waste, and the work environment.',
        'Laundry untuk laporan cucian tertukar, rusak, belum selesai, atau kurang jumlah.': 'Laundry for mixed-up, damaged, unfinished, or missing laundry reports.',
        'Form Pengaduan Fasilitas General Affair': 'General Affair Facility Complaint Form',
        'Isi data fasilitas agar laporan bisa ditangani oleh tim yang sesuai.': 'Fill in facility details so the right team can handle the report.',
        'Tipe Komplain': 'Complaint Type',
        'Bangunan / Area': 'Building / Area',
        'Tulis nama bangunan / area lainnya': 'Write another building / area name',
        'Tulis nama perusahaan lainnya': 'Write another company name',
        'Nama Pelapor': 'Reporter Name',
        'No. Kamar': 'Room No.',
        'Contoh: 101': 'Example: 101',
        'Deskripsi Keluhan': 'Complaint Description',
        'Ceritakan masalah yang Anda alami secara detail...': 'Describe the issue you experienced in detail...',
        'Upload Foto': 'Upload Photo',
        'Klik atau drag foto ke sini': 'Click or drag photos here',
        'Opsional. Maksimal 6 foto dengan format JPG, PNG, atau WEBP.': 'Optional. Maximum 6 photos in JPG, PNG, or WEBP format.',
        'Kirim Laporan General Affair': 'Submit General Affair Report',
        'Setelah laporan General Affair dikirim, sistem akan membuat nomor tiket dan QR untuk cek status.': 'After the General Affair report is submitted, the system will create a ticket number and QR code for status checks.',
        'Satu tempat untuk cek perkembangan tiket Human Resources dan General Affair.': 'One place to check Human Resources and General Affair ticket progress.',
        'Masukkan nomor tiket yang diterima setelah laporan atau request dikirim.': 'Enter the ticket number received after a report or request is submitted.',
        'Status akan menampilkan progres penanganan dari tim terkait.': 'The status shows handling progress from the related team.',
        'Saat ini data tiket Human Resources dan General Affair sudah aktif dan dapat dipantau dalam satu halaman.': 'Human Resources and General Affair ticket data are now active and can be monitored from one page.',
        'Cek Status Tiket Human Resources / General Affair': 'Check Human Resources / General Affair Ticket Status',
        'Gunakan nomor tiket dari Human Resources atau General Affair untuk melihat status terakhir dalam satu halaman.': 'Use a Human Resources or General Affair ticket number to view the latest status on one page.',
        'Contoh: HR-0001, RCP-0001, HKP-0001, LDY-0001': 'Example: HR-0001, RCP-0001, HKP-0001, LDY-0001',
        'Mencari tiket...': 'Searching ticket...',
        'Mohon perbaiki:': 'Please fix:',
        'Pilih perusahaan': 'Select company',
        'Pilih jenis layanan': 'Select service type',
        'Surat keterangan kerja': 'Employment certificate',
        'Payroll / slip gaji': 'Payroll / payslip',
        'Absensi, cuti, atau izin': 'Attendance, leave, or permission',
        'BPJS, asuransi, atau benefit': 'BPJS, insurance, or benefits',
        'Rekrutmen / onboarding': 'Recruitment / onboarding',
        'Konsultasi hubungan kerja': 'Employee relations consultation',
        'Lainnya': 'Other',
        'Pilih tipe komplain': 'Select complaint type',
        'Receptionist - Fasilitas Kamar': 'Receptionist - Room Facilities',
        'Housekeeping - Kebersihan Area': 'Housekeeping - Area Cleanliness',
        'Laundry - Laporan Cucian': 'Laundry - Laundry Report',
        'Pilih bangunan / area': 'Select building / area',
        'Layanan': 'Service',
        'Pelapor': 'Reporter',
        'Dilaporkan': 'Reported',
        'Selesai': 'Resolved',
        'Kamar': 'Room',
        'Catatan Tim:': 'Team Notes:',
        'Detail': 'Detail',
        'Overdue SLA': 'Overdue SLA',
        'Tiket tidak ditemukan.': 'Ticket not found.',
        'Gagal menghubungi server. Coba lagi.': 'Failed to contact server. Please try again.',
        'Foto': 'Photo',
        'Jelaskan masalah fasilitas kamar secara detail...': 'Describe the room facility issue in detail...',
        'Jelaskan masalah kebersihan yang ditemukan...': 'Describe the cleanliness issue found...',
        'Jelaskan masalah cucian seperti tertukar, rusak, kurang, atau belum selesai...': 'Describe the laundry issue, such as mixed-up, damaged, missing, or unfinished laundry...',
        'Data tidak ditemukan': 'No data found',
        'Tidak ada pilihan': 'No options available',
        'Cari atau pilih bangunan...': 'Search or select building...',
        'Ketik nama bangunan...': 'Type building name...',
        'Cari atau pilih perusahaan...': 'Search or select company...',
        'Ketik nama perusahaan...': 'Type company name...'
    },
    zh: {
        'Form Layanan SCM': 'SCM 服务表单',
        'Admin': '管理员',
        'Pilih layanan': '选择服务',
        'Form satu halaman untuk kebutuhan HR dan fasilitas.': '用于人力资源需求和设施投诉的一页表单。',
        'Mulai dari pilihan HR atau General Affair, lalu isi form yang sesuai. Kedua layanan sudah aktif dan dapat digunakan langsung dari halaman ini.': '先选择 HR 或 General Affair，然后填写相应表单。两项服务现已启用，并可直接在此页面使用。',
        'Permintaan dan konsultasi karyawan': '员工申请和咨询',
        'Pengaduan fasilitas dan area kerja': '设施和工作区域投诉',
        'Cek Tiket': '查询工单',
        'Pantau status tiket HR dan General Affair': '跟踪 HR 和 General Affair 工单状态',
        'Kebutuhan karyawan masuk lewat jalur yang lebih rapi.': '员工需求通过更清晰的流程提交。',
        'Data pelapor dibuat ringkas agar HR mudah mengenali konteks karyawan.': '提交人信息保持简洁，便于 HR 快速了解员工背景。',
        'Jenis layanan dipisahkan untuk surat, payroll, absensi, benefit, dan konsultasi.': '服务类型按证明、薪资、考勤、福利和咨询区分。',
        'Lampiran disediakan untuk dokumen pendukung seperti foto, bukti, atau file administrasi.': '可上传照片、凭证或行政文件等支持材料。',
        'Form Layanan HR': 'HR 服务表单',
        'Preview tampilan untuk pengajuan kebutuhan HR karyawan.': '员工 HR 申请的界面预览。',
        'Nama Karyawan': '员工姓名',
        'Nama lengkap': '姓名全称',
        'NIK / ID Karyawan': '员工编号',
        'Contoh: SCM-00123': '例如：SCM-00123',
        'Perusahaan': '公司',
        'Departemen / Bagian': '部门 / 科室',
        'Contoh: Produksi, Finance, HSE': '例如：生产、财务、HSE',
        'Jabatan': '职位',
        'Contoh: Operator, Staff, Supervisor': '例如：操作员、员工、主管',
        'Contoh: Supervisor, Operator': '例如：主管、操作员',
        'Nomor WhatsApp': 'WhatsApp 号码',
        '08xxxxxxxxxx': '08xxxxxxxxxx',
        'Jenis Layanan HR': 'HR 服务类型',
        'Tingkat Kebutuhan': '需求优先级',
        'Normal': '普通',
        'Penting': '重要',
        'Mendesak': '紧急',
        'Tanggal / Periode Terkait': '相关日期 / 期间',
        'Contoh: April 2026 atau 20/04/2026': '例如：2026 年 4 月或 20/04/2026',
        'Email': '电子邮箱',
        'nama@perusahaan.com': 'name@company.com',
        'Detail Permintaan': '申请详情',
        'Tuliskan kebutuhan HR secara jelas...': '请清楚填写 HR 需求...',
        'Lampiran Pendukung': '支持附件',
        'Klik atau drag lampiran ke sini': '点击或拖拽附件到这里',
        'Opsional. Bisa berupa foto atau dokumen pendukung.': '可选。可以是照片或支持文件。',
        'Reset': '重置',
        'Kirim Preview HR': '提交 HR 预览',
        'Setelah laporan Human Resources dikirim, sistem akan membuat nomor tiket dan QR untuk cek status.': '提交 Human Resources 报告后，系统会生成工单号和用于查询状态的二维码。',
        'Form HR ini baru tampilan UI. Penyimpanan data dan dashboard HR belum diaktifkan.': '此 HR 表单目前只是界面预览。数据保存和 HR 仪表板尚未启用。',
        'General Affair facility': 'General Affair facility',
        'Pengaduan fasilitas langsung masuk ke tiket General Affair.': '设施投诉会直接进入 General Affair 工单。',
        'Receptionist untuk fasilitas kamar dan kebutuhan penghuni.': 'Receptionist 用于房间设施和住户需求。',
        'Housekeeping untuk kebersihan area, toilet, sampah, dan lingkungan kerja.': 'Housekeeping 用于区域清洁、卫生间、垃圾和工作环境。',
        'Laundry untuk laporan cucian tertukar, rusak, belum selesai, atau kurang jumlah.': 'Laundry 用于衣物混淆、损坏、未完成或数量不足的报告。',
        'Form Pengaduan Fasilitas General Affair': 'General Affair 设施投诉表单',
        'Isi data fasilitas agar laporan bisa ditangani oleh tim yang sesuai.': '填写设施信息，以便相关团队处理报告。',
        'Tipe Komplain': '投诉类型',
        'Bangunan / Area': '建筑 / 区域',
        'Tulis nama bangunan / area lainnya': '填写其他建筑 / 区域名称',
        'Tulis nama perusahaan lainnya': '填写其他公司名称',
        'Nama Pelapor': '提交人姓名',
        'No. Kamar': '房间号',
        'Contoh: 101': '例如：101',
        'Deskripsi Keluhan': '投诉说明',
        'Ceritakan masalah yang Anda alami secara detail...': '请详细说明您遇到的问题...',
        'Upload Foto': '上传照片',
        'Klik atau drag foto ke sini': '点击或拖拽照片到这里',
        'Opsional. Maksimal 6 foto dengan format JPG, PNG, atau WEBP.': '可选。最多 6 张照片，格式为 JPG、PNG 或 WEBP。',
        'Kirim Laporan General Affair': '提交 General Affair 报告',
        'Setelah laporan General Affair dikirim, sistem akan membuat nomor tiket dan QR untuk cek status.': 'General Affair 报告提交后，系统会生成工单号和用于查询状态的二维码。',
        'Satu tempat untuk cek perkembangan tiket HR dan General Affair.': '在一个页面查询 HR 和 General Affair 工单进度。',
        'Masukkan nomor tiket yang diterima setelah laporan atau request dikirim.': '请输入报告或申请提交后收到的工单号。',
        'Status akan menampilkan progres penanganan dari tim terkait.': '状态会显示相关团队的处理进度。',
        'Saat ini data tiket HR dan General Affair sudah aktif dan dapat dipantau dalam satu halaman.': '当前 HR 和 General Affair 工单数据已启用，并可在同一页面中查看。',
        'Cek Status Tiket HR / General Affair': '查询 HR / General Affair 工单状态',
        'Gunakan nomor tiket dari HR atau General Affair untuk melihat status terakhir dalam satu halaman.': '使用 HR 或 General Affair 工单号，在同一页面查看最新状态。',
        'Contoh: HR-0001, RCP-0001, HKP-0001, LDY-0001': '例如：HR-0001、RCP-0001、HKP-0001、LDY-0001',
        'Mencari tiket...': '正在查询工单...',
        'Mohon perbaiki:': '请修正：',
        'Pilih perusahaan': '选择公司',
        'Pilih jenis layanan': '选择服务类型',
        'Surat keterangan kerja': '在职证明',
        'Payroll / slip gaji': '薪资 / 工资单',
        'Absensi, cuti, atau izin': '考勤、休假或请假',
        'BPJS, asuransi, atau benefit': 'BPJS、保险或福利',
        'Rekrutmen / onboarding': '招聘 / 入职',
        'Konsultasi hubungan kerja': '劳动关系咨询',
        'Lainnya': '其他',
        'Pilih tipe komplain': '选择投诉类型',
        'Receptionist - Fasilitas Kamar': 'Receptionist - 房间设施',
        'Housekeeping - Kebersihan Area': 'Housekeeping - 区域清洁',
        'Laundry - Laporan Cucian': 'Laundry - 洗衣报告',
        'Pilih bangunan / area': '选择建筑 / 区域',
        'Layanan': '服务',
        'Pelapor': '提交人',
        'Dilaporkan': '提交时间',
        'Selesai': '已完成',
        'Kamar': '房间',
        'Catatan Tim:': '团队备注：',
        'Detail': '详情',
        'Overdue SLA': 'SLA 超时',
        'Tiket tidak ditemukan.': '未找到工单。',
        'Gagal menghubungi server. Coba lagi.': '无法连接服务器。请重试。',
        'Foto': '照片',
        'Jelaskan masalah fasilitas kamar secara detail...': '请详细说明房间设施问题...',
        'Jelaskan masalah kebersihan yang ditemukan...': '请说明发现的清洁问题...',
        'Jelaskan masalah cucian seperti tertukar, rusak, kurang, atau belum selesai...': '请说明洗衣问题，例如混淆、损坏、缺少或未完成...',
        'Data tidak ditemukan': '未找到数据',
        'Tidak ada pilihan': '没有可选项',
        'Cari atau pilih bangunan...': '搜索或选择建筑...',
        'Ketik nama bangunan...': '输入建筑名称...',
        'Cari atau pilih perusahaan...': '搜索或选择公司...',
        'Ketik nama perusahaan...': '输入公司名称...'
    }
};

const textSources = new WeakMap();
let currentLang = 'id';

function translateText(source) {
    return translations[currentLang][source] || source;
}

function applyLanguage(lang) {
    currentLang = translations[lang] ? lang : 'id';
    document.documentElement.lang = currentLang === 'zh' ? 'zh-CN' : currentLang;
    document.title = translateText('Form Layanan SEDIA');

    document.querySelectorAll('.lang-btn').forEach((button) => {
        button.classList.toggle('active', button.dataset.lang === currentLang);
    });

    const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, {
        acceptNode(node) {
            const parent = node.parentElement;
            if (!parent || ['SCRIPT', 'STYLE'].includes(parent.tagName)) {
                return NodeFilter.FILTER_REJECT;
            }
            return node.nodeValue.trim() ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
        }
    });

    while (walker.nextNode()) {
        const node = walker.currentNode;
        if (!textSources.has(node)) {
            textSources.set(node, node.nodeValue.trim());
        }
        const source = textSources.get(node);
        const leading = node.nodeValue.match(/^\s*/)?.[0] || '';
        const trailing = node.nodeValue.match(/\s*$/)?.[0] || '';
        node.nodeValue = leading + translateText(source) + trailing;
    }

    document.querySelectorAll('[placeholder]').forEach((element) => {
        if (!element.dataset.i18nPlaceholderSource) {
            element.dataset.i18nPlaceholderSource = element.getAttribute('placeholder');
        }
        element.setAttribute('placeholder', translateText(element.dataset.i18nPlaceholderSource));
    });

    document.querySelectorAll('[aria-label]').forEach((element) => {
        if (!element.dataset.i18nAriaSource) {
            element.dataset.i18nAriaSource = element.getAttribute('aria-label');
        }
        element.setAttribute('aria-label', translateText(element.dataset.i18nAriaSource));
    });
}

function setLanguage(lang) {
    applyLanguage(lang);
}

function switchService(service) {
    document.querySelectorAll('.service-card').forEach((card) => {
        card.classList.toggle('active', card.dataset.service === service);
    });
    document.querySelectorAll('.section').forEach((section) => section.classList.remove('active'));
    document.getElementById('section-' + service).classList.add('active');
}

function toggleOther(selectId, wrapId, inputId) {
    const select = document.getElementById(selectId);
    const wrap = document.getElementById(wrapId);
    const input = document.getElementById(inputId);

    if (!select || !wrap || !input) return;

    const apply = () => {
        if (select.value === '__other__') {
            wrap.style.display = 'block';
            input.required = true;
        } else {
            wrap.style.display = 'none';
            input.required = false;
            input.value = '';
        }
    };

    apply();
    select.addEventListener('change', apply);
}

function onTypeChange(type) {
    const fieldRoom = document.getElementById('field-room');
    const roomInput = document.getElementById('room-input');
    const roomReq = document.getElementById('room-req');
    const descArea = document.getElementById('desc-area');

    const placeholders = {
        receptionist: 'Jelaskan masalah fasilitas kamar secara detail...',
        hk: 'Jelaskan masalah kebersihan yang ditemukan...',
        laundry: 'Jelaskan masalah cucian seperti tertukar, rusak, kurang, atau belum selesai...',
    };
    if (placeholders[type]) {
        descArea.dataset.i18nPlaceholderSource = placeholders[type];
        descArea.placeholder = translateText(placeholders[type]);
    }

    if (type === 'receptionist') {
        fieldRoom.style.display = '';
        roomInput.required = true;
        roomReq.textContent = '*';
    } else if (type === 'laundry') {
        fieldRoom.style.display = '';
        roomInput.required = false;
        roomReq.textContent = '';
    } else {
        fieldRoom.style.display = 'none';
        roomInput.required = false;
        roomInput.value = '';
    }
}

function formatFileSize(bytes) {
    if (bytes < 1024 * 1024) return `${Math.round(bytes / 1024)} KB`;
    return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
}

function renderPhotoPreviews(files) {
    const previewGrid = document.getElementById('photo-preview-grid');
    if (!previewGrid) return;

    if (!files || files.length === 0) {
        previewGrid.innerHTML = '';
        previewGrid.style.display = 'none';
        return;
    }

    previewGrid.style.display = 'grid';
    previewGrid.innerHTML = '';

    Array.from(files).forEach((file, index) => {
        const card = document.createElement('div');
        card.className = 'preview-card';

        const image = document.createElement('img');
        image.alt = `Preview foto ${index + 1}`;

        const meta = document.createElement('div');
        meta.className = 'preview-meta';
        meta.innerHTML = `<strong>${translateText('Foto')} ${index + 1}</strong><br>${file.name}<br>${formatFileSize(file.size)}`;

        card.appendChild(image);
        card.appendChild(meta);
        previewGrid.appendChild(card);

        const reader = new FileReader();
        reader.onload = (event) => {
            image.src = event.target?.result || '';
        };
        reader.readAsDataURL(file);
    });
}

async function cekTiket() {
    const input = document.getElementById('cek-input');
    const loading = document.getElementById('cek-loading');
    const result = document.getElementById('cek-result');
    const content = document.getElementById('cek-content');
    const ticket = input?.value.trim().toUpperCase();

    if (!ticket) {
        return;
    }

    loading.style.display = 'block';
    result.classList.remove('show');
    content.innerHTML = '';

    try {
        const response = await fetch('/api/cek-tiket?ticket=' + encodeURIComponent(ticket), {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        const data = await response.json();

        loading.style.display = 'none';
        result.classList.add('show');

        if (!data.found) {
            content.innerHTML = `<div class="ticket-empty"><i class="fas fa-circle-xmark"></i> ${translateText(data.message || 'Tiket tidak ditemukan.')}</div>`;
            return;
        }

        const ticketGroup = ticket.startsWith('HR-') ? 'Human Resources' : 'General Affair';
        const overdue = data.is_overdue ? `<span style="color:#dc2626;font-size:.78rem;font-weight:800;">${translateText('Overdue SLA')}</span>` : '';
        const room = data.room ? `<div><div class="ticket-label">${translateText('Kamar')}</div><div class="ticket-value">${data.room}</div></div>` : '';
        const adminNotes = data.admin_notes
            ? `<div class="ticket-desc" style="border-left:4px solid var(--teal);"><strong>${translateText('Catatan Tim:')}</strong> ${data.admin_notes}</div>`
            : '';

        content.innerHTML = `
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:14px;flex-wrap:wrap;">
                <div>
                    <div class="ticket-number">${data.ticket}</div>
                    <span class="status-badge" style="background:${data.status_color}1f;color:${data.status_color};">
                        <i class="fas fa-circle"></i> ${data.status_label}
                    </span>
                    ${overdue}
                </div>
                <a href="${data.url}" target="_blank" class="btn btn-secondary" style="padding:9px 13px;">
                    <i class="fas fa-up-right-from-square"></i> ${translateText('Detail')}
                </a>
            </div>
            <div class="ticket-grid">
                <div><div class="ticket-label">${translateText('Layanan')}</div><div class="ticket-value">${ticketGroup} - ${data.type}</div></div>
                <div><div class="ticket-label">${translateText('Pelapor')}</div><div class="ticket-value">${data.reporter || '-'}</div></div>
                <div><div class="ticket-label">${translateText('Dilaporkan')}</div><div class="ticket-value">${data.created_at || '-'}</div></div>
                <div><div class="ticket-label">${translateText('Bangunan / Area')}</div><div class="ticket-value">${data.building || '-'}</div></div>
                ${room}
                ${data.resolved_at ? `<div><div class="ticket-label">${translateText('Selesai')}</div><div class="ticket-value">${data.resolved_at}</div></div>` : ''}
            </div>
            <div class="ticket-desc">${data.description || '-'}</div>
            ${adminNotes}
        `;
    } catch (error) {
        loading.style.display = 'none';
        result.classList.add('show');
        content.innerHTML = `<div class="ticket-empty"><i class="fas fa-triangle-exclamation"></i> ${translateText('Gagal menghubungi server. Coba lagi.')}</div>`;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const oldType = document.getElementById('type-select')?.value;
    const hasServerErrors = @json($errors->any());
    if (oldType || hasServerErrors) {
        switchService('ga');
        if (oldType) onTypeChange(oldType);
    }

    const enhanceSelect = (id, placeholder, searchPlaceholder) => {
        const select = document.getElementById(id);
        if (!select || typeof Choices === 'undefined') return;

        new Choices(select, {
            searchEnabled: true,
            shouldSort: false,
            itemSelectText: '',
            placeholder: true,
            placeholderValue: translateText(placeholder),
            searchPlaceholderValue: translateText(searchPlaceholder),
            noResultsText: translateText('Data tidak ditemukan'),
            noChoicesText: translateText('Tidak ada pilihan'),
            searchResultLimit: 999,
            fuseOptions: { threshold: 0.3, minMatchCharLength: 1, keys: ['label', 'value'] },
        });
    };

    enhanceSelect('building-select', 'Cari atau pilih bangunan...', 'Ketik nama bangunan...');
    enhanceSelect('company-select', 'Cari atau pilih perusahaan...', 'Ketik nama perusahaan...');
    toggleOther('building-select', 'building-other-wrap', 'building-other');
    toggleOther('company-select', 'company-other-wrap', 'company-other');

    const photoInput = document.getElementById('photos-input');
    const uploadBox = document.getElementById('photo-upload-box');
    if (photoInput) {
        photoInput.addEventListener('change', (event) => renderPhotoPreviews(event.target.files));
    }
    if (uploadBox) {
        ['dragenter', 'dragover'].forEach((eventName) => {
            uploadBox.addEventListener(eventName, (event) => {
                event.preventDefault();
                uploadBox.classList.add('is-dragover');
            });
        });
        ['dragleave', 'drop'].forEach((eventName) => {
            uploadBox.addEventListener(eventName, (event) => {
                event.preventDefault();
                uploadBox.classList.remove('is-dragover');
            });
        });
    }

    applyLanguage('id');
});
</script>
</body>
</html>
