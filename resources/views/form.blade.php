<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0d6efd">
    <title>Form Komplain SCM</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js@11.2.1/public/assets/styles/choices.min.css">
    <style>
        :root {
            --primary:#0d6efd; --primary-d:#0a58ca; --primary-l:#e7f0ff;
            --bg:#f0f4fc; --panel:#fff; --line:#dde6f5;
            --text:#1a2340; --muted:#6b7a99;
            --green:#198754; --green-l:#d1e7dd;
            --red:#dc3545; --red-l:#f8d7da;
            --shadow:0 8px 40px rgba(13,110,253,.10);
        }
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Figtree',sans-serif;background:linear-gradient(135deg,#e8eefa,#f0f4fc,#e4eeff);min-height:100vh;color:var(--text);}
        .topbar{background:#fff;border-bottom:1px solid var(--line);padding:0 20px;height:62px;display:flex;align-items:center;justify-content:space-between;box-shadow:0 1px 6px rgba(13,110,253,.06);position:sticky;top:0;z-index:100;}
        .brand{display:flex;align-items:center;gap:12px;min-width:0;}
        .brand-logo{width:42px;height:42px;object-fit:contain;flex-shrink:0;}
        .brand-copy{line-height:1.1;min-width:0;}
        .brand .name{font-weight:800;font-size:.95rem;}
        .brand .sub{font-size:.68rem;color:var(--muted);}
        .btn-login{padding:7px 16px;background:var(--primary);color:#fff;border:none;border-radius:8px;font:inherit;font-size:.82rem;font-weight:600;text-decoration:none;cursor:pointer;}
        .btn-login:hover{background:var(--primary-d);color:#fff;}
        .page-tabs{display:flex;background:#fff;border-bottom:2px solid var(--line);overflow-x:auto;}
        .page-tab{padding:12px 24px;font-size:.88rem;font-weight:600;color:var(--muted);cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-2px;white-space:nowrap;transition:color .15s;}
        .page-tab.active{color:var(--primary);border-bottom-color:var(--primary);}
        .page-tab:hover{color:var(--primary);}
        .section{display:none;}.section.active{display:block;}
        .container{width:min(700px,calc(100% - 28px));margin:0 auto;padding:28px 0 60px;}
        .form-card{background:var(--panel);border:1px solid var(--line);border-radius:18px;padding:26px;box-shadow:var(--shadow);}
        .form-heading{display:flex;align-items:center;gap:12px;margin-bottom:12px;}
        .form-heading-logo{width:52px;height:52px;object-fit:contain;flex-shrink:0;}
        .form-title{font-size:1.05rem;font-weight:700;margin-bottom:4px;}
        .form-sub{font-size:.82rem;color:var(--muted);margin-bottom:20px;}
        .field{display:grid;gap:5px;margin-bottom:15px;}
        .field label{font-size:.83rem;font-weight:600;color:var(--text);}
        .field label .req{color:var(--red);}
        .field input,.field select,.field textarea{width:100%;padding:10px 13px;border:1.5px solid var(--line);border-radius:10px;background:#fafcff;font:inherit;font-size:.9rem;color:var(--text);transition:border-color .18s,box-shadow .18s;}
        .field input:focus,.field select:focus,.field textarea:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(13,110,253,.1);background:#fff;}
        .field textarea{min-height:120px;resize:vertical;}
        .field .hint{font-size:.75rem;color:var(--muted);}
        .choices{margin-bottom:0;}
        .choices__inner{
            min-height:auto;
            padding:4px 10px !important;
            border:1.5px solid var(--line) !important;
            border-radius:10px !important;
            background:#fafcff !important;
            font-size:.9rem;
        }
        .is-focused .choices__inner,
        .is-open .choices__inner{
            border-color:var(--primary) !important;
            box-shadow:0 0 0 3px rgba(13,110,253,.1);
            background:#fff !important;
        }
        .choices__input{
            background:transparent !important;
            margin-bottom:0 !important;
            font-size:.9rem !important;
        }
        .choices__list--dropdown,
        .choices__list[aria-expanded]{
            border:1px solid var(--line) !important;
            border-radius:10px !important;
            box-shadow:0 10px 24px rgba(13,110,253,.08);
        }
        .choices__list--dropdown .choices__item--selectable.is-highlighted,
        .choices__list[aria-expanded] .choices__item--selectable.is-highlighted{
            background:#eef5ff !important;
            color:#0d6efd !important;
        }
        .building-other-wrap{display:none;margin-top:8px;}
        .photo-upload-box{
            position:relative;
            border:2px dashed #c8d3e3;
            border-radius:16px;
            background:linear-gradient(180deg,#fbfdff 0%,#f4f8ff 100%);
            min-height:126px;
            display:flex;
            align-items:center;
            justify-content:center;
            text-align:center;
            padding:20px;
            cursor:pointer;
            transition:border-color .18s ease, background .18s ease, box-shadow .18s ease;
        }
        .photo-upload-box:hover,
        .photo-upload-box.is-dragover{
            border-color:var(--primary);
            background:linear-gradient(180deg,#f8fbff 0%,#edf4ff 100%);
            box-shadow:0 0 0 4px rgba(13,110,253,.08);
        }
        .photo-upload-box input[type=file]{
            position:absolute;
            inset:0;
            opacity:0;
            cursor:pointer;
        }
        .photo-upload-copy{
            display:grid;
            gap:8px;
            pointer-events:none;
        }
        .photo-upload-icon{
            width:44px;
            height:44px;
            margin:0 auto;
            border-radius:999px;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#e8f1ff;
            color:var(--primary);
            font-size:1.1rem;
        }
        .photo-upload-title{
            font-size:1rem;
            font-weight:700;
            color:#355070;
        }
        .photo-upload-sub{
            font-size:.78rem;
            color:var(--muted);
        }
        .photo-preview-grid{
            display:grid;
            grid-template-columns:repeat(auto-fill,minmax(110px,1fr));
            gap:12px;
            margin-top:12px;
        }
        .photo-preview-card{
            border:1px solid var(--line);
            border-radius:14px;
            overflow:hidden;
            background:#fff;
            box-shadow:0 8px 24px rgba(13,110,253,.08);
        }
        .photo-preview-card img{
            width:100%;
            height:100px;
            object-fit:cover;
            display:block;
        }
        .photo-preview-meta{
            padding:8px 10px;
            font-size:.72rem;
            color:var(--muted);
            line-height:1.35;
            background:#f8fbff;
        }
        .row2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
        .alert-err{padding:12px 15px;border-radius:10px;background:var(--red-l);border:1px solid #f1aeb5;color:#842029;font-size:.85rem;margin-bottom:14px;}
        .btn-submit{width:100%;padding:13px;background:linear-gradient(135deg,var(--primary),var(--primary-d));color:#fff;border:none;border-radius:11px;font:inherit;font-size:.95rem;font-weight:700;cursor:pointer;box-shadow:0 6px 20px rgba(13,110,253,.28);transition:filter .2s;}
        .btn-submit:hover{filter:brightness(1.06);}
        .cek-card{background:var(--panel);border:1px solid var(--line);border-radius:18px;padding:26px;box-shadow:var(--shadow);}
        .ticket-result{margin-top:16px;padding:16px;border-radius:12px;border:1px solid var(--line);background:#fafcff;display:none;}
        .ticket-result.show{display:block;}
        .ticket-result .tr-ticket{font-size:1.3rem;font-weight:800;color:#0a1628;letter-spacing:-.5px;}
        .ticket-result .tr-badge{display:inline-block;padding:4px 12px;border-radius:999px;font-size:.78rem;font-weight:700;margin-top:6px;}
        .ticket-result .tr-row{display:flex;gap:8px;margin-top:10px;font-size:.84rem;flex-wrap:wrap;}
        .ticket-result .tr-label{color:var(--muted);font-size:.75rem;font-weight:700;text-transform:uppercase;margin-bottom:2px;}
        .ticket-result .tr-val{font-size:.88rem;}
        .tr-not-found{color:var(--red);font-size:.88rem;padding:10px 0;}
        select option.other-opt{font-style:italic;color:#0d6efd;}
        @media(max-width:520px){.row2{grid-template-columns:1fr;}.form-card,.cek-card{padding:16px;}.container{padding:18px 0 40px;}.page-tab{padding:10px 14px;font-size:.82rem;}.brand .sub{display:none;}.form-heading{align-items:flex-start;}}
    </style>
</head>
<body>

<header class="topbar">
    <div class="brand">
        <img src="{{ asset('icons/GA-SCM.png') }}" alt="GA SCM Logo" class="brand-logo">
        <div class="brand-copy"><div class="name">SCM</div><div class="sub">Complaint Management</div></div>
    </div>
    <div style="display:flex;gap:8px;align-items:center;">
        <a href="{{ route('login') }}" class="btn-login"><i class="fas fa-sign-in-alt mr-1"></i> Login Admin</a>
    </div>
</header>

<div class="page-tabs" id="pageTabs">
    <div class="page-tab active" onclick="switchTab('form',this)">
        <i class="fas fa-edit mr-1"></i> Form Komplain
    </div>
    <div class="page-tab" onclick="switchTab('cek',this)">
        <i class="fas fa-search mr-1"></i> Cek Status Tiket
    </div>
</div>

<div class="section active" id="section-form">
<div class="container">

    @if($errors->any())
    <div class="alert-err">
        <strong><i class="fas fa-exclamation-triangle mr-1"></i> Mohon perbaiki:</strong><br>
        @foreach($errors->all() as $e)- {{ $e }}<br>@endforeach
    </div>
    @endif

    <div class="form-card">
        <div class="form-heading">
            <img src="{{ asset('icons/GA-SCM.png') }}" alt="GA SCM Logo" class="form-heading-logo">
            <div>
                <div class="form-title">Form Komplain SCM</div>
                <div class="form-sub">Isi semua data dengan benar agar laporan dapat ditangani dengan cepat.</div>
            </div>
        </div>

        <form method="POST" action="{{ route('complaint.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="field">
                <label>Tipe Komplain <span class="req">*</span></label>
                <select name="type" id="type-select" required onchange="onTypeChange(this.value)">
                    <option value="">-- Pilih Tipe --</option>
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
                    <option value="">-- Pilih Bangunan / Area --</option>
                    @foreach($buildingOptions as $b)
                        <option value="{{ $b }}" @selected($oldBuilding === $b)>{{ $b }}</option>
                    @endforeach
                    <option value="__other__" @selected($isOtherBuilding)>Lainnya</option>
                </select>
                <div class="building-other-wrap" id="building-other-wrap" style="{{ $isOtherBuilding ? 'display:block;' : '' }}">
                    <input type="text"
                           id="building-other"
                           name="building_other"
                           value="{{ $isOtherBuilding ? $oldBuilding : '' }}"
                           placeholder="Tulis nama bangunan / area lainnya">
                </div>
                <span class="hint">Pilih bangunan dari daftar. Jika belum ada, pilih <strong>Lainnya</strong> lalu isi manual.</span>
            </div>

            <div class="field">
                <label>Perusahaan <span class="req">*</span></label>
                @php
                    $companyOptions = collect(config('companies', []))->unique()->sort()->values();
                    $oldCompany = old('company_name');
                    $isOtherCompany = filled($oldCompany) && !$companyOptions->contains($oldCompany);
                @endphp
                <select id="company-select" name="company_name" required>
                    <option value="">-- Pilih Perusahaan --</option>
                    @foreach($companyOptions as $company)
                        <option value="{{ $company }}" @selected($oldCompany === $company)>{{ $company }}</option>
                    @endforeach
                    <option value="__other__" @selected($isOtherCompany)>Lainnya</option>
                </select>
                <div class="building-other-wrap" id="company-other-wrap" style="{{ $isOtherCompany ? 'display:block;' : '' }}">
                    <input type="text"
                           id="company-other"
                           name="company_other"
                           value="{{ $isOtherCompany ? $oldCompany : '' }}"
                           placeholder="Tulis nama perusahaan lainnya">
                </div>
                <span class="hint">Cari dan pilih perusahaan. Jika belum ada, pilih <strong>Lainnya</strong> lalu isi manual.</span>
            </div>

            <div class="field">
                <label>Jabatan</label>
                <input type="text"
                       name="job_title"
                       value="{{ old('job_title') }}"
                       placeholder="Contoh: Supervisor, Operator, Admin Site">
                <span class="hint">Isi jabatan pelapor jika diperlukan.</span>
            </div>

            <div class="field" id="field-room" style="{{ in_array(old('type',''), ['receptionist','laundry']) ? '' : 'display:none' }}">
                <label id="room-label">No. Kamar <span class="req" id="room-req">*</span></label>
                <input type="text" name="room_number" id="room-input"
                       value="{{ old('room_number') }}"
                       placeholder="Contoh: 101">
            </div>

            <div class="row2">
                <div class="field">
                    <label>Nama Pelapor <span class="req">*</span></label>
                    <input type="text" name="reporter_name" value="{{ old('reporter_name') }}"
                           placeholder="Nama lengkap" required>
                </div>
                <div class="field">
                    <label>Nomor WhatsApp</label>
                    <input type="tel" name="reporter_wa" value="{{ old('reporter_wa') }}"
                           placeholder="08xxxxxxxxxx">
                </div>
            </div>

            <div class="field">
                <label>Deskripsi Keluhan <span class="req">*</span></label>
                <textarea name="description" id="desc-area"
                          placeholder="Ceritakan masalah yang Anda alami secara detail..."
                          required>{{ old('description') }}</textarea>
                <span class="hint"><i class="fas fa-info-circle mr-1"></i> Cukup detailkan masalah yang ada.</span>
            </div>

            <div class="field">
                <label>Upload Foto</label>
                <label class="photo-upload-box" for="photos-input" id="photo-upload-box">
                    <input type="file" id="photos-input" name="photos[]" accept=".jpg,.jpeg,.png,.webp,image/*" multiple>
                    <span class="photo-upload-copy">
                        <span class="photo-upload-icon"><i class="fas fa-camera"></i></span>
                        <span class="photo-upload-title">Klik atau drag foto ke sini</span>
                        <span class="photo-upload-sub">Hanya foto. Maksimal 6 file dengan format JPG, PNG, atau WEBP.</span>
                    </span>
                </label>
                <span class="hint">Opsional. Bisa upload lebih dari satu foto, maksimal 6 file.</span>
                <div id="photo-preview-grid" class="photo-preview-grid" style="display:none;"></div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-paper-plane mr-2"></i> Kirim Laporan
            </button>
        </form>
    </div>

</div>
</div>

<div class="section" id="section-cek">
<div class="container">
    <div class="cek-card">
        <div class="form-title"><i class="fas fa-search-location mr-2 text-primary"></i>Cek Status Tiket</div>
        <div class="form-sub">Masukkan nomor tiket yang Anda terima setelah mengirimkan laporan.</div>

        <div style="display:flex;gap:10px;margin-bottom:4px;">
            <input type="text" id="cek-input" placeholder="Contoh: RCP-0001"
                   style="flex:1;padding:10px 14px;border:1.5px solid var(--line);border-radius:10px;font:inherit;font-size:.9rem;"
                   onkeydown="if(event.key==='Enter')cekTiket()">
            <button onclick="cekTiket()" class="btn-submit" style="width:auto;padding:10px 22px;">
                <i class="fas fa-search mr-1"></i> Cek
            </button>
        </div>
        <div id="cek-loading" style="display:none;font-size:.82rem;color:var(--muted);padding:8px 0;">
            <i class="fas fa-spinner fa-spin mr-1"></i> Mencari tiket...
        </div>

        <div class="ticket-result" id="cek-result">
            <div id="cek-content"></div>
        </div>
    </div>

    <div style="margin-top:18px;padding:16px;background:rgba(255,255,255,.7);border-radius:14px;border:1px solid var(--line);font-size:.82rem;color:var(--muted);">
        <i class="fas fa-qrcode mr-2 text-primary"></i>
        <strong>Tips:</strong> Setelah mengirim laporan, Anda akan mendapatkan QR Code. Scan QR tersebut untuk langsung mengecek status tiket kapan saja.
    </div>
</div>
</div>

<footer style="text-align:center;padding:18px 16px;font-size:.76rem;color:#aaa;">
    &copy; {{ date('Y') }} PT. Sulawesi Cahaya Mineral &mdash; SCM v2.0
</footer>

@if(request('tab') === 'cek')
<script>
document.addEventListener('DOMContentLoaded', () => {
    switchTab('cek', document.querySelectorAll('.page-tab')[1]);
});
</script>
@endif

<script src="https://cdn.jsdelivr.net/npm/choices.js@11.2.1/public/assets/scripts/choices.min.js"></script>
<script>
function switchTab(id, el) {
    document.querySelectorAll('.page-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('section-' + id).classList.add('active');
}

function toggleBuildingOther(value) {
    const otherWrap = document.getElementById('building-other-wrap');
    const otherInput = document.getElementById('building-other');

    if (!otherWrap || !otherInput) return;

    if (value === '__other__') {
        otherWrap.style.display = 'block';
        otherInput.required = true;
        otherInput.focus();
    } else {
        otherWrap.style.display = 'none';
        otherInput.required = false;
        otherInput.value = '';
    }
}

function toggleCompanyOther(value) {
    const otherWrap = document.getElementById('company-other-wrap');
    const otherInput = document.getElementById('company-other');

    if (!otherWrap || !otherInput) return;

    if (value === '__other__') {
        otherWrap.style.display = 'block';
        otherInput.required = true;
    } else {
        otherWrap.style.display = 'none';
        otherInput.required = false;
        otherInput.value = '';
    }
}

function onTypeChange(type) {
    const fieldRoom = document.getElementById('field-room');
    const roomInput = document.getElementById('room-input');
    const roomReq = document.getElementById('room-req');
    const descArea = document.getElementById('desc-area');

    const placeholders = {
        receptionist: 'Jelaskan masalah kamar ',
        hk: 'Jelaskan masalah kebersihan yang ditemukan (sprei, lantai, sampah, toilet, dll)...',
        laundry: 'Jelaskan masalah cucian (belum selesai, tertukar, rusak, jumlah kurang, dll)...',
    };
    if (placeholders[type]) descArea.placeholder = placeholders[type];

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
    }
}

function formatFileSize(bytes) {
    if (bytes < 1024 * 1024) {
        return `${Math.round(bytes / 1024)} KB`;
    }

    return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
}

function renderPhotoPreviews(files) {
    const previewGrid = document.getElementById('photo-preview-grid');

    if (!previewGrid) {
        return;
    }

    if (!files || files.length === 0) {
        previewGrid.innerHTML = '';
        previewGrid.style.display = 'none';
        return;
    }

    previewGrid.style.display = 'grid';
    previewGrid.innerHTML = '';

    Array.from(files).forEach((file, index) => {
        const card = document.createElement('div');
        card.className = 'photo-preview-card';

        const image = document.createElement('img');
        image.alt = `Preview foto ${index + 1}`;

        const meta = document.createElement('div');
        meta.className = 'photo-preview-meta';
        meta.innerHTML = `<strong>Foto ${index + 1}</strong><br>${file.name}<br>${formatFileSize(file.size)}`;

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

document.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('type-select');
    if (sel.value) onTypeChange(sel.value);

    const buildingSelect = document.getElementById('building-select');
    if (buildingSelect) {
        new Choices(buildingSelect, {
            searchEnabled: true,
            shouldSort: false,
            itemSelectText: '',
            placeholder: true,
            placeholderValue: 'Cari atau pilih nama bangunan...',
            searchPlaceholderValue: 'Ketik nama bangunan...',
            noResultsText: 'Bangunan tidak ditemukan',
            noChoicesText: 'Tidak ada pilihan bangunan',
            searchResultLimit: 999,
            fuseOptions: { threshold: 0.3, minMatchCharLength: 1, keys: ['label', 'value'] },
        });

        toggleBuildingOther(buildingSelect.value);
        buildingSelect.addEventListener('change', () => toggleBuildingOther(buildingSelect.value));
    }

    const companySelect = document.getElementById('company-select');
    if (companySelect) {
        new Choices(companySelect, {
            searchEnabled: true,
            shouldSort: false,
            itemSelectText: '',
            placeholder: true,
            placeholderValue: 'Cari atau pilih perusahaan...',
            searchPlaceholderValue: 'Ketik nama perusahaan...',
            noResultsText: 'Perusahaan tidak ditemukan',
            noChoicesText: 'Tidak ada pilihan perusahaan',
            searchResultLimit: 999,
            fuseOptions: { threshold: 0.3, minMatchCharLength: 1, keys: ['label', 'value'] },
        });

        toggleCompanyOther(companySelect.value);
        companySelect.addEventListener('change', () => toggleCompanyOther(companySelect.value));
    }

    const photoInput = document.getElementById('photos-input');
    const photoUploadBox = document.getElementById('photo-upload-box');
    if (photoInput) {
        photoInput.addEventListener('change', (event) => {
            renderPhotoPreviews(event.target.files);
        });
    }

    if (photoUploadBox) {
        ['dragenter', 'dragover'].forEach((eventName) => {
            photoUploadBox.addEventListener(eventName, (event) => {
                event.preventDefault();
                photoUploadBox.classList.add('is-dragover');
            });
        });

        ['dragleave', 'drop'].forEach((eventName) => {
            photoUploadBox.addEventListener(eventName, (event) => {
                event.preventDefault();
                photoUploadBox.classList.remove('is-dragover');
            });
        });
    }
});

async function cekTiket() {
    const val = document.getElementById('cek-input').value.trim();
    if (!val) return;

    document.getElementById('cek-loading').style.display = 'block';
    document.getElementById('cek-result').classList.remove('show');

    try {
        const res = await fetch('/api/cek-tiket?ticket=' + encodeURIComponent(val), {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        const data = await res.json();

        document.getElementById('cek-loading').style.display = 'none';
        const resultEl = document.getElementById('cek-result');
        resultEl.classList.add('show');

        if (!data.found) {
            document.getElementById('cek-content').innerHTML =
                `<div class="tr-not-found"><i class="fas fa-times-circle mr-2"></i>${data.message}</div>`;
            return;
        }

        const overdueHtml = data.is_overdue
            ? `<span style="color:#dc3545;font-size:.75rem;font-weight:700;"> Warning Overdue SLA</span>` : '';

        document.getElementById('cek-content').innerHTML = `
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                <div>
                    <div class="tr-ticket">${data.ticket}</div>
                    <span class="tr-badge" style="background:${data.status_color}22;color:${data.status_color};">${data.status_label}</span>
                    ${overdueHtml}
                </div>
                <a href="${data.url}" target="_blank"
                   style="padding:7px 14px;background:var(--primary-l);color:var(--primary);border-radius:8px;font-size:.82rem;font-weight:600;text-decoration:none;">
                   Detail lengkap ->
                </a>
            </div>
            <div class="tr-row" style="margin-top:14px;">
                <div style="min-width:120px;">
                    <div class="tr-label">Tipe</div>
                    <div class="tr-val">${data.type}</div>
                </div>
                ${data.building ? `<div style="min-width:120px;"><div class="tr-label">Bangunan</div><div class="tr-val">${data.building}</div></div>` : ''}
                ${data.room ? `<div style="min-width:80px;"><div class="tr-label">Kamar</div><div class="tr-val">${data.room}</div></div>` : ''}
                <div style="min-width:130px;">
                    <div class="tr-label">Dilaporkan</div>
                    <div class="tr-val">${data.created_at}</div>
                </div>
                ${data.resolved_at ? `<div style="min-width:130px;"><div class="tr-label">Diselesaikan</div><div class="tr-val" style="color:#198754;">${data.resolved_at}</div></div>` : ''}
            </div>
            <div style="margin-top:12px;padding:10px 12px;background:#f8f9fa;border-radius:8px;font-size:.84rem;color:#495057;line-height:1.5;">
                ${data.description}
            </div>
            ${data.admin_notes ? `<div style="margin-top:10px;padding:10px 12px;background:#eff6ff;border-radius:8px;border-left:3px solid #0d6efd;font-size:.83rem;"><strong>Catatan Tim:</strong> ${data.admin_notes}</div>` : ''}
        `;
    } catch (e) {
        document.getElementById('cek-loading').style.display = 'none';
        document.getElementById('cek-content').innerHTML =
            '<div class="tr-not-found"><i class="fas fa-exclamation-triangle mr-2"></i>Gagal menghubungi server. Coba lagi.</div>';
        document.getElementById('cek-result').classList.add('show');
    }
}
</script>
</body>
</html>
