@extends('layouts.app')
@section('title', 'Laporan')
@section('content')

<div class="page-header">
    <h1 class="page-title">📊 Generate Laporan</h1>
</div>

{{-- Stats ringkasan --}}
<div class="stat-grid" style="margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-book-reader"></i></div>
        <div><div class="stat-value">{{ $stats['totalPeminjaman'] }}</div><div class="stat-label">Total Peminjaman (Periode)</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-undo"></i></div>
        <div><div class="stat-value">{{ $stats['totalKembali'] }}</div><div class="stat-label">Dikembalikan (Periode)</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-clock"></i></div>
        <div><div class="stat-value">{{ $stats['totalTerlambat'] }}</div><div class="stat-label">Saat Ini Terlambat</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-money-bill-wave"></i></div>
        <div>
            <div class="stat-value" style="font-size:18px">Rp {{ number_format($stats['totalDendaBelum']) }}</div>
            <div class="stat-label">Denda Belum Dibayar</div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card" style="margin-bottom:20px">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
        <div style="flex:1;min-width:160px">
            <label style="display:block;font-size:12px;color:var(--muted);margin-bottom:6px">Jenis Laporan</label>
            <select name="tipe">
                <option value="peminjaman" {{ $tipe==='peminjaman'?'selected':'' }}>Peminjaman</option>
                <option value="pengembalian" {{ $tipe==='pengembalian'?'selected':'' }}>Pengembalian</option>
                <option value="terlambat" {{ $tipe==='terlambat'?'selected':'' }}>Keterlambatan</option>
                <option value="hilang" {{ $tipe==='hilang'?'selected':'' }}>Buku Hilang</option>
                <option value="rusak" {{ $tipe==='rusak'?'selected':'' }}>Buku Rusak</option>
                <option value="denda" {{ $tipe==='denda'?'selected':'' }}>Denda</option>
                <option value="stok" {{ $tipe==='stok'?'selected':'' }}>Stok Buku</option>
            </select>
        </div>
        @if($tipe !== 'terlambat' && $tipe !== 'stok' && $tipe !== 'hilang' && $tipe !== 'rusak')
        <div style="flex:1;min-width:140px">
            <label style="display:block;font-size:12px;color:var(--muted);margin-bottom:6px">Dari Tanggal</label>
            <input type="date" name="dari" value="{{ $dari }}">
        </div>
        <div style="flex:1;min-width:140px">
            <label style="display:block;font-size:12px;color:var(--muted);margin-bottom:6px">Sampai Tanggal</label>
            <input type="date" name="sampai" value="{{ $sampai }}">
        </div>
        @endif
        <button type="submit" class="btn btn-primary"><i class="fas fa-chart-bar"></i> Generate</button>
        <button type="button" onclick="window.print()" class="btn btn-outline"><i class="fas fa-print"></i> Cetak</button>
    </form>
</div>

