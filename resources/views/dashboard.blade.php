@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
@if($user->isStaff())
{{-- ─────────── STAFF / ADMIN DASHBOARD ─────────── --}}

{{-- Alert terlambat --}}
@if(!empty($data['alertTerlambat']) && count($data['alertTerlambat']) > 0)
<div class="alert alert-red-alarm">
    <i class="fas fa-exclamation-triangle"></i>
    <strong>{{ count($data['alertTerlambat']) }} peminjaman melewati batas waktu pengembalian!</strong>
    &nbsp;— Segera tindak lanjuti.
</div>
@endif

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon gold"><i class="fas fa-book"></i></div>
        <div><div class="stat-value">{{ $data['totalBuku'] }}</div><div class="stat-label">Total Judul Buku</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-users"></i></div>
        <div><div class="stat-value">{{ $data['totalAnggota'] }}</div><div class="stat-label">Total Anggota</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-book-reader"></i></div>
        <div><div class="stat-value">{{ $data['totalDipinjam'] }}</div><div class="stat-label">Sedang Dipinjam</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-clock"></i></div>
        <div><div class="stat-value">{{ $data['totalTerlambat'] }}</div><div class="stat-label">Terlambat</div></div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px" class="dashboard-grid">
    {{-- Peminjaman terbaru --}}
    <div class="card" style="grid-column: 1 / -1">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
            <div class="card-title" style="margin:0">Transaksi Peminjaman Terbaru</div>
            <a href="{{ route('peminjaman.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr>
                    <th>#</th><th>Siswa</th><th>Buku</th><th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Status</th>
                </tr></thead>
                <tbody>
                @forelse($data['peminjamanTerbaru'] as $p)
                <tr>
                    <td><code>{{ $p->PeminjamanID }}</code></td>
                    <td>
                        <div style="font-weight:600;font-size:13px">{{ $p->user?->NamaLengkap ?? '-' }}</div>
                        <div style="font-size:11px;color:var(--muted)">{{ $p->user?->Rombel }}</div>
                    </td>
                    <td style="max-width:200px">
                        <div style="font-weight:500;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $p->buku?->Judul ?? '-' }}</div>
                    </td>
                    <td style="font-size:12px">{{ $p->TanggalPeminjaman?->format('d M Y') }}</td>
                    <td style="font-size:12px;{{ $p->isTerlambat() ? 'color:var(--red);font-weight:600' : '' }}">
                        {{ $p->TanggalJatuhTempo?->format('d M Y') }}
                        @if($p->isTerlambat()) <i class="fas fa-exclamation-circle"></i> @endif
                    </td>
                    <td>
                        @php $st = $p->StatusPeminjaman; @endphp
                        @if($st==='dipinjam') <span class="badge badge-blue">Dipinjam</span>
                        @elseif($st==='terlambat') <span class="badge badge-red">Terlambat</span>
                        @elseif($st==='dikembalikan') <span class="badge badge-green">Kembali</span>
                        @elseif($st==='hilang') <span class="badge badge-orange">Hilang</span>
                        @elseif($st==='rusak') <span class="badge badge-orange">Rusak</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:30px">Belum ada transaksi</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Activity Log --}}
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
        <div class="card-title" style="margin:0">🗂️ Activity Log Terbaru</div>
        @if($user->isAdmin()) <a href="{{ route('logs.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a> @endif
    </div>
    <div class="table-wrap">
        <table class="log-table">
            <thead><tr><th>Waktu</th><th>Pelaku</th><th>Role</th><th>Kegiatan</th><th>Keterangan</th></tr></thead>
            <tbody>
            @forelse($data['recentLogs'] as $log)
            <tr>
                <td style="font-size:11px;white-space:nowrap;font-family:monospace;color:var(--muted)">{{ $log->created_at?->format('d-M-Y H:i') }}</td>
                <td style="font-size:13px;font-weight:500">{{ $log->NamaPelaku ?? 'System' }}</td>
                <td><span class="badge {{ $log->RolePelaku==='admin' ? 'badge-gold' : ($log->RolePelaku==='petugas' ? 'badge-blue' : 'badge-green') }}">{{ $log->RolePelaku }}</span></td>
                <td>
                    @php $k = strtoupper($log->Kegiatan); @endphp
                    <span class="{{ str_contains($k,'LOGIN') && !str_contains($k,'GAGAL') ? 'kegiatan-login' : (str_contains($k,'LOGOUT') ? 'kegiatan-logout' : (str_contains($k,'HAPUS') || str_contains($k,'DELETE') ? 'kegiatan-hapus' : 'kegiatan-transaksi')) }}" style="font-size:12px;font-weight:600">
                        {{ $log->Kegiatan }}
                    </span>
                </td>
                <td style="font-size:12px;color:var(--muted);max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $log->Keterangan }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:20px">Belum ada log</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@else
{{-- ─────────── SISWA DASHBOARD ─────────── --}}

