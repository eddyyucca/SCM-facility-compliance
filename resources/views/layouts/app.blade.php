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
    <title>@yield('title', 'SCM Complaint Management')</title>

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

        /* Brand / Sidebar */
        .main-sidebar { background: #0a1628 !important; }
        .brand-link    { background: #061020 !important; border-bottom: 1px solid rgba(255,255,255,.06) !important; }
        .brand-link .brand-text { color: #fff !important; font-weight: 700 !important; font-size: 1rem !important; }
        .brand-link:hover { background: #061020 !important; }
        .brand-logo-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 0;
        }
        .brand-logo-image {
            width: 128px;
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
            font-size: .84rem;
            font-weight: 700;
        }
        .brand-logo-copy span {
            display: block;
            color: rgba(255,255,255,.6);
            font-size: .62rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .main-footer {
            background: #fff !important;
            border-top: 1px solid #e3eaf4 !important;
            padding: 10px 24px !important;
        }

        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active,
        [class*="sidebar-dark"] .nav-treeview > .nav-item > .nav-link.active {
            background: rgba(13,110,253,.25) !important;
            color: #fff !important;
        }
        [class*="sidebar-dark"] .nav-sidebar .nav-link {
            color: rgba(255,255,255,.68) !important;
            border-radius: 8px !important;
            font-size: .8rem;
            line-height: 1.15;
            min-height: 29px;
            padding: .34rem 1.9rem .34rem .56rem !important;
        }
        [class*="sidebar-dark"] .nav-sidebar .nav-link:hover { background: rgba(255,255,255,.07) !important; color: #fff !important; }
        [class*="sidebar-dark"] .nav-sidebar .nav-link i { color: rgba(255,255,255,.45) !important; }
        [class*="sidebar-dark"] .nav-sidebar .nav-link.active i { color: #74b4ff !important; }
        .nav-sidebar > .nav-item {
            margin: 0 4px 2px;
        }
        .nav-sidebar > .nav-item.has-treeview {
            margin-top: 6px;
            padding-bottom: 6px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .nav-sidebar > .nav-item.has-treeview > .nav-link {
            font-size: .82rem;
            font-weight: 700;
            background: rgba(255,255,255,.045);
        }
        .nav-sidebar .nav-link p {
            max-width: calc(100% - 18px);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .nav-sidebar .nav-link > .right,
        .nav-sidebar .nav-link > p > .right {
            right: .54rem !important;
            top: .52rem !important;
            font-size: .72rem;
        }
        .nav-sidebar > .nav-item.has-treeview.menu-open > .nav-link {
            background: rgba(13,110,253,.16) !important;
        }
        .nav-sidebar .nav-treeview {
            margin: 3px 0 0 10px;
            padding: 1px 0 1px 6px;
            border-left: 1px solid rgba(255,255,255,.12);
        }
        .nav-sidebar .nav-treeview > .nav-item {
            margin: 0;
        }
        .nav-sidebar .nav-treeview > .nav-item > .nav-link {
            min-height: 26px;
            padding: .26rem .48rem !important;
            font-size: .75rem;
        }
        .nav-sidebar .nav-treeview > .nav-item:not(:last-child) > .nav-link {
            border-bottom: 1px solid rgba(255,255,255,.055);
            border-radius: 0 !important;
        }
        .nav-sidebar .nav-treeview > .nav-item > .nav-link .nav-icon {
            font-size: .72rem;
            width: 1.18rem;
        }
        .nav-sidebar .nav-header { color: rgba(255,255,255,.3) !important; font-size: .68rem !important; }
        .nav-sidebar .nav-header.sidebar-section {
            margin: 10px 6px 6px !important;
            padding: 5px 8px !important;
            border-radius: 8px;
            color: rgba(255,255,255,.84) !important;
            font-size: .62rem !important;
            font-weight: 800 !important;
            letter-spacing: .08em;
        }
        .nav-sidebar .nav-header.sidebar-section-ga {
            background: rgba(13,110,253,.16);
            border-left: 3px solid #74b4ff;
        }
        .nav-sidebar .nav-header.sidebar-section-hr {
            background: rgba(15,118,110,.2);
            border-left: 3px solid #2dd4bf;
        }
        .nav-sidebar .nav-header.sidebar-section-admin {
            background: rgba(255,255,255,.08);
            border-left: 3px solid rgba(255,255,255,.4);
        }

        /* User panel at bottom */
        /* Sidebar flex agar footer selalu di bawah */
        .main-sidebar .sidebar { display: flex; flex-direction: column; height: calc(100% - 57px); padding-left: 4px; padding-right: 3px; }
        .main-sidebar .sidebar nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding-right: 3px; scrollbar-width: thin; scrollbar-color: rgba(255,255,255,.22) transparent; }
        .main-sidebar .sidebar nav::-webkit-scrollbar { width: 5px; }
        .main-sidebar .sidebar nav::-webkit-scrollbar-track { background: transparent; }
        .main-sidebar .sidebar nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.22); border-radius: 999px; }
        .sidebar-footer { padding: 12px 16px; border-top: 1px solid rgba(255,255,255,.07); flex-shrink: 0; }
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

        /* Navbar */
        .main-header.navbar {
            background: #fff !important;
            border-bottom: 1px solid #e3eaf4 !important;
            box-shadow: 0 1px 8px rgba(0,0,0,.05) !important;
        }
        .main-header .navbar-brand { color: #0a1628 !important; font-weight: 700; }
        .nav-item .nav-link { color: #4a5568 !important; }

        /* Notification bell */
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
        #push-toggle {
            background: none; border: none; cursor: pointer;
            color: #4a5568; font-size: .9rem; padding: 4px 10px;
            border-radius: 999px; transition: background .15s, color .15s;
            display: inline-flex; align-items: center; gap: 6px;
        }
        #push-toggle:hover { background: #f0f4f8; }
        #push-toggle.enabled { color: #198754; background: #eaf8ef; }
        #push-toggle.disabled { color: #6c757d; }
        #push-toggle.denied { color: #dc3545; background: #fff0f0; }
        #push-toggle[hidden] { display: none !important; }
        #push-label { font-size: .75rem; font-weight: 700; }

        /* Content area — min-height dibiarkan AdminLTE JS yang hitung */
        .content-wrapper { background: #eef2f7 !important; }
        .content-header { padding: 12px 20px 0 !important; }
        .content-header h1 { font-size: 1.2rem !important; font-weight: 700 !important; }
        .content { padding: 12px 20px 20px !important; }

        /* Cards */
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

        /* Info boxes / Small boxes */
        .small-box { border-radius: 14px !important; }
        .small-box:hover { filter: brightness(1.03); }

        /* Tabs */
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

        /* Badges */
        .badge-urgent  { background: #dc3545 !important; color: #fff !important; }
        .badge-high    { background: #fd7e14 !important; color: #fff !important; }
        .badge-medium  { background: #0d6efd !important; color: #fff !important; }
        .badge-low     { background: #6c757d !important; color: #fff !important; }
        .status-open     { background: #fde8e8 !important; color: #b02a37 !important; }
        .status-progress { background: #fff3cd !important; color: #856404 !important; }
        .status-closed   { background: #d1e7dd !important; color: #0f5132 !important; }
        .status-rejected { background: #e2e3e5 !important; color: #41464b !important; }
        .type-rec  { background: #cfe2ff !important; color: #084298 !important; }
        .type-hk   { background: #d1e7dd !important; color: #0f5132 !important; }
        .type-ldy  { background: #fff3cd !important; color: #664d03 !important; }

        /* Table */
        .table th { font-size: .78rem !important; text-transform: uppercase; letter-spacing: .04em; color: #6c757d; font-weight: 700; white-space: nowrap; }
        .table td { vertical-align: middle !important; }
        .ticket-link { color: #0d6efd; font-weight: 700; text-decoration: none; }
        .ticket-link:hover { text-decoration: underline; }

        /* Date filter bar */
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

        /* Overdue row */
        tr.overdue td { background: #fff5f5 !important; }

        /* Toast notification */
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

        .photo-thumb-button {
            border: 0;
            padding: 0;
            background: transparent;
            cursor: pointer;
        }
        .photo-thumb-image {
            width: 64px;
            height: 64px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #dce5f2;
            box-shadow: 0 4px 12px rgba(15, 23, 42, .08);
            transition: transform .18s ease, box-shadow .18s ease;
        }
        .photo-thumb-button:hover .photo-thumb-image {
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(15, 23, 42, .12);
        }
        .photo-count-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            background: #e8f1ff;
            color: #0d6efd;
            font-size: .74rem;
            font-weight: 700;
        }

        .photo-lightbox .modal-content {
            background: #0b1220;
            border: 0;
            border-radius: 18px;
            overflow: hidden;
        }
        .photo-lightbox .modal-header {
            border-bottom: 1px solid rgba(255,255,255,.08);
            color: #fff;
        }
        .photo-lightbox .modal-body {
            padding: 12px;
            text-align: center;
            background: radial-gradient(circle at top, rgba(13,110,253,.18), transparent 48%), #0b1220;
        }
        .photo-lightbox .modal-body img {
            width: 100%;
            max-height: 74vh;
            object-fit: contain;
            border-radius: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .content { padding: 12px !important; }
            .tab-content { padding: 12px; }
            .main-footer { padding: 10px 16px !important; }
        }
    </style>
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

{{-- Toast container --}}
<div id="toast-container"></div>

<div class="modal fade photo-lightbox" id="photoLightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoLightboxTitle">Foto Lampiran</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="photoLightboxImage" src="" alt="Preview foto laporan">
            </div>
        </div>
    </div>
</div>

{{-- Navbar --}}
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
        <li class="nav-item">
            <button id="push-toggle" class="disabled" title="Aktifkan notifikasi push" hidden>
                <i class="fas fa-bell" id="push-icon"></i>
                <span id="push-label">Push Off</span>
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
                    Lihat Semua Laporan <i class="fas fa-arrow-right ml-1"></i>
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

{{-- Sidebar --}}
<aside class="main-sidebar sidebar-dark-primary elevation-0">
    <a href="{{ route('dashboard') }}" class="brand-link px-3">
        <span class="brand-text brand-logo-wrap">
            <img src="{{ asset('img/sedia.png') }}" alt="SEDIA Logo" class="brand-logo-image">
        </span>
    </a>

    <div class="sidebar">
        <nav class="mt-2 pb-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu">

                @php
                    $gaMenuOpen = request()->routeIs('dashboard')
                        || request()->routeIs('complaints.*')
                        || request()->routeIs('analytics.*')
                        || request()->routeIs('reporters.*');
                    $hrMenuOpen = request()->routeIs('hr.*');
                @endphp

                <li class="nav-item has-treeview {{ $gaMenuOpen ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $gaMenuOpen ? 'active' : '' }}">
                        <i class="nav-icon fas fa-building"></i>
                        <p>
                            General Affairs
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard GA</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('complaints.index') }}"
                               class="nav-link {{ request()->routeIs('complaints.*') && !request()->filled('status') && !request()->boolean('overdue') && !request()->filled('type') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clipboard-list"></i>
                                <p>Semua Laporan GA</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('complaints.index', ['status' => 'open']) }}"
                               class="nav-link {{ request()->routeIs('complaints.*') && request('status') === 'open' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-circle" style="color:#dc3545;font-size:.55rem;margin-top:.35rem;"></i>
                                <p>GA Open</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('complaints.index', ['status' => 'progress']) }}"
                               class="nav-link {{ request()->routeIs('complaints.*') && request('status') === 'progress' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-circle" style="color:#ffc107;font-size:.55rem;margin-top:.35rem;"></i>
                                <p>GA Progress</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('complaints.index', ['overdue' => '1']) }}"
                               class="nav-link {{ request()->routeIs('complaints.*') && request()->boolean('overdue') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-exclamation-triangle" style="color:#dc3545;"></i>
                                <p>GA Overdue SLA</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('complaints.index', ['type' => 'receptionist']) }}"
                               class="nav-link {{ request()->routeIs('complaints.*') && request('type') === 'receptionist' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-concierge-bell"></i><p>GA Receptionist</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('complaints.index', ['type' => 'hk']) }}"
                               class="nav-link {{ request()->routeIs('complaints.*') && request('type') === 'hk' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-broom"></i><p>GA Housekeeping</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('complaints.index', ['type' => 'laundry']) }}"
                               class="nav-link {{ request()->routeIs('complaints.*') && request('type') === 'laundry' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tshirt"></i><p>GA Laundry</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('analytics.index') }}"
                               class="nav-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-bar"></i><p>Analitik GA</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('reporters.index') }}"
                               class="nav-link {{ request()->routeIs('reporters.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i><p>Pelapor GA</p>
                            </a>
                        </li>
                    </ul>
                </li>

        @if(Auth::user()->isSuperAdmin())
                <li class="nav-item has-treeview {{ $hrMenuOpen ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $hrMenuOpen ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>
                            Human Resources
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('hr.dashboard') }}"
                               class="nav-link {{ request()->routeIs('hr.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-line"></i><p>Dashboard HR</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('hr.requests.index') }}"
                               class="nav-link {{ request()->routeIs('hr.requests.*') && !request()->filled('status') && !request()->boolean('overdue') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clipboard-check"></i><p>Semua Laporan HR</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('hr.requests.index', ['status' => 'open']) }}"
                               class="nav-link {{ request()->routeIs('hr.requests.*') && request('status') === 'open' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-circle" style="color:#dc3545;font-size:.55rem;margin-top:.35rem;"></i>
                                <p>HR Open</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('hr.requests.index', ['status' => 'progress']) }}"
                               class="nav-link {{ request()->routeIs('hr.requests.*') && request('status') === 'progress' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-circle" style="color:#ffc107;font-size:.55rem;margin-top:.35rem;"></i>
                                <p>HR Progress</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('hr.requests.index', ['status' => 'closed']) }}"
                               class="nav-link {{ request()->routeIs('hr.requests.*') && request('status') === 'closed' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-check-circle" style="color:#198754;"></i>
                                <p>HR Penyelesaian</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('hr.requests.index', ['overdue' => '1']) }}"
                               class="nav-link {{ request()->routeIs('hr.requests.*') && request()->boolean('overdue') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-exclamation-triangle" style="color:#dc3545;"></i>
                                <p>HR Overdue SLA</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header sidebar-section sidebar-section-admin">ADMINISTRASI</li>
                <li class="nav-item">
                    <a href="{{ route('users.index') }}"
                       class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-cog"></i><p>Kelola Akun</p>
                    </a>
                </li>
                @endif

                <li class="nav-header sidebar-section sidebar-section-admin">LAINNYA</li>
                <li class="nav-item">
                    <a href="{{ url('/') }}" target="_blank" class="nav-link">
                        <i class="nav-icon fas fa-external-link-alt"></i>
                        <p>Form Laporan Publik</p>
                    </a>
                </li>
            </ul>
        </nav>

        {{-- Sidebar footer: user info --}}
        <!-- <div class="sidebar-footer">
            <div class="user-name">{{ Auth::user()->name }}</div>
            <span class="user-role">{{ ucfirst(Auth::user()->role) }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                </button>
            </form>
        </div> -->
    </div>
</aside>

{{-- Content Wrapper --}}
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
    <span style="font-size:.78rem;color:#aaa;">&copy; {{ date('Y') }} PT. Sulawesi Cahaya Mineral &mdash; v2.0</span>
</footer>
</div>{{-- /wrapper --}}

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Notification polling
let soundEnabled = localStorage.getItem('ga_sound') !== 'false';
let lastCheckTs  = new Date().toISOString();
let pendingCount = 0;
let swRegistration = null;
let pushPublicKey = null;

const pushState = {
    supported: 'serviceWorker' in navigator && 'PushManager' in window && 'Notification' in window,
    enabled: false,
    busy: false,
};

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

function updatePushBtn() {
    const btn = document.getElementById('push-toggle');
    const icon = document.getElementById('push-icon');
    const label = document.getElementById('push-label');

    if (!btn || !icon || !label) return;
    if (!pushState.supported) {
        btn.hidden = true;
        return;
    }

    btn.hidden = false;
    btn.disabled = pushState.busy;
    btn.classList.remove('enabled', 'disabled', 'denied');

    if (Notification.permission === 'denied') {
        btn.classList.add('denied');
        icon.className = 'fas fa-bell-slash';
        label.textContent = 'Push Blocked';
        btn.title = 'Izin notifikasi diblokir di browser';
        return;
    }

    if (pushState.enabled) {
        btn.classList.add('enabled');
        icon.className = 'fas fa-bell';
        label.textContent = 'Push On';
        btn.title = 'Nonaktifkan notifikasi push';
        return;
    }

    btn.classList.add('disabled');
    icon.className = 'far fa-bell';
    label.textContent = 'Push Off';
    btn.title = pushState.busy ? 'Memproses notifikasi push' : 'Aktifkan notifikasi push';
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }

    return outputArray;
}

