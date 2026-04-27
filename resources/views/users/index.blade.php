@extends('layouts.app')
@section('title', 'Manajemen User')
@section('content')

<div class="page-header">
    <h1 class="page-title">👥 Manajemen User</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah User</a>
</div>

<form method="GET" class="search-bar">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, username, NIS, email...">
    <select name="role">
        <option value="">Semua Role</option>
        <option value="admin" {{ request('role')==='admin'?'selected':'' }}>Admin</option>
        <option value="petugas" {{ request('role')==='petugas'?'selected':'' }}>Petugas</option>
        <option value="siswa" {{ request('role')==='siswa'?'selected':'' }}>Siswa</option>
    </select>
    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
    @if(request()->hasAny(['search','role']))
    <a href="{{ route('users.index') }}" class="btn btn-outline"><i class="fas fa-times"></i></a>
    @endif
</form>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Avatar</th><th>Nama & Username</th><th>Role</th><th>NIS / Rombel</th><th>Email</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($users as $u)
            <tr>
                <td>
                    <div class="user-avatar" style="width:36px;height:36px;font-size:13px">{{ strtoupper(substr($u->NamaLengkap,0,1)) }}</div>
                </td>
                <td>
                    <div style="font-weight:600;font-size:13.5px">{{ $u->NamaLengkap }}</div>
                    <div style="font-size:11px;color:var(--muted)">@{{ $u->Username }}</div>
                </td>
                <td><span class="role-badge role-{{ $u->Role }}">{{ $u->Role }}</span></td>
                <td style="font-size:12px;color:var(--muted)">
                    {{ $u->NIS ?? '—' }}<br>{{ $u->Rombel ?? '—' }}
                </td>
                <td style="font-size:12px">{{ $u->Email ?? '—' }}</td>
                <td>
                    @if($u->Status==='aktif') <span class="badge badge-green">Aktif</span>
                    @else <span class="badge badge-red">Nonaktif</span>
                    @endif
                </td>
                <td>
                    <div style="display:flex;gap:6px">
                        <a href="{{ route('users.edit', $u->UserID) }}" class="btn btn-outline btn-sm"><i class="fas fa-edit"></i></a>
                        @if($u->UserID != auth()->id())
                        <form method="POST" action="{{ route('users.destroy', $u->UserID) }}" onsubmit="return confirm('Hapus user {{ $u->NamaLengkap }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7"><div class="empty-state"><i class="fas fa-users"></i><p>Tidak ada user ditemukan</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $users->links() }}</div>
</div>
@endsection
