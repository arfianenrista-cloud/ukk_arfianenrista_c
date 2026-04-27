@extends('layouts.app')
@section('title', 'Activity Log')
@section('content')

<div class="page-header">
    <h1 class="page-title">🗂️ Activity Log — Tabel Sakti</h1>
</div>

<form method="GET" class="search-bar">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pelaku, kegiatan, keterangan...">
    <select name="role">
        <option value="">Semua Role</option>
        <option value="admin" {{ request('role')==='admin'?'selected':'' }}>Admin</option>
        <option value="petugas" {{ request('role')==='petugas'?'selected':'' }}>Petugas</option>
        <option value="siswa" {{ request('role')==='siswa'?'selected':'' }}>Siswa</option>
        <option value="system" {{ request('role')==='system'?'selected':'' }}>System</option>
    </select>
    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
    @if(request()->hasAny(['search','role']))
    <a href="{{ route('logs.index') }}" class="btn btn-outline"><i class="fas fa-times"></i></a>
    @endif
</form>

<div class="card">
    <div class="table-wrap">
        <table class="log-table">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Pelaku / ID</th>
                    <th>Role</th>
                    <th>Kegiatan</th>
                    <th>Keterangan</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
            @forelse($logs as $log)
            @php $k = strtoupper($log->Kegiatan); @endphp
            <tr>
                <td style="font-family:'JetBrains Mono',monospace;font-size:11.5px;white-space:nowrap;color:var(--muted)">
                    {{ $log->created_at?->format('d-M-Y H:i:s') }}
                </td>
                <td>
                    <div style="font-weight:600;font-size:13px">{{ $log->NamaPelaku ?? 'System' }}</div>
                    @if($log->UserID) <div style="font-size:10px;color:var(--muted)">UID: {{ $log->UserID }}</div> @endif
                </td>
                <td>
                    <span class="badge {{ $log->RolePelaku==='admin' ? 'badge-gold' : ($log->RolePelaku==='petugas' ? 'badge-blue' : ($log->RolePelaku==='siswa' ? 'badge-green' : 'badge-gray')) }}">
                        {{ $log->RolePelaku ?? '—' }}
                    </span>
                </td>
                <td>
                    <span style="font-size:12.5px;font-weight:600;
                        {{ str_contains($k,'LOGIN') && !str_contains($k,'GAGAL') ? 'color:var(--green)' : '' }}
                        {{ str_contains($k,'LOGOUT') ? 'color:var(--blue)' : '' }}
                        {{ str_contains($k,'HAPUS') || str_contains($k,'DELETE') ? 'color:var(--red)' : '' }}
                        {{ str_contains($k,'TRANSAKSI') || str_contains($k,'PEMINJAMAN') ? 'color:var(--accent)' : '' }}
                        {{ str_contains($k,'PENGEMBALIAN') ? 'color:var(--green)' : '' }}
                        {{ str_contains($k,'DENDA') ? 'color:var(--orange)' : '' }}
                        {{ str_contains($k,'GAGAL') ? 'color:var(--red)' : '' }}
                    ">
                        {{ $log->Kegiatan }}
                    </span>
                </td>
                <td style="font-size:12px;color:var(--muted);max-width:250px">{{ $log->Keterangan ?? '—' }}</td>
                <td style="font-family:monospace;font-size:11px;color:var(--muted)">{{ $log->IPAddress ?? '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="6"><div class="empty-state"><i class="fas fa-history"></i><p>Belum ada activity log</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $logs->links() }}</div>
</div>
@endsection