async function getPushPublicKey() {
    if (pushPublicKey) return pushPublicKey;

    const params = new URLSearchParams({
        push_action: 'public_key',
        _ts: Date.now().toString(),
    });
    const res = await fetch(`/api/new-complaints?${params}`, { cache: 'no-store' });
    if (!res.ok) {
        throw new Error('Gagal memuat public key push.');
    }

    const data = await res.json();
    if (!data.enabled || !data.publicKey) {
        throw new Error('Web push belum dikonfigurasi di server.');
    }

    pushPublicKey = data.publicKey;
    return pushPublicKey;
}

async function syncPushSubscription(interactive = false) {
    if (!pushState.supported) return;

    pushState.busy = true;
    updatePushBtn();

    try {
        swRegistration = swRegistration || await navigator.serviceWorker.ready;

        if (Notification.permission === 'default' && interactive) {
            const permission = await Notification.requestPermission();
            if (permission !== 'granted') {
                pushState.enabled = false;
                updatePushBtn();
                return;
            }
        }

        if (Notification.permission !== 'granted') {
            pushState.enabled = false;
            updatePushBtn();
            return;
        }

        const vapidPublicKey = await getPushPublicKey();
        let subscription = await swRegistration.pushManager.getSubscription();
        const supportedEncodings = PushManager.supportedContentEncodings || [];

        if (!subscription) {
            subscription = await swRegistration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
            });
        }

        const payload = subscription.toJSON();
        payload.contentEncoding = supportedEncodings.includes('aes128gcm') ? 'aes128gcm' : 'aesgcm';

        const params = new URLSearchParams({
            push_action: 'subscribe',
            endpoint: payload.endpoint,
            p256dh: payload.keys?.p256dh || '',
            auth: payload.keys?.auth || '',
            content_encoding: payload.contentEncoding || 'aes128gcm',
            _ts: Date.now().toString(),
        });
        const res = await fetch(`/api/new-complaints?${params}`, {
            cache: 'no-store',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
        });

        if (!res.ok) {
            throw new Error('Gagal menyimpan subscription push.');
        }

        pushState.enabled = true;

        if (interactive) {
            showToast('Push Aktif', 'Notifikasi push berhasil diaktifkan.', 'success');
        }
    } catch (error) {
        pushState.enabled = false;

        if (interactive) {
            showToast('Push Gagal', error.message || 'Tidak bisa mengaktifkan notifikasi push.', 'warning');
        }
    } finally {
        pushState.busy = false;
        updatePushBtn();
    }
}

