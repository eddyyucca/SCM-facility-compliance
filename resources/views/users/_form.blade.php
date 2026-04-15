@php
    $editing = isset($user);
@endphp

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Nama</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $user->name ?? '') }}"
                        placeholder="Nama pengguna"
                        required
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $user->email ?? '') }}"
                        placeholder="nama@scm.com"
                        required
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                        <option value="">Pilih role</option>
                        @foreach($roles as $value => $label)
                            <option value="{{ $value }}" @selected(old('role', $user->role ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <div class="w-100 p-3 rounded" style="background:#f8fbff;border:1px solid #e5edf8;">
                    <div style="font-size:.8rem;font-weight:700;color:#526072;">Catatan</div>
                    <div style="font-size:.82rem;color:#6c757d;">
                        Role menentukan akses menu, laporan, dan analytics yang dapat dibuka pengguna.
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="password">Password {{ $editing ? '(Opsional)' : '' }}</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="{{ $editing ? 'Kosongkan jika tidak ingin diubah' : 'Minimal 8 karakter' }}"
                        {{ $editing ? '' : 'required' }}
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Ulangi password"
                        {{ $editing ? '' : 'required' }}
                    >
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between align-items-center">
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-1"></i> {{ $editing ? 'Simpan Perubahan' : 'Buat Akun' }}
        </button>
    </div>
</div>
