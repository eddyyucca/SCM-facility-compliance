<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0d6efd">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="SCM">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icons/icon-192.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">
    <title>@yield('title', 'SCM') â€” Complaint Management</title>

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700" rel="stylesheet" />
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    {{-- AdminLTE --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
    {{-- Flatpickr --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        body { font-family: 'Figtree', sans-serif !important; background-color: #eef2f7 !important; }

        /* â”€â”€ Brand / Sidebar â”€â”€ */
        .main-sidebar { background: #0a1628 !important; }
        .brand-link    { background: #061020 !important; border-bottom: 1px solid rgba(255,255,255,.06) !important; }
        .brand-link .brand-text { color: #fff !important; font-weight: 700 !important; font-size: 1rem !important; }
        .brand-link:hover { background: #061020 !important; }
        .brand-logo-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }
        .brand-logo-image {
            width: 42px;
            height: 42px;
            object-fit: contain;
            flex-shrink: 0;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,.22));
        }
        .brand-logo-copy {
            min-width: 0;
            line-height: 1.1;
        }
        .brand-logo-copy strong {
            display: block;
            color: #fff;
            font-size: .92rem;
            font-weight: 700;
        }
        .brand-logo-copy span {
            display: block;
            color: rgba(255,255,255,.6);
            font-size: .68rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .main-footer {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%) !important;
            border-top: 1px solid #e3eaf4 !important;
            padding: 14px 24px !important;
        }
        .footer-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }
        .footer-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .footer-brand img {
            width: 36px;
            height: 36px;
            object-fit: contain;
        }
        .footer-brand strong {
            display: block;
            font-size: .86rem;
            color: #18263f;
        }
        .footer-brand span {
            display: block;
            font-size: .76rem;
            color: #7a8797;
        }
        .footer-meta {
            text-align: right;
            font-size: .76rem;
            color: #97a3b3;
        }

        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active,
        [class*="sidebar-dark"] .nav-treeview > .nav-item > .nav-link.active {
            background: rgba(13,110,253,.25) !important;
            color: #fff !important;
        }
        [class*="sidebar-dark"] .nav-sidebar .nav-link { color: rgba(255,255,255,.68) !important; }
        [class*="sidebar-dark"] .nav-sidebar .nav-link:hover { background: rgba(255,255,255,.07) !important; color: #fff !important; }
        [class*="sidebar-dark"] .nav-sidebar .nav-link i { color: rgba(255,255,255,.45) !important; }
        [class*="sidebar-dark"] .nav-sidebar .nav-link.active i { color: #74b4ff !important; }
        .nav-sidebar .nav-header { color: rgba(255,255,255,.3) !important; font-size: .68rem !important; }

        /* User panel at bottom */
        .sidebar-footer { padding: 12px 16px; border-top: 1px solid rgba(255,255,255,.07); }
        .sidebar-footer .user-name { color: #fff; font-weight: 600; font-size: .85rem; }
        .sidebar-footer .user-role {
            display: inline-block; margin-top: 3px; padding: 2px 9px;
            border-radius: 999px; background: rgba(13,110,253,.35);
            color: #74b4ff; font-size: .7rem; font-weight: 700;
        }
        .sidebar-footer .btn-logout {
            display: block; margin-top: 10px; padding: 7px;
            border: 1px solid rgba(255,255,255,.18); border-radius: 8px;
            background: transparent; color: rgba(255,255,255,.6);
            font: inherit; font-size: .82rem; cursor: pointer; text-align: center;
            transition: background .15s;
        }
        .sidebar-footer .btn-logout:hover { background: rgba(255,255,255,.08); color: #fff; }

        /* â”€â”€ Navbar â”€â”€ */
        .main-header.navbar {
            background: #fff !important;
            border-bottom: 1px solid #e3eaf4 !important;
            box-shadow: 0 1px 8px rgba(0,0,0,.05) !important;
        }
        .main-header .navbar-brand { color: #0a1628 !important; font-weight: 700; }
        .nav-item .nav-link { color: #4a5568 !important; }

        /* â”€â”€ Notification bell â”€â”€ */
        #notif-bell { position: relative; }
        #notif-bell .notif-count {
            position: absolute; top: 2px; right: 2px;
            background: #dc3545; color: #fff;
            font-size: .62rem; font-weight: 700;
            min-width: 16px; height: 16px;
            border-radius: 999px; display: none;
            align-items: center; justify-content: center;
            padding: 0 3px; line-height: 1;
        }
        .notif-item-row {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 16px; border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
        }
        .notif-item-row:hover { background: #f5f9ff; }
        .notif-item-icon {
            width: 32px; height: 32px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: .85rem; flex-shrink: 0;
        }
        .notif-empty { padding: 16px; text-align: center; color: #aaa; font-size: .85rem; }

        /* Sound toggle */
        #sound-toggle {
            background: none; border: none; cursor: pointer;
            color: #4a5568; font-size: 1rem; padding: 4px 8px;
            border-radius: 6px; transition: background .15s;
        }
        #sound-toggle:hover { background: #f0f4f8; }
        #sound-toggle.muted { color: #aaa; }

        /* â”€â”€ Content area â”€â”€ */
        .content-wrapper { background: #eef2f7 !important; }
        .content-header { padding: 16px 24px 0 !important; }
        .content-header h1 { font-size: 1.25rem !important; font-weight: 700 !important; }
        .content { padding: 16px 24px 24px !important; }

        /* â”€â”€ Cards â”€â”€ */
        .card {
            border: none !important;
            border-radius: 14px !important;
            box-shadow: 0 2px 12px rgba(0,0,0,.07) !important;
        }
        .card-header { border-radius: 14px 14px 0 0 !important; border-bottom: 1px solid rgba(0,0,0,.06) !important; }
        .card-primary > .card-header { background: #0d6efd !important; }
        .card-info    > .card-header { background: #17a2b8 !important; }
        .card-success > .card-header { background: #28a745 !important; }
        .card-warning > .card-header { background: #ffc107 !important; color: #212529 !important; }
        .card-danger  > .card-header { background: #dc3545 !important; }

        /* â”€â”€ Info boxes / Small boxes â”€â”€ */
        .small-box { border-radius: 14px !important; }
        .small-box:hover { filter: brightness(1.03); }

        /* â”€â”€ Tabs â”€â”€ */
        .nav-tabs .nav-link {
            border-radius: 10px 10px 0 0 !important;
            font-weight: 600 !important; font-size: .88rem !important;
            color: #6c757d !important;
        }
        .nav-tabs .nav-link.active { color: #0d6efd !important; }
        .tab-content {
            background: #fff; border: 1px solid #dee2e6; border-top: none;
            border-radius: 0 0 14px 14px; padding: 20px;
        }

        /* â”€â”€ Badges â”€â”€ */
        .badge-urgent  { background: #dc3545 !important; color: #fff !important; }
        .badge-high    { background: #fd7e14 !important; color: #fff !important; }
        .badge-medium  { background: #0d6efd !important; color: #fff !important; }
        .badge-low     { background: #6c757d !important; color: #fff !important; }
        .status-open     { background: #fde8e8 !important; color: #b02a37 !important; }
        .status-progress { background: #fff3cd !important; color: #856404 !important; }
        .status-closed   { background: #d1e7dd !important; color: #0f5132 !important; }
        .type-rec  { background: #cfe2ff !important; color: #084298 !important; }
        .type-hk   { background: #d1e7dd !important; color: #0f5132 !important; }
        .type-ldy  { background: #fff3cd !important; color: #664d03 !important; }

        /* â”€â”€ Table â”€â”€ */
        .table th { font-size: .78rem !important; text-transform: uppercase; letter-spacing: .04em; color: #6c757d; font-weight: 700; white-space: nowrap; }
        .table td { vertical-align: middle !important; }
        .ticket-link { color: #0d6efd; font-weight: 700; text-decoration: none; }
        .ticket-link:hover { text-decoration: underline; }

        /* â”€â”€ Date filter bar â”€â”€ */
        .date-filter-bar {
            display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
            padding: 14px 18px; background: #fff;
            border-radius: 12px; margin-bottom: 18px;
            box-shadow: 0 1px 6px rgba(0,0,0,.06);
        }
        .date-filter-bar label { font-weight: 600; font-size: .84rem; color: #495057; margin-bottom: 0; }
        .date-filter-bar .flatpickr-input {
            width: 130px; padding: 7px 10px; font-size: .85rem;
            border: 1px solid #dee2e6; border-radius: 8px;
        }

        /* â”€â”€ Overdue row â”€â”€ */
        tr.overdue td { background: #fff5f5 !important; }

        /* â”€â”€ Toast notification â”€â”€ */
        #toast-container {
            position: fixed; top: 70px; right: 20px; z-index: 9999;
            display: flex; flex-direction: column; gap: 8px;
        }
        .notif-toast {
            min-width: 280px; max-width: 360px;
            background: #fff; border-left: 4px solid #0d6efd;
            border-radius: 10px; padding: 12px 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,.15);
            display: flex; align-items: flex-start; gap: 10px;
            animation: slideIn .3s ease;
        }
        .notif-toast.toast-warning { border-left-color: #fd7e14; }
        .notif-toast .toast-icon { font-size: 1.1rem; margin-top: 1px; }
        .notif-toast .toast-body { flex: 1; }
        .notif-toast .toast-title { font-weight: 700; font-size: .85rem; }
        .notif-toast .toast-text  { font-size: .8rem; color: #6c757d; margin-top: 2px; }
        .notif-toast .toast-close  { background: none; border: none; cursor: pointer; color: #aaa; font-size: .9rem; }
        @keyframes slideIn { from { transform: translateX(30px); opacity:0; } to { transform: translateX(0); opacity:1; } }

        /* â”€â”€ Responsive â”€â”€ */
        @media (max-width: 768px) {
            .content { padding: 12px !important; }
            .tab-content { padding: 12px; }
        }
    </style>
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">
<div class="wrapper">

{{-- Toast container --}}
<div id="toast-container"></div>

{{-- â”€â”€ Navbar â”€â”€ --}}
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <span class="nav-link font-weight-bold" style="color:#0a1628!important;">
                @yield('page_title', 'Dashboard')
            </span>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        {{-- Sound toggle --}}
        <li class="nav-item">
            <button id="sound-toggle" title="Toggle notifikasi suara">
                <i class="fas fa-volume-up" id="sound-icon"></i>
            </button>
        </li>

        {{-- Notification bell --}}
        <li class="nav-item dropdown" id="notif-bell">
            <a class="nav-link" data-toggle="dropdown" href="#" role="button">
                <i class="far fa-bell fa-lg"></i>
                <span class="notif-count" id="notif-badge"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="border-radius:12px;overflow:hidden;">
                <div class="dropdown-header d-flex align-items-center justify-content-between px-3 py-2"
                     style="background:#f8f9fa;border-bottom:1px solid #e9ecef;">
                    <span style="font-weight:700;font-size:.88rem;"><i class="fas fa-bell text-primary mr-1"></i> Notifikasi Baru</span>
                    <span id="notif-time" style="font-size:.72rem;color:#aaa;"></span>
                </div>
                <div id="notif-list" style="max-height:280px;overflow-y:auto;">
                    <div class="notif-empty">Tidak ada notifikasi baru</div>
                </div>
                <a href="{{ route('complaints.index') }}"
                   class="dropdown-item text-center py-2"
                   style="font-size:.82rem;font-weight:600;color:#0d6efd;border-top:1px solid #e9ecef;">
                    Lihat Semua Laporan â†’
                </a>
            </div>
        </li>

        {{-- User dropdown --}}
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-user-circle mr-1"></i>
                <span class="d-none d-sm-inline" style="font-size:.88rem;font-weight:600;">
                    {{ Auth::user()->name }}
                </span>
                <i class="fas fa-chevron-down ml-1" style="font-size:.65rem;"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right" style="border-radius:10px;">
                <span class="dropdown-item-text text-muted" style="font-size:.78rem;">
                    {{ Auth::user()->email }}
                </span>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>

{{-- â”€â”€ Sidebar â”€â”€ --}}
<aside class="main-sidebar sidebar-dark-primary elevation-0">
    <a href="{{ route('dashboard') }}" class="brand-link px-3">
        <span class="brand-text brand-logo-wrap">
            <img src="{{ asset('icons/scm-logo-transparent.png') }}" alt="SCM Logo" class="brand-logo-image">
            <span class="brand-logo-copy">
                <strong>SCM</strong>
                <span>Complaint Management</span>
            </span>
        </span>
    </a>

    <div class="sidebar">
        <nav class="mt-2 pb-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu">

                <li class="nav-header">MENU UTAMA</li>

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header">LAPORAN</li>

                <li class="nav-item">
                    <a href="{{ route('complaints.index') }}"
                       class="nav-link {{ request()->routeIs('complaints.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>Semua Laporan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('complaints.index', ['status' => 'open']) }}" class="nav-link">
                        <i class="nav-icon fas fa-circle" style="color:#dc3545;font-size:.55rem;margin-top:.35rem;"></i>
                        <p>Open</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('complaints.index', ['status' => 'progress']) }}" class="nav-link">
                        <i class="nav-icon fas fa-circle" style="color:#ffc107;font-size:.55rem;margin-top:.35rem;"></i>
                        <p>Progress</p>
                    </a>
                </li>

        @if(Auth::user()->isSuperAdmin())
                <li class="nav-header">ADMIN</li>
                <li class="nav-item">
                    <a href="{{ route('users.index') }}"
                       class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-cog"></i><p>Kelola Akun</p>
                    </a>
                </li>

                <li class="nav-header">FILTER TIPE</li>
                <li class="nav-item">
                    <a href="{{ route('complaints.index', ['type' => 'receptionist']) }}" class="nav-link">
                        <i class="nav-icon fas fa-concierge-bell"></i><p>Resepsionis</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('complaints.index', ['type' => 'hk']) }}" class="nav-link">
                        <i class="nav-icon fas fa-broom"></i><p>Housekeeping</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('complaints.index', ['type' => 'laundry']) }}" class="nav-link">
                        <i class="nav-icon fas fa-tshirt"></i><p>Laundry</p>
                    </a>
                </li>
                @endif

                <li class="nav-header">ANALITIK</li>
                <li class="nav-item">
                    <a href="{{ route('analytics.index') }}"
                       class="nav-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-bar"></i><p>Analitik Laporan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('reporters.index') }}"
                       class="nav-link {{ request()->routeIs('reporters.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i><p>Analitik Pelapor</p>
                    </a>
                </li>

                <li class="nav-header">LAINNYA</li>
                <li class="nav-item">
                    <a href="{{ url('/') }}" target="_blank" class="nav-link">
                        <i class="nav-icon fas fa-external-link-alt"></i>
                        <p>Form Laporan Publik</p>
                    </a>
                </li>
            </ul>
        </nav>

        {{-- Sidebar footer --}}
      
    </div>
</aside>

{{-- â”€â”€ Content Wrapper â”€â”€ --}}
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">@yield('page_title', 'Dashboard')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">@yield('breadcrumb', 'Dashboard')</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:10px;">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:10px;">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<footer class="main-footer">
    <div class="footer-wrap">
        <div class="footer-brand">
            <img src="{{ asset('icons/scm-logo-transparent.png') }}" alt="SCM Logo">
            <div>
                <strong>SCM Complaint Management</strong>
                <span>Panel administrasi pelaporan fasilitas internal</span>
            </div>
        </div>
        <div class="footer-meta">
            <div>&copy; {{ date('Y') }} PT. Sulawesi Cahaya Mineral</div>
            <div>Version 2.0</div>
        </div>
    </div>
</footer>
</div>{{-- /wrapper --}}

{{-- â”€â”€ Scripts â”€â”€ --}}
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// â”€â”€ Notification polling â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
let soundEnabled = localStorage.getItem('ga_sound') !== 'false';
let lastCheckTs  = new Date().toISOString();
let pendingCount = 0;

function updateSoundBtn() {
    const icon = document.getElementById('sound-icon');
    const btn  = document.getElementById('sound-toggle');
    if (soundEnabled) {
        icon.className = 'fas fa-volume-up';
        btn.classList.remove('muted');
        btn.title = 'Matikan suara notifikasi';
    } else {
        icon.className = 'fas fa-volume-mute';
        btn.classList.add('muted');
        btn.title = 'Aktifkan suara notifikasi';
    }
}

document.getElementById('sound-toggle').addEventListener('click', () => {
    soundEnabled = !soundEnabled;
    localStorage.setItem('ga_sound', soundEnabled);
    updateSoundBtn();
});
updateSoundBtn();

function playBeep(freq = 880, dur = 0.25) {
    if (!soundEnabled) return;
    try {
        const ctx  = new (window.AudioContext || window.webkitAudioContext)();
        const osc  = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain); gain.connect(ctx.destination);
        osc.type = 'sine'; osc.frequency.value = freq;
        gain.gain.setValueAtTime(0.25, ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + dur);
        osc.start(ctx.currentTime); osc.stop(ctx.currentTime + dur);
    } catch(e) {}
}

function showToast(title, text, type='info') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = 'notif-toast' + (type === 'warning' ? ' toast-warning' : '');
    const icons = { info: 'ðŸ””', warning: 'âš ï¸', success: 'âœ…' };
    toast.innerHTML = `
        <span class="toast-icon">${icons[type] || 'ðŸ””'}</span>
        <div class="toast-body">
            <div class="toast-title">${title}</div>
            <div class="toast-text">${text}</div>
        </div>
        <button class="toast-close" onclick="this.closest('.notif-toast').remove()">âœ•</button>`;
    container.prepend(toast);
    setTimeout(() => toast.remove(), 7000);
}

function renderNotifList(complaints) {
    const list = document.getElementById('notif-list');
    if (!complaints || complaints.length === 0) {
        list.innerHTML = '<div class="notif-empty">Tidak ada notifikasi baru</div>';
        return;
    }
    const typeIcon = { receptionist: 'ðŸ›Žï¸', hk: 'ðŸ§¹', laundry: 'ðŸ‘•' };
    const typeBg   = { receptionist: '#cfe2ff', hk: '#d1e7dd', laundry: '#fff3cd' };
    list.innerHTML = complaints.map(c => `
        <div class="notif-item-row" onclick="window.location='/complaints/${c.id}'">
            <div class="notif-item-icon" style="background:${typeBg[c.type] || '#e9ecef'};">
                ${typeIcon[c.type] || 'ðŸ“‹'}
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-weight:700;font-size:.82rem;">${c.ticket_number}</div>
                <div style="font-size:.76rem;color:#6c757d;">${c.reporter_name}</div>
            </div>
            <div style="font-size:.72rem;color:#aaa;white-space:nowrap;">${c.age}</div>
        </div>`).join('');
}

async function pollNotifications() {
    try {
        const res  = await fetch(`/api/new-complaints?since=${encodeURIComponent(lastCheckTs)}`, {
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
        });
        if (!res.ok) return;
        const data = await res.json();

        document.getElementById('notif-time').textContent =
            new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

        if (data.count > 0) {
            pendingCount += data.count;
            const badge = document.getElementById('notif-badge');
            badge.style.display = 'inline-flex';
            badge.textContent   = pendingCount > 99 ? '99+' : pendingCount;

            renderNotifList(data.complaints);

            // Sound + toast for each new complaint
            playBeep(880, 0.2);
            setTimeout(() => playBeep(1100, 0.15), 250);

            data.complaints.forEach((c, i) => {
                setTimeout(() => {
                    const typeLabel = { receptionist: 'Resepsionis', hk: 'Housekeeping', laundry: 'Laundry' }[c.type] || c.type;
                    showToast(
                        `Laporan Baru â€” ${c.ticket_number}`,
                        `${typeLabel} Â· ${c.reporter_name}`,
                        'info'
                    );
                }, i * 400);
            });
        }

        lastCheckTs = data.timestamp;
    } catch(e) {}
}

// Clear badge when dropdown opens
document.querySelector('#notif-bell > a').addEventListener('click', () => {
    pendingCount = 0;
    const badge = document.getElementById('notif-badge');
    badge.style.display = 'none';
    badge.textContent = '0';
});

// Poll every 30 seconds
setTimeout(pollNotifications, 5000);
setInterval(pollNotifications, 30000);
</script>
<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('{{ asset('sw.js') }}').catch(() => {});
    });
}
</script>
@stack('scripts')
</body>
</html>

