@extends('layouts.app')
@section('title', 'Catat Peminjaman')
@section('content')
<div style="max-width:600px">
<div class="page-header">
    <h1 class="page-title">➕ Catat Peminjaman Baru</h1>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <form method="POST" action="{{ route('peminjaman.store') }}">
        @csrf
        <div class="form-group">
            <label>Siswa Peminjam *</label>
            <select name="UserID" required>
                <option value="">— Pilih Siswa —</option>
                @foreach($siswa as $s)
                <option value="{{ $s->UserID }}" {{ old('UserID')==$s->UserID ? 'selected' : '' }}>
                    {{ $s->NamaLengkap }} | NIS: {{ $s->NIS }} | {{ $s->Rombel }}
                </option>
                @endforeach
            </select>
            <div style="font-size:11.5px;color:var(--muted);margin-top:5px"><i class="fas fa-info-circle"></i> Atau gunakan barcode KTP/kartu siswa</div>
        </div>
        <div class="form-group">
            <label>Buku yang Dipinjam *</label>
            <select name="BukuID" required>
                <option value="">— Pilih Buku —</option>
                @foreach($buku as $b)
                <option value="{{ $b->BukuID }}" {{ old('BukuID')==$b->BukuID ? 'selected' : '' }}>
                    {{ $b->Judul }} — {{ $b->Penulis }} (Stok: {{ $b->JumlahTersedia }})
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Tanggal Peminjaman *</label>
                <input type="date" name="TanggalPeminjaman" value="{{ old('TanggalPeminjaman', date('Y-m-d')) }}" required>
            </div>
            <div class="form-group">
                <label>Jatuh Tempo *</label>
                <input type="date" name="TanggalJatuhTempo" value="{{ old('TanggalJatuhTempo', date('Y-m-d', strtotime('+14 days'))) }}" required>
            </div>
        </div>
        <div class="form-group">
            <label>Catatan</label>
            <textarea name="Catatan" rows="2" placeholder="Catatan tambahan...">{{ old('Catatan') }}</textarea>
        </div>
        <div style="background:rgba(201,168,76,.08);border:1px solid rgba(201,168,76,.2);border-radius:10px;padding:14px;margin-bottom:18px;font-size:13px">
            <i class="fas fa-info-circle" style="color:var(--accent)"></i>
            <strong style="color:var(--accent)">Info Denda:</strong>
            <span style="color:var(--muted)"> Keterlambatan: Rp 1.000/hari · Rusak: Rp 25.000 · Hilang: Rp 100.000</span>
        </div>
        <div style="display:flex;gap:10px;justify-content:flex-end">
            <a href="{{ route('peminjaman.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Catat Peminjaman</button>
        </div>
    </form>
</div>
</div>
@endsection