async function disablePushSubscription() {
    if (!pushState.supported) return;

    pushState.busy = true;
    updatePushBtn();

    try {
        swRegistration = swRegistration || await navigator.serviceWorker.ready;
        const subscription = await swRegistration.pushManager.getSubscription();

        if (subscription) {
            const params = new URLSearchParams({
                push_action: 'unsubscribe',
                endpoint: subscription.endpoint,
                _ts: Date.now().toString(),
            });
            await fetch(`/api/new-complaints?${params}`, {
                cache: 'no-store',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
            });

            await subscription.unsubscribe();
        }

        pushState.enabled = false;
        showToast('Push Nonaktif', 'Notifikasi push dimatikan di device ini.', 'info');
    } catch (error) {
        showToast('Push Gagal', error.message || 'Tidak bisa mematikan notifikasi push.', 'warning');
    } finally {
        pushState.busy = false;
        updatePushBtn();
    }
}

document.getElementById('sound-toggle').addEventListener('click', () => {
    soundEnabled = !soundEnabled;
    localStorage.setItem('ga_sound', soundEnabled);
    updateSoundBtn();
});
updateSoundBtn();
updatePushBtn();

const pushToggle = document.getElementById('push-toggle');
if (pushToggle) {
    pushToggle.addEventListener('click', async () => {
        if (pushState.busy) return;

        if (pushState.enabled) {
            await disablePushSubscription();
            return;
        }

        await syncPushSubscription(true);
    });
}

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
    const icons = { info: '<i class="fas fa-bell text-primary"></i>', warning: '<i class="fas fa-exclamation-triangle text-warning"></i>', success: '<i class="fas fa-check-circle text-success"></i>' };
    toast.innerHTML = `
        <span class="toast-icon">${icons[type] || '<i class="fas fa-bell text-primary"></i>'}</span>
        <div class="toast-body">
            <div class="toast-title">${title}</div>
            <div class="toast-text">${text}</div>
        </div>
        <button class="toast-close" onclick="this.closest('.notif-toast').remove()">x</button>`;
    container.prepend(toast);
    setTimeout(() => toast.remove(), 7000);
}

