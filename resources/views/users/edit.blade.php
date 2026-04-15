@extends('layouts.app')
@section('title', 'Edit Akun')
@section('page_title', 'Edit Akun')
@section('breadcrumb', 'Edit Akun')

@section('content')
<div class="mb-3">
    <h2 style="font-size:1.05rem;font-weight:700;margin:0;color:#18263f;">Edit Akun</h2>
    <div style="font-size:.82rem;color:#6c757d;">Perbarui identitas, role, atau password pengguna.</div>
</div>

<form method="POST" action="{{ route('users.update', $user) }}">
    @csrf
    @method('PUT')
    @include('users._form')
</form>
@endsection
