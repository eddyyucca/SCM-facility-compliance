@extends('layouts.app')
@section('title', 'Tambah Akun')
@section('page_title', 'Tambah Akun')
@section('breadcrumb', 'Tambah Akun')

@section('content')
<div class="mb-3">
    <h2 style="font-size:1.05rem;font-weight:700;margin:0;color:#18263f;">Tambah Akun Admin</h2>
    <div style="font-size:.82rem;color:#6c757d;">Buat akun baru untuk tim yang akan mengakses sistem.</div>
</div>

<form method="POST" action="{{ route('users.store') }}">
    @csrf
    @include('users._form')
</form>
@endsection
