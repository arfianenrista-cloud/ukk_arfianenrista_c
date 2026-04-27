@extends('layouts.app')
@section('title', 'Peminjaman Buku')
@section('content')

<div class="page-header">
    <h1 class="page-title">🔄 {{ auth()->user()->isSiswa() ? 'Riwayat Pinjaman Saya' : 'Manajemen Peminjaman' }}</h1>
    @if(auth()->user()->isStaff())
    <a href="{{ route('peminjaman.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Catat Peminjaman</a>
    @endif
</div>

@if(auth()->user()->isStaff())
<form method="GET" class="search-bar">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa, NIS, judul buku...">
    <select name="status">
        <option value="">Semua Status</option>
        <option value="dipinjam" {{ request('status')==='dipinjam'?'selected':'' }}>Dipinjam</option>
        <option value="terlambat" {{ request('status')==='terlambat'?'selected':'' }}>Terlambat</option>
        <option value="dikembalikan" {{ request('status')==='dikembalikan'?'selected':'' }}>Dikembalikan</option>
        <option value="hilang" {{ request('status')==='hilang'?'selected':'' }}>Hilang</option>
        <option value="rusak" {{ request('status')==='rusak'?'selected':'' }}>Rusak</option>
    </select>
    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
    @if(request()->hasAny(['search','status']))
    <a href="{{ route('peminjaman.index') }}" class="btn btn-outline"><i class="fas fa-times"></i></a>
    @endif
</form>
@endif

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#ID</th>
                    @if(auth()->user()->isStaff()) <th>Siswa</th> @endif
                    <th>Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                    <th>Denda</th>
                    @if(auth()->user()->isStaff()) <th>Aksi</th> @endif
                </tr>
            </thead>
            <tbody>
            @forelse($peminjaman as $p)
            @php $late = $p->isTerlambat(); @endphp
            <tr style="{{ $late ? 'background:rgba(224,82,82,.04)' : '' }}">
                <td><code>{{ $p->PeminjamanID }}</code></td>
                @if(auth()->user()->isStaff())
                <td>
                    <div style="font-weight:600;font-size:13px">{{ $p->user?->NamaLengkap }}</div>
                    <div style="font-size:11px;color:var(--muted)">{{ $p->user?->NIS }} · {{ $p->user?->Rombel }}</div>
                </td>
                @endif
                <td style="max-width:200px">
                    <div style="font-weight:500;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $p->buku?->Judul }}</div>
                    <div style="font-size:11px;color:var(--muted)">{{ $p->buku?->Barcode }}</div>
                </td>
                <td style="font-size:12px">{{ $p->TanggalPeminjaman?->format('d M Y') }}</td>
                <td style="font-size:12px{{ $late ? ';color:var(--red);font-weight:700' : '' }}">
                    {{ $p->TanggalJatuhTempo?->format('d M Y') }}
                    @if($late) <br><small>+{{ $p->hari_terlambat }} hari</small> @endif
                </td>
                <td style="font-size:12px;color:var(--muted)">
                    {{ $p->TanggalPengembalian ? $p->TanggalPengembalian->format('d M Y') : '—' }}
                </td>
                <td>
                    @php $st = $p->StatusPeminjaman; @endphp
                    @if($st==='dipinjam') <span class="badge badge-blue">Dipinjam</span>
                    @elseif($st==='terlambat') <span class="badge badge-red"><i class="fas fa-exclamation-circle"></i> Terlambat</span>
                    @elseif($st==='dikembalikan') <span class="badge badge-green">Dikembalikan</span>
                    @elseif($st==='hilang') <span class="badge badge-orange">Hilang</span>
                    @elseif($st==='rusak') <span class="badge badge-orange">Rusak</span>
                    @endif
                </td>
                <td>
                    @if($p->Denda > 0)
                        <span style="font-weight:700;color:{{ $p->StatusDenda==='sudah_bayar' ? 'var(--green)' : 'var(--red)' }}">
                            Rp {{ number_format($p->Denda) }}
                        </span>
                        <br>
                        @if($p->StatusDenda==='sudah_bayar') <span class="badge badge-green" style="font-size:10px">Lunas</span>
                        @elseif($p->StatusDenda==='belum_bayar') <span class="badge badge-red" style="font-size:10px">Belum Bayar</span>
                        @endif
                    @else <span style="color:var(--muted)">—</span>
                    @endif
                </td>
                @if(auth()->user()->isStaff())
                <td>
                    <div style="display:flex;gap:6px;flex-wrap:wrap">
                        @if(in_array($p->StatusPeminjaman, ['dipinjam','terlambat']))
                        <button onclick="openModal('modal-kembali-{{ $p->PeminjamanID }}')" class="btn btn-green btn-sm">
                            <i class="fas fa-undo"></i> Kembalikan
                        </button>
                        @endif
                        @if($p->StatusDenda==='belum_bayar' && $p->Denda > 0)
                        <form method="POST" action="{{ route('peminjaman.bayar-denda', $p->PeminjamanID) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm" style="background:rgba(201,168,76,.15);color:var(--accent);border:1px solid rgba(201,168,76,.3)">
                                <i class="fas fa-money-bill"></i> Bayar Denda
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
                @endif
            </tr>
            @empty
            <tr><td colspan="9"><div class="empty-state"><i class="fas fa-exchange-alt"></i><p>Belum ada data peminjaman</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $peminjaman->links() }}</div>
