@extends('layouts.app')
@section('title', $buku->Judul)
@section('content')

<div class="page-header">
    <h1 class="page-title" style="font-size:20px">Detail Buku</h1>
    <a href="{{ route('buku.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div style="display:grid;grid-template-columns:auto 1fr;gap:24px;align-items:start" class="buku-detail-grid">
    {{-- Cover --}}
    <div style="width:200px;flex-shrink:0">
        <div style="background:linear-gradient(135deg,#1f2535,#252d42);border-radius:12px;height:260px;display:flex;align-items:center;justify-content:center;overflow:hidden;border:1px solid var(--border);position:relative">
            <div style="position:absolute;left:0;top:0;bottom:0;width:8px;background:var(--accent);border-radius:12px 0 0 12px"></div>
            @if($buku->CoverImage)
                <img src="{{ asset('storage/'.$buku->CoverImage) }}" style="width:100%;height:100%;object-fit:cover">
            @else
                <i class="fas fa-book" style="font-size:60px;color:var(--muted);opacity:.3"></i>
            @endif
        </div>
        <div style="margin-top:12px;display:flex;gap:8px;flex-direction:column">
            @if($buku->JumlahTersedia > 0)
                @if(auth()->user()->isSiswa())
                <form method="POST" action="{{ route('peminjaman.request') }}">
                    @csrf
                    <input type="hidden" name="BukuID" value="{{ $buku->BukuID }}">
                    <button type="submit" class="btn btn-primary" style="width:100%"><i class="fas fa-hand-holding-heart"></i> Minta Pinjam</button>
                </form>
                @endif
            @else
                <div class="alert alert-error" style="text-align:center;justify-content:center">Stok Habis</div>
            @endif
            @if(auth()->user()->isStaff())
                <a href="{{ route('buku.edit', $buku->BukuID) }}" class="btn btn-outline" style="text-align:center;justify-content:center"><i class="fas fa-edit"></i> Edit Buku</a>
            @endif
        </div>
    </div>

    {{-- Info --}}
    <div>
        <div class="card" style="margin-bottom:16px">
            <h2 style="font-family:'Playfair Display',serif;font-size:22px;font-weight:700;margin-bottom:6px">{{ $buku->Judul }}</h2>
            <p style="color:var(--muted);font-size:15px;margin-bottom:14px">{{ $buku->Penulis }}</p>
            <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px">
                @foreach($buku->kategori as $k)
                    <span class="badge badge-gold">{{ $k->NamaKategori }}</span>
                @endforeach
                @if($buku->Status==='tersedia') <span class="badge badge-green">Tersedia</span>
                @else <span class="badge badge-blue">Dipinjam</span> @endif
                @if($buku->Kondisi==='rusak') <span class="badge badge-orange">Kondisi Rusak</span> @endif
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
                <div style="background:var(--bg);border-radius:8px;padding:12px;border:1px solid var(--border)">
                    <div style="font-size:11px;color:var(--muted);margin-bottom:4px">Penerbit</div>
                    <div style="font-size:13px;font-weight:600">{{ $buku->Penerbit ?? '-' }}</div>
                </div>
                <div style="background:var(--bg);border-radius:8px;padding:12px;border:1px solid var(--border)">
                    <div style="font-size:11px;color:var(--muted);margin-bottom:4px">Tahun Terbit</div>
                    <div style="font-size:13px;font-weight:600">{{ $buku->TahunTerbit ?? '-' }}</div>
                </div>
                <div style="background:var(--bg);border-radius:8px;padding:12px;border:1px solid var(--border)">
                    <div style="font-size:11px;color:var(--muted);margin-bottom:4px">Stok Tersedia</div>
                    <div style="font-size:13px;font-weight:700;color:{{ $buku->JumlahTersedia > 0 ? 'var(--green)' : 'var(--red)' }}">{{ $buku->JumlahTersedia }} / {{ $buku->JumlahTotal }}</div>
                </div>
                <div style="background:var(--bg);border-radius:8px;padding:12px;border:1px solid var(--border)">
                    <div style="font-size:11px;color:var(--muted);margin-bottom:4px">ISBN</div>
                    <div style="font-size:12px;font-family:monospace">{{ $buku->ISBN ?? '-' }}</div>
                </div>
                <div style="background:var(--bg);border-radius:8px;padding:12px;border:1px solid var(--border)">
                    <div style="font-size:11px;color:var(--muted);margin-bottom:4px">Barcode</div>
                    <div style="font-size:12px;font-family:monospace">{{ $buku->Barcode ?? '-' }}</div>
                </div>
                <div style="background:var(--bg);border-radius:8px;padding:12px;border:1px solid var(--border)">
                    <div style="font-size:11px;color:var(--muted);margin-bottom:4px">Rating</div>
                    <div style="font-size:13px;font-weight:600;color:var(--accent)">
                        ⭐ {{ $buku->rating }} / 5
                        <span style="color:var(--muted);font-size:11px">({{ $buku->ulasan->count() }} ulasan)</span>
                    </div>
                </div>
            </div>
            @if($buku->Deskripsi)
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border)">
                <div style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:8px">Deskripsi</div>
                <p style="font-size:14px;line-height:1.7;color:#b0b8cc">{{ $buku->Deskripsi }}</p>
            </div>
            @endif
        </div>

        {{-- Ulasan --}}
        @if($buku->ulasan->count() > 0)
        <div class="card">
            <div class="card-title">💬 Ulasan Pembaca</div>
            @foreach($buku->ulasan->take(5) as $u)
            <div style="padding:12px 0;border-bottom:1px solid var(--border);display:flex;gap:12px">
                <div class="user-avatar" style="width:32px;height:32px;font-size:12px;flex-shrink:0">{{ strtoupper(substr($u->user?->NamaLengkap??'?',0,1)) }}</div>
                <div>
                    <div style="font-size:13px;font-weight:600">{{ $u->user?->NamaLengkap }}</div>
                    <div style="color:var(--accent);font-size:13px;margin:2px 0">{{ str_repeat('⭐', $u->Rating) }}</div>
                    @if($u->Ulasan) <div style="font-size:13px;color:#b0b8cc;line-height:1.6">{{ $u->Ulasan }}</div> @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
@push('styles')
<style>@media(max-width:700px){.buku-detail-grid{grid-template-columns:1fr!important}}</style>
@endpush
