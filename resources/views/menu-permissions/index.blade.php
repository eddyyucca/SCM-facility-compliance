@extends('layouts.app')

@section('title', 'Izin Akses Menu')
@section('page_title', 'Izin Akses Menu')
@section('breadcrumb', 'Izin Akses Menu')

@push('styles')
<style>
    .perm-table th, .perm-table td { vertical-align: middle !important; text-align: center; }
    .perm-table td:first-child { text-align: left; }
    .perm-section-header td {
        background: #f0f4ff !important;
        font-weight: 700;
        font-size: .78rem;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: #3d5a99;
        padding: 8px 16px !important;
        text-align: left !important;
    }
    .perm-section-header.hr td { background: #f0faf6 !important; color: #0f6e56; }
    .perm-section-header.admin td { background: #f7f7f7 !important; color: #555; }
    .perm-checkbox { width: 18px; height: 18px; cursor: pointer; accent-color: #0d6efd; }
    .perm-checkbox:disabled { accent-color: #6c757d; cursor: not-allowed; opacity: .6; }
    .role-col { min-width: 100px; font-size: .78rem; }
    .menu-label { font-size: .85rem; font-weight: 600; color: #1a2a4a; }
    .badge-section {
        display: inline-block; padding: 2px 8px; border-radius: 999px;
        font-size: .65rem; font-weight: 700; margin-left: 6px;
    }
    .badge-ga    { background: #dbeafe; color: #1d4ed8; }
    .badge-hr    { background: #d1fae5; color: #065f46; }
    .badge-admin { background: #f3f4f6; color: #374151; }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between"
                 style="background:#fff;border-bottom:1px solid #e3eaf4;">
                <div>
                    <i class="fas fa-shield-alt text-primary mr-2"></i>
                    <strong>Pengaturan Izin Akses Menu</strong>
                    <small class="text-muted ml-2">Centang role yang boleh melihat setiap menu</small>
                </div>
                <span class="badge badge-warning text-dark" style="font-size:.72rem;">
                    <i class="fas fa-lock mr-1"></i>Super Admin Only
                </span>
            </div>

            <div class="card-body p-0">
                <form method="POST" action="{{ route('menu-permissions.update') }}" id="perm-form">
                    @csrf
                    @method('PUT')

                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 perm-table">
                            <thead>
                                <tr style="background:#f8f9fa;">
                                    <th style="min-width:200px;text-align:left;">Menu</th>
                                    @foreach($roles as $role)
                                    <th class="role-col">
                                        {{ $roleLabels[$role] }}
                                        @if($role === 'superadmin')
                                            <br><small class="text-muted" style="font-size:.65rem;font-weight:400;">selalu aktif</small>
                                        @endif
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>

                                {{-- ── GA Section ── --}}
                                @if(isset($permissions['ga']))
                                <tr class="perm-section-header ga">
                                    <td colspan="{{ count($roles) + 1 }}">
                                        <i class="fas fa-building mr-1"></i> General Affairs
                                    </td>
                                </tr>
                                @foreach($permissions['ga'] as $perm)
                                <tr>
                                    <td>
                                        <span class="menu-label">{{ $perm->menu_label }}</span>
                                        <span class="badge-section badge-ga">GA</span>
                                    </td>
                                    @foreach($roles as $role)
                                    <td>
                                        @if($role === 'superadmin')
                                            <input type="checkbox" class="perm-checkbox" disabled checked
                                                   title="Super Admin selalu memiliki akses penuh">
                                        @else
                                            <input type="hidden"
                                                   name="perm[{{ $perm->menu_key }}][{{ $role }}]"
                                                   value="0">
                                            <input type="checkbox"
                                                   class="perm-checkbox"
                                                   name="perm[{{ $perm->menu_key }}][{{ $role }}]"
                                                   value="1"
                                                   {{ in_array($role, $perm->allowed_roles ?? []) ? 'checked' : '' }}>
                                        @endif
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                                @endif

                                {{-- ── HR Section ── --}}
                                @if(isset($permissions['hr']))
                                <tr class="perm-section-header hr">
                                    <td colspan="{{ count($roles) + 1 }}">
                                        <i class="fas fa-user-tie mr-1"></i> Human Resources
                                    </td>
                                </tr>
                                @foreach($permissions['hr'] as $perm)
                                <tr>
                                    <td>
                                        <span class="menu-label">{{ $perm->menu_label }}</span>
                                        <span class="badge-section badge-hr">HR</span>
                                    </td>
                                    @foreach($roles as $role)
                                    <td>
                                        @if($role === 'superadmin')
                                            <input type="checkbox" class="perm-checkbox" disabled checked
                                                   title="Super Admin selalu memiliki akses penuh">
                                        @else
                                            <input type="hidden"
                                                   name="perm[{{ $perm->menu_key }}][{{ $role }}]"
                                                   value="0">
                                            <input type="checkbox"
                                                   class="perm-checkbox"
                                                   name="perm[{{ $perm->menu_key }}][{{ $role }}]"
                                                   value="1"
                                                   {{ in_array($role, $perm->allowed_roles ?? []) ? 'checked' : '' }}>
                                        @endif
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                                @endif

                                {{-- ── Admin Section ── --}}
                                @if(isset($permissions['admin']))
                                <tr class="perm-section-header admin">
                                    <td colspan="{{ count($roles) + 1 }}">
                                        <i class="fas fa-cog mr-1"></i> Administrasi
                                    </td>
                                </tr>
                                @foreach($permissions['admin'] as $perm)
                                <tr>
                                    <td>
                                        <span class="menu-label">{{ $perm->menu_label }}</span>
                                        <span class="badge-section badge-admin">Admin</span>
                                        @if($perm->menu_key === 'menu_permissions')
                                            <br>
                                            <small class="text-muted" style="font-size:.7rem;">
                                                <i class="fas fa-info-circle"></i>
                                                Halaman ini hanya bisa diakses Super Admin
                                            </small>
                                        @endif
                                    </td>
                                    @foreach($roles as $role)
                                    <td>
                                        {{-- Admin menus: superadmin only, tidak bisa diubah --}}
                                        <input type="checkbox" class="perm-checkbox" disabled
                                               {{ $role === 'superadmin' ? 'checked' : '' }}
                                               title="Menu administrasi hanya untuk Super Admin">
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer d-flex align-items-center justify-content-between"
                         style="background:#f8f9fa;">
                        <small class="text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            Perubahan langsung berlaku saat halaman di-refresh oleh pengguna.
                        </small>
                        <button type="submit" class="btn btn-primary btn-sm px-4">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
// Konfirmasi sebelum simpan
document.getElementById('perm-form').addEventListener('submit', function(e) {
    if (!confirm('Simpan perubahan izin akses menu?')) {
        e.preventDefault();
    }
});
</script>
@endpush
