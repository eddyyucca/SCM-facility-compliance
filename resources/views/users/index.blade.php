@extends('layouts.app')
@section('title', 'Kelola Akun')
@section('page_title', 'Kelola Akun')
@section('breadcrumb', 'Kelola Akun')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between mb-3" style="gap:12px;">
    <div>
        <h2 style="font-size:1.05rem;font-weight:700;margin:0;color:#18263f;">Akun Admin</h2>
        <div style="font-size:.82rem;color:#6c757d;">Kelola pengguna panel admin dan role aksesnya.</div>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus mr-1"></i> Tambah Akun
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Dibuat</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="font-weight-bold">{{ $user->name }}</div>
                                @if(auth()->id() === $user->id)
                                    <span class="badge badge-primary">Anda</span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge {{ [
                                    'superadmin' => 'badge-danger',
                                    'receptionist' => 'type-rec',
                                    'hk' => 'type-hk',
                                    'laundry' => 'type-ldy',
                                ][$user->role] ?? 'badge-secondary' }}">
                                    {{ [
                                        'superadmin' => 'Super Admin',
                                        'receptionist' => 'Receptionist',
                                        'hk' => 'Housekeeping',
                                        'laundry' => 'Laundry',
                                    ][$user->role] ?? $user->role }}
                                </span>
                            </td>
                            <td>{{ $user->created_at?->format('d M Y H:i') }}</td>
                            <td class="text-right">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-pen"></i>
                                </a>
                                @if(auth()->id() !== $user->id)
                                    <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('Hapus akun ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada akun.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
        <div class="card-footer">
            {{ $users->links('vendor.pagination.custom') }}
        </div>
    @endif
</div>
@endsection
