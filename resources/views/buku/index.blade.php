@extends('layouts.app')
@section('title', 'Koleksi Buku')
@section('content')

<div class="page-header">
    <h1 class="page-title">📚 Koleksi Buku</h1>
    @if(auth()->user()->isStaff())
    <a href="{{ route('buku.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Buku</a>
    @endif
</div>

<form method="GET" class="search-bar">
    <div style="position:relative;flex:2;min-width:200px">
        <i class="fas fa-search" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:13px"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, penulis, ISBN, barcode..." style="padding-left:38px">
    </div>
    <select name="kategori" style="flex:1;min-width:150px">
        <option value="">Semua Kategori</option>
        @foreach($kategori as $k)
        <option value="{{ $k->KategoriID }}" {{ request('kategori')==$k->KategoriID ? 'selected' : '' }}>{{ $k->NamaKategori }}</option>
        @endforeach
    </select>
    <select name="status" style="flex:1;min-width:130px">
        <option value="">Semua Status</option>
        <option value="tersedia" {{ request('status')==='tersedia' ? 'selected' : '' }}>Tersedia</option>
        <option value="dipinjam" {{ request('status')==='dipinjam' ? 'selected' : '' }}>Dipinjam</option>
    </select>
    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
    @if(request()->hasAny(['search','kategori','status']))
    <a href="{{ route('buku.index') }}" class="btn btn-outline"><i class="fas fa-times"></i></a>
    @endif
</form>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Barcode</th>
                    <th>Judul & Penulis</th>
                    <th>Kategori</th>
                    <th>Tahun</th>
                    <th>Stok</th>
                    <th>Kondisi</th>
                    <th>Status</th>
                    @if(auth()->user()->isStaff()) <th>Aksi</th> @endif
                </tr>
            </thead>
            <tbody>
            @forelse($buku as $b)
            <tr>
                <td><code>{{ $b->Barcode ?? '-' }}</code></td>
                <td>
                    <a href="{{ route('buku.show', $b->BukuID) }}" style="color:var(--text);text-decoration:none">
                        <div style="font-weight:600;font-size:13.5px">{{ $b->Judul }}</div>
                        <div style="font-size:11.5px;color:var(--muted)">{{ $b->Penulis }} @if($b->Penerbit) · {{ $b->Penerbit }} @endif</div>
                    </a>
                </td>
                <td>
                    @foreach($b->kategori as $k)
                        <span class="badge badge-gold" style="margin:1px">{{ $k->NamaKategori }}</span>
                    @endforeach
                </td>
                <td style="font-size:12px">{{ $b->TahunTerbit ?? '-' }}</td>
                <td>
                    <span style="font-weight:700;color:{{ $b->JumlahTersedia > 0 ? 'var(--green)' : 'var(--red)' }}">{{ $b->JumlahTersedia }}</span>
                    <span style="color:var(--muted);font-size:11px"> / {{ $b->JumlahTotal }}</span>
                </td>
                <td>
                    @if($b->Kondisi==='baik') <span class="badge badge-green">Baik</span>
                    @elseif($b->Kondisi==='rusak') <span class="badge badge-orange">Rusak</span>
                    @else <span class="badge badge-red">Hilang</span>
                    @endif
                </td>
                <td>
                    @if($b->Status==='tersedia') <span class="badge badge-green"><i class="fas fa-circle" style="font-size:7px"></i> Tersedia</span>
                    @else <span class="badge badge-blue">Dipinjam</span>
                    @endif
                </td>
                @if(auth()->user()->isStaff())
                <td>
                    <div style="display:flex;gap:6px;flex-wrap:wrap">
                        <a href="{{ route('buku.edit', $b->BukuID) }}" class="btn btn-outline btn-sm"><i class="fas fa-edit"></i></a>
                        @if(auth()->user()->isAdmin())
                        <form method="POST" action="{{ route('buku.destroy', $b->BukuID) }}" onsubmit="return confirm('Hapus buku ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </div>
                </td>
                @endif
            </tr>
            @empty
            <tr><td colspan="8">
                <div class="empty-state"><i class="fas fa-book-open"></i><p>Tidak ada buku ditemukan</p></div>
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $buku->links() }}</div>
</div>
@endsection
