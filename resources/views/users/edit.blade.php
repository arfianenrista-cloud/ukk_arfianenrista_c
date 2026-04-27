@extends('layouts.app')
@section('title', 'Edit User')
@section('content')
<div style="max-width:640px">
<div class="page-header">
    <h1 class="page-title">✏️ Edit User</h1>
    <a href="{{ route('users.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
<div class="card">
    <form method="POST" action="{{ route('users.update', $user->UserID) }}">
        @csrf @method('PUT')
        <div class="form-row">
            <div class="form-group" style="grid-column:1/-1">
                <label>Nama Lengkap *</label>
                <input type="text" name="NamaLengkap" value="{{ old('NamaLengkap', $user->NamaLengkap) }}" required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" value="{{ $user->Username }}" disabled style="opacity:.5;cursor:not-allowed">
            </div>
            <div class="form-group">
                <label>Role</label>
                <input type="text" value="{{ $user->Role }}" disabled style="opacity:.5;cursor:not-allowed">
            </div>
            <div class="form-group" style="grid-column:1/-1">
                <label>Email *</label>
                <input type="email" name="Email" value="{{ old('Email', $user->Email) }}" required>
            </div>
            <div class="form-group">
                <label>NIS</label>
                <input type="text" name="NIS" value="{{ old('NIS', $user->NIS) }}">
            </div>
            <div class="form-group">
                <label>Rayon</label>
                <input type="text" name="Rayon" value="{{ old('Rayon', $user->Rayon) }}">
            </div>
            <div class="form-group">
                <label>Rombel</label>
                <input type="text" name="Rombel" value="{{ old('Rombel', $user->Rombel) }}">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="Status">
                    <option value="aktif" {{ $user->Status==='aktif'?'selected':'' }}>Aktif</option>
                    <option value="nonaktif" {{ $user->Status==='nonaktif'?'selected':'' }}>Nonaktif</option>
                </select>
            </div>
            <div class="form-group">
                <label>Password Baru (kosongkan jika tidak diganti)</label>
                <input type="password" name="Password">
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="Password_confirmation">
            </div>
            <div class="form-group" style="grid-column:1/-1">
                <label>Alamat</label>
                <textarea name="Alamat" rows="2">{{ old('Alamat', $user->Alamat) }}</textarea>
            </div>
        </div>
        <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
            <a href="{{ route('users.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui User</button>
        </div>
    </form>
</div>
</div>
@endsection
