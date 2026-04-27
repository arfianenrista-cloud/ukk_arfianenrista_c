@extends('layouts.app')
@section('title', 'Tambah Buku')
@section('content')
<div style="max-width:680px">
<div class="page-header">
    <h1 class="page-title">➕ Tambah Buku Baru</h1>
    <a href="{{ route('buku.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <form method="POST" action="{{ route('buku.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-row">
            <div class="form-group" style="grid-column:1/-1">
                <label>Judul Buku *</label>
                <input type="text" name="Judul" value="{{ old('Judul') }}" placeholder="Judul lengkap buku" required>
            </div>
            <div class="form-group">
                <label>Penulis *</label>
                <input type="text" name="Penulis" value="{{ old('Penulis') }}" placeholder="Nama penulis" required>
            </div>
            <div class="form-group">
                <label>Penerbit</label>
                <input type="text" name="Penerbit" value="{{ old('Penerbit') }}" placeholder="Nama penerbit">
            </div>
            <div class="form-group">
                <label>Tahun Terbit</label>
                <input type="number" name="TahunTerbit" value="{{ old('TahunTerbit') }}" placeholder="2024" min="1800" max="{{ date('Y') }}">
            </div>
            <div class="form-group">
                <label>ISBN</label>
                <input type="text" name="ISBN" value="{{ old('ISBN') }}" placeholder="978-xxx-xxx-xxx-x">
            </div>
            <div class="form-group">
                <label>Barcode</label>
                <input type="text" name="Barcode" value="{{ old('Barcode') }}" placeholder="BK-0001">
            </div>
            <div class="form-group">
                <label>Jumlah Total *</label>
                <input type="number" name="JumlahTotal" value="{{ old('JumlahTotal', 1) }}" min="1" required>
            </div>
            <div class="form-group" style="grid-column:1/-1">
                <label>Kategori *</label>
                <div style="display:flex;flex-wrap:wrap;gap:10px;padding:12px;background:var(--bg);border:1px solid var(--border);border-radius:9px">
                    @foreach($kategori as $k)
                    <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--text);cursor:pointer;margin:0">
                        <input type="checkbox" name="kategori_ids[]" value="{{ $k->KategoriID }}" style="width:auto;padding:0" {{ in_array($k->KategoriID, old('kategori_ids', [])) ? 'checked' : '' }}>
                        {{ $k->NamaKategori }}
                    </label>
                    @endforeach
                </div>
                @error('kategori_ids')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group" style="grid-column:1/-1">
                <label>Deskripsi</label>
                <textarea name="Deskripsi" rows="3" placeholder="Deskripsi singkat buku...">{{ old('Deskripsi') }}</textarea>
            </div>
            <div class="form-group" style="grid-column:1/-1">
                <label>Cover Buku</label>
                <input type="file" name="cover" accept="image/*" style="padding:9px 13px">
            </div>
        </div>
        <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
            <a href="{{ route('buku.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Buku</button>
        </div>
    </form>
</div>
</div>
@endsection