</div>

{{-- MODAL KEMBALIKAN --}}
@if(auth()->user()->isStaff())
@foreach($peminjaman as $p)
@if(in_array($p->StatusPeminjaman, ['dipinjam','terlambat']))
<div class="modal-backdrop" id="modal-kembali-{{ $p->PeminjamanID }}">
    <div class="modal">
        <button class="modal-close" onclick="closeModal('modal-kembali-{{ $p->PeminjamanID }}')">×</button>
        <div class="modal-title">🔄 Pengembalian Buku</div>
        <div style="background:var(--bg);border-radius:10px;padding:14px;margin-bottom:18px;border:1px solid var(--border)">
            <div style="font-weight:600;margin-bottom:4px">{{ $p->buku?->Judul }}</div>
            <div style="font-size:13px;color:var(--muted)">Peminjam: {{ $p->user?->NamaLengkap }}</div>
            <div style="font-size:13px;color:var(--muted)">Jatuh Tempo: <span style="{{ $p->isTerlambat() ? 'color:var(--red);font-weight:700' : '' }}">{{ $p->TanggalJatuhTempo?->format('d M Y') }}</span>
            @if($p->isTerlambat()) <span style="color:var(--red)"> (+{{ $p->hari_terlambat }} hari terlambat — denda Rp {{ number_format($p->hari_terlambat * 1000) }})</span> @endif
            </div>
        </div>
        <form method="POST" action="{{ route('peminjaman.kembalikan', $p->PeminjamanID) }}">
            @csrf
            <div class="form-group">
                <label>Kondisi Buku Saat Dikembalikan *</label>
                <select name="kondisi" required>
                    <option value="baik">✅ Baik — tanpa kerusakan</option>
                    <option value="rusak">⚠️ Rusak — denda Rp 25.000</option>
                    <option value="hilang">❌ Hilang — denda Rp 100.000</option>
                </select>
            </div>
            <div class="form-group">
                <label>Catatan (opsional)</label>
                <textarea name="Catatan" rows="2" placeholder="Catatan tambahan..."></textarea>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end">
                <button type="button" onclick="closeModal('modal-kembali-{{ $p->PeminjamanID }}')" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">Konfirmasi Pengembalian</button>
            </div>
        </form>
    </div>
</div>
@endif
@endforeach
@endif

@endsection

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
</script>
@endpush