@if(isset($data['totalDenda']) && $data['totalDenda'] > 0)
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle"></i>
    Kamu memiliki <strong>denda belum dibayar: Rp {{ number_format($data['totalDenda']) }}</strong>. Segera hubungi petugas!
</div>
@endif

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-book-reader"></i></div>
        <div>
            <div class="stat-value">{{ count($data['peminjamanAktif']) }}</div>
            <div class="stat-label">Sedang Dipinjam</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon gold"><i class="fas fa-history"></i></div>
        <div>
            <div class="stat-value">{{ $user->peminjaman()->count() }}</div>
            <div class="stat-label">Total Riwayat Pinjam</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon {{ $data['totalDenda'] > 0 ? 'red' : 'green' }}"><i class="fas fa-money-bill"></i></div>
        <div>
            <div class="stat-value" style="{{ $data['totalDenda'] > 0 ? 'color:var(--red)' : '' }}">Rp {{ number_format($data['totalDenda']) }}</div>
            <div class="stat-label">Denda Belum Bayar</div>
        </div>
    </div>
</div>

{{-- Peminjaman aktif --}}
@if(count($data['peminjamanAktif']) > 0)
<div class="card" style="margin-bottom:20px">
    <div class="card-title">📖 Buku yang Sedang Dipinjam</div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Buku</th><th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Status</th><th>Denda</th></tr></thead>
            <tbody>
            @foreach($data['peminjamanAktif'] as $p)
            <tr>
                <td>
                    <div style="font-weight:600">{{ $p->buku?->Judul }}</div>
                    <div style="font-size:11px;color:var(--muted)">{{ $p->buku?->Penulis }}</div>
                </td>
                <td style="font-size:12px">{{ $p->TanggalPeminjaman?->format('d M Y') }}</td>
                <td style="font-size:12px;{{ $p->isTerlambat() ? 'color:var(--red);font-weight:700' : '' }}">
                    {{ $p->TanggalJatuhTempo?->format('d M Y') }}
                    @if($p->isTerlambat()) <br><small>{{ $p->hari_terlambat }} hari terlambat</small> @endif
                </td>
                <td>@if($p->StatusPeminjaman==='terlambat')<span class="badge badge-red">Terlambat</span>@else<span class="badge badge-blue">Dipinjam</span>@endif</td>
                <td style="color:{{ $p->Denda > 0 ? 'var(--red)' : 'var(--muted)' }};font-weight:600">
                    {{ $p->Denda > 0 ? 'Rp '.number_format($p->Denda) : '-' }}
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Buku tersedia --}}
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
        <div class="card-title" style="margin:0">📚 Koleksi Terbaru</div>
        <a href="{{ route('buku.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
    </div>
    <div class="book-grid">
        @foreach($data['bukuTersedia'] as $buku)
        <a href="{{ route('buku.show', $buku->BukuID) }}" style="text-decoration:none;color:inherit">
        <div class="book-card">
            <div class="book-cover">
                <div class="book-spine"></div>
                @if($buku->CoverImage)
                    <img src="{{ asset('storage/'.$buku->CoverImage) }}" alt="">
                @else
                    <i class="fas fa-book" style="position:relative;z-index:1"></i>
                @endif
            </div>
            <div class="book-info">
                <div class="book-title">{{ Str::limit($buku->Judul, 40) }}</div>
                <div class="book-author">{{ $buku->Penulis }}</div>
                <span class="badge badge-green"><i class="fas fa-circle" style="font-size:7px"></i> Tersedia ({{ $buku->JumlahTersedia }})</span>
            </div>
        </div>
        </a>
        @endforeach
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
@media(max-width:768px){.dashboard-grid{grid-template-columns:1fr!important}}
</style>
@endpush
