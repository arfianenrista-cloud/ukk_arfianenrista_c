@extends('layouts.app')
@section('title', 'Tambah User')
@section('content')
<div style="max-width:640px">
<div class="page-header">
    <h1 class="page-title">➕ Tambah User Baru</h1>
    <a href="{{ route('users.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
<div class="card">
    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <div class="form-row">
            <div class="form-group" style="grid-column:1/-1">
                <label>Nama Lengkap *</label>
                <input type="text" name="NamaLengkap" value="{{ old('NamaLengkap') }}" required>
            </div>
            <div class="form-group">
                <label>Username *</label>
                <input type="text" name="Username" value="{{ old('Username') }}" required>
            </div>
            <div class="form-group">
                <label>Role *</label>
                <select name="Role" required>
                    <option value="siswa" {{ old('Role')==='siswa'?'selected':'' }}>Siswa</option>
                    <option value="petugas" {{ old('Role')==='petugas'?'selected':'' }}>Petugas</option>
                    <option value="admin" {{ old('Role')==='admin'?'selected':'' }}>Admin</option>
                </select>
            </div>
            <div class="form-group" style="grid-column:1/-1">
                <label>Email *</label>
                <input type="email" name="Email" value="{{ old('Email') }}" required>
            </div>
            <div class="form-group">
                <label>NIS</label>
                <input type="text" name="NIS" value="{{ old('NIS') }}" placeholder="Nomor Induk Siswa">
            </div>
            <div class="form-group">
                <label>Rayon</label>
                <input type="text" name="Rayon" value="{{ old('Rayon') }}">
            </div>
            <div class="form-group" style="grid-column:1/-1">
                <label>Rombel</label>
                <input type="text" name="Rombel" value="{{ old('Rombel') }}" placeholder="XII RPL 1">
            </div>
            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="Password" required>
            </div>
            <div class="form-group">
                <label>Konfirmasi Password *</label>
                <input type="password" name="Password_confirmation" required>
            </div>
        </div>
        <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
            <a href="{{ route('users.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan User</button>
        </div>
    </form>
</div>
</div>
@endsection