function renderNotifList(complaints) {
    const list = document.getElementById('notif-list');
    if (!complaints || complaints.length === 0) {
        list.innerHTML = '<div class="notif-empty">Tidak ada notifikasi baru</div>';
        return;
    }
    const typeIcon = { receptionist: '<i class="fas fa-concierge-bell"></i>', hk: '<i class="fas fa-broom"></i>', laundry: '<i class="fas fa-tshirt"></i>' };
    const typeBg   = { receptionist: '#cfe2ff', hk: '#d1e7dd', laundry: '#fff3cd' };
    list.innerHTML = complaints.map(c => `
        <div class="notif-item-row" onclick="window.location='/complaints/${c.id}'">
            <div class="notif-item-icon" style="background:${typeBg[c.type] || '#e9ecef'};">
                ${typeIcon[c.type] || '<i class="fas fa-clipboard-list"></i>'}
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
        const params = new URLSearchParams({
            since: lastCheckTs,
            _ts: Date.now().toString(),
        });
        const res  = await fetch(`/api/new-complaints?${params}`, {
            cache: 'no-store',
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
                    const typeLabel = { receptionist: 'Receptionist', hk: 'Housekeeping', laundry: 'Laundry' }[c.type] || c.type;
                    showToast(
                        `Laporan Baru  ${c.ticket_number}`,
                        `${typeLabel} · ${c.reporter_name}`,
                        'info'
                    );
                }, i * 400);
            });
        }

        lastCheckTs = data.timestamp;

        // Jika ada laporan baru DAN dashboard terbuka, refresh seketika
        if (data.count > 0 && typeof window.refreshDashboard === 'function') {
            window.refreshDashboard();
        }
    } catch(e) {}
}

// Clear badge when dropdown opens
document.querySelector('#notif-bell > a').addEventListener('click', () => {
    pendingCount = 0;
    const badge = document.getElementById('notif-badge');
    badge.style.display = 'none';
    badge.textContent = '0';
});

// Refresh dashboard saat tab kembali aktif (misal setelah ubah status di halaman lain)
document.addEventListener('visibilitychange', () => {
    if (!document.hidden && typeof window.refreshDashboard === 'function') {
        window.refreshDashboard();
    }
});

// Poll every 15 seconds
setTimeout(pollNotifications, 5000);
setInterval(pollNotifications, 15000);
</script>
<script>
document.addEventListener('click', (event) => {
    const trigger = event.target.closest('[data-photo-preview]');

    if (!trigger) {
        return;
    }

    event.preventDefault();

    const modal = $('#photoLightboxModal');
    const image = document.getElementById('photoLightboxImage');
    const title = document.getElementById('photoLightboxTitle');

    image.src = trigger.getAttribute('data-photo-src') || '';
    image.alt = trigger.getAttribute('data-photo-title') || 'Preview foto laporan';
    title.textContent = trigger.getAttribute('data-photo-title') || 'Foto Lampiran';

    modal.modal('show');
});

$('#photoLightboxModal').on('hidden.bs.modal', function () {
    const image = document.getElementById('photoLightboxImage');
    image.src = '';
});
</script>
<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('{{ asset('sw.js') }}')
            .then(async (registration) => {
                swRegistration = registration;
                await registration.update();

                if (pushState.supported) {
                    const subscription = await registration.pushManager.getSubscription();
                    pushState.enabled = !!subscription;
                    updatePushBtn();

                    if (Notification.permission === 'granted') {
                        await syncPushSubscription(false);
                    }
                }
            })
            .catch(() => {});
    });
}
</script>
@stack('scripts')
</body>
</html>