{{-- Hasil laporan --}}
<div class="card" id="print-area">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
        <div>
            <div class="card-title" style="margin:0;text-transform:capitalize">Laporan {{ $tipe }}</div>
            @if($tipe !== 'terlambat' && $tipe !== 'stok' && $tipe !== 'hilang' && $tipe !== 'rusak')
            <div style="font-size:12px;color:var(--muted);margin-top:3px">{{ \Carbon\Carbon::parse($dari)->format('d M Y') }} — {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}</div>
            @endif
        </div>
        <div style="font-size:13px;color:var(--muted)">Total: <strong style="color:var(--text)">{{ count($data) }}</strong> record</div>
    </div>

    @if($tipe === 'stok')
    {{-- Stok buku --}}
    <div class="table-wrap">
        <table>
            <thead><tr><th>Barcode</th><th>Judul</th><th>Penulis</th><th>Kategori</th><th>Total</th><th>Tersedia</th><th>Status</th><th>Kondisi</th></tr></thead>
            <tbody>
            @forelse($data as $b)
            <tr>
                <td><code>{{ $b->Barcode }}</code></td>
                <td style="font-weight:500">{{ $b->Judul }}</td>
                <td style="color:var(--muted);font-size:12px">{{ $b->Penulis }}</td>
                <td>@foreach($b->kategori as $k)<span class="badge badge-gold" style="margin:1px;font-size:10px">{{ $k->NamaKategori }}</span>@endforeach</td>
                <td style="text-align:center;font-weight:700">{{ $b->JumlahTotal }}</td>
                <td style="text-align:center;font-weight:700;color:{{ $b->JumlahTersedia > 0 ? 'var(--green)' : 'var(--red)' }}">{{ $b->JumlahTersedia }}</td>
                <td>@if($b->Status==='tersedia')<span class="badge badge-green">Tersedia</span>@else<span class="badge badge-blue">Dipinjam</span>@endif</td>
                <td>@if($b->Kondisi==='baik')<span class="badge badge-green">Baik</span>@elseif($b->Kondisi==='rusak')<span class="badge badge-orange">Rusak</span>@else<span class="badge badge-red">Hilang</span>@endif</td>
            </tr>
            @empty
            <tr><td colspan="8"><div class="empty-state"><i class="fas fa-book"></i><p>Tidak ada data</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @else
    {{-- Peminjaman-based reports --}}
    <div class="table-wrap">
        <table>
            <thead><tr>
                <th>#</th><th>Siswa</th><th>Rombel/Rayon</th><th>Buku</th><th>Tgl Pinjam</th><th>Jatuh Tempo</th>
                @if(in_array($tipe,['pengembalian','rusak','hilang'])) <th>Tgl Kembali</th> @endif
                <th>Status</th>
                @if(in_array($tipe,['denda','terlambat','rusak','hilang'])) <th>Denda</th> @endif
            </tr></thead>
            <tbody>
            @forelse($data as $p)
            <tr>
                <td><code>{{ $p->PeminjamanID }}</code></td>
                <td>
                    <div style="font-weight:600;font-size:13px">{{ $p->user?->NamaLengkap }}</div>
                    <div style="font-size:11px;color:var(--muted)">NIS: {{ $p->user?->NIS }}</div>
                </td>
                <td style="font-size:12px;color:var(--muted)">{{ $p->user?->Rombel }}<br>Rayon {{ $p->user?->Rayon }}</td>
                <td style="max-width:180px">
                    <div style="font-size:13px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $p->buku?->Judul }}</div>
                </td>
                <td style="font-size:12px">{{ $p->TanggalPeminjaman?->format('d M Y') }}</td>
                <td style="font-size:12px;{{ $p->isTerlambat() ? 'color:var(--red);font-weight:700' : '' }}">{{ $p->TanggalJatuhTempo?->format('d M Y') }}</td>
                @if(in_array($tipe,['pengembalian','rusak','hilang']))
                <td style="font-size:12px">{{ $p->TanggalPengembalian?->format('d M Y') ?? '—' }}</td>
                @endif
                <td>
                    @php $st = $p->StatusPeminjaman; @endphp
                    @if($st==='dipinjam') <span class="badge badge-blue">Dipinjam</span>
                    @elseif($st==='terlambat') <span class="badge badge-red">Terlambat</span>
                    @elseif($st==='dikembalikan') <span class="badge badge-green">Kembali</span>
                    @elseif($st==='hilang') <span class="badge badge-orange">Hilang</span>
                    @elseif($st==='rusak') <span class="badge badge-orange">Rusak</span>
                    @endif
                </td>
                @if(in_array($tipe,['denda','terlambat','rusak','hilang']))
                <td>
                    <span style="font-weight:700;color:{{ $p->Denda>0?'var(--red)':'var(--muted)' }}">
                        {{ $p->Denda > 0 ? 'Rp '.number_format($p->Denda) : '—' }}
                    </span>
                    @if($p->StatusDenda==='sudah_bayar') <span class="badge badge-green" style="font-size:10px">Lunas</span> @endif
                </td>
                @endif
            </tr>
            @empty
            <tr><td colspan="10"><div class="empty-state"><i class="fas fa-chart-bar"></i><p>Tidak ada data untuk periode ini</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
@push('styles')
<style>
@media print {
    .sidebar, .topbar, form, .page-header .btn { display: none !important; }
    .main { margin-left: 0 !important; }
    .content { padding: 0 !important; }
    body { background: white !important; color: black !important; }
    .card { border: 1px solid #ccc !important; background: white !important; }
    td, th { color: black !important; border-color: #ccc !important; }
}
</style>
@endpush
