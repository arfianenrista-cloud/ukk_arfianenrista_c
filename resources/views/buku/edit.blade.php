@extends('layouts.app')
@section('title', 'Edit Buku')
@section('content')
<div style="max-width:680px">
<div class="page-header">
    <h1 class="page-title">✏️ Edit Buku</h1>
    <a href="{{ route('buku.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <form method="POST" action="{{ route('buku.update', $buku->BukuID) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="form-row">
            <div class="form-group" style="grid-column:1/-1">
                <label>Judul Buku *</label>
                <input type="text" name="Judul" value="{{ old('Judul', $buku->Judul) }}" required>
            </div>
            <div class="form-group">
                <label>Penulis *</label>
                <input type="text" name="Penulis" value="{{ old('Penulis', $buku->Penulis) }}" required>
            </div>
            <div class="form-group">
                <label>Penerbit</label>
                <input type="text" name="Penerbit" value="{{ old('Penerbit', $buku->Penerbit) }}">
            </div>
            <div class="form-group">
                <label>Tahun Terbit</label>
                <input type="number" name="TahunTerbit" value="{{ old('TahunTerbit', $buku->TahunTerbit) }}">
            </div>
            <div class="form-group">
                <label>ISBN</label>
                <input type="text" name="ISBN" value="{{ old('ISBN', $buku->ISBN) }}">
            </div>
            <div class="form-group">
                <label>Barcode</label>
                <input type="text" name="Barcode" value="{{ old('Barcode', $buku->Barcode) }}">
            </div>
            <div class="form-group">
                <label>Jumlah Total *</label>
                <input type="number" name="JumlahTotal" value="{{ old('JumlahTotal', $buku->JumlahTotal) }}" min="1" required>
            </div>
            <div class="form-group">
                <label>Jumlah Tersedia</label>
                <input type="number" name="JumlahTersedia" value="{{ old('JumlahTersedia', $buku->JumlahTersedia) }}" min="0">
            </div>
            <div class="form-group">
                <label>Kondisi</label>
                <select name="Kondisi">
                    <option value="baik" {{ $buku->Kondisi==='baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak" {{ $buku->Kondisi==='rusak' ? 'selected' : '' }}>Rusak</option>
                    <option value="hilang" {{ $buku->Kondisi==='hilang' ? 'selected' : '' }}>Hilang</option>
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="Status">
                    <option value="tersedia" {{ $buku->Status==='tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="dipinjam" {{ $buku->Status==='dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                </select>
            </div>
            <div class="form-group" style="grid-column:1/-1">
                <label>Kategori</label>
                <div style="display:flex;flex-wrap:wrap;gap:10px;padding:12px;background:var(--bg);border:1px solid var(--border);border-radius:9px">
                    @php $selectedKat = $buku->kategori->pluck('KategoriID')->toArray(); @endphp
                    @foreach($kategori as $k)
                    <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--text);cursor:pointer;margin:0">
                        <input type="checkbox" name="kategori_ids[]" value="{{ $k->KategoriID }}" style="width:auto;padding:0" {{ in_array($k->KategoriID, old('kategori_ids', $selectedKat)) ? 'checked' : '' }}>
                        {{ $k->NamaKategori }}
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="form-group" style="grid-column:1/-1">
                <label>Deskripsi</label>
                <textarea name="Deskripsi" rows="3">{{ old('Deskripsi', $buku->Deskripsi) }}</textarea>
            </div>
            <div class="form-group" style="grid-column:1/-1">
                <label>Cover Buku (kosongkan jika tidak ingin mengganti)</label>
                @if($buku->CoverImage)
                    <img src="{{ asset('storage/'.$buku->CoverImage) }}" style="height:80px;border-radius:6px;margin-bottom:8px;display:block">
                @endif
                <input type="file" name="cover" accept="image/*" style="padding:9px 13px">
            </div>
        </div>
        <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
            <a href="{{ route('buku.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui Buku</button>
        </div>
    </form>
</div>
</div>
@endsection
