<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DigiLib') — Perpustakaan Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --bg:       #0d0f14;
            --surface:  #151820;
            --card:     #1b1f2b;
            --border:   #252936;
            --accent:   #c9a84c;
            --accent2:  #e8c96d;
            --red:      #e05252;
            --green:    #4caf7d;
            --blue:     #4c8be0;
            --orange:   #e07c4c;
            --text:     #e8eaf0;
            --muted:    #7c8196;
            --sidebar-w: 260px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* ─── SIDEBAR ─── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            transition: transform .3s ease;
        }
        .sidebar-logo {
            padding: 24px 20px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 12px;
        }
        .sidebar-logo .logo-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: #0d0f14; font-weight: 700;
        }
        .sidebar-logo .logo-text {
            font-family: 'Playfair Display', serif;
            font-size: 20px; font-weight: 700; color: var(--accent);
            line-height: 1.1;
        }
        .sidebar-logo .logo-sub { font-size: 11px; color: var(--muted); }
        .sidebar-nav { flex: 1; overflow-y: auto; padding: 12px 0; }
        .nav-section { padding: 16px 20px 6px; font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); font-weight: 600; }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 20px; color: var(--muted);
            text-decoration: none; font-size: 14px; font-weight: 500;
            border-left: 3px solid transparent;
            transition: all .2s;
        }
        .nav-item:hover { color: var(--text); background: rgba(255,255,255,.04); }
        .nav-item.active { color: var(--accent); border-left-color: var(--accent); background: rgba(201,168,76,.08); }
        .nav-item i { width: 18px; text-align: center; font-size: 14px; }
        .sidebar-user {
            padding: 16px 20px;
            border-top: 1px solid var(--border);
            display: flex; align-items: center; gap: 10px;
        }
        .user-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), #8b5e1a);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px; color: var(--bg); flex-shrink: 0;
        }
        .user-info { flex: 1; min-width: 0; }
        .user-name { font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role { font-size: 11px; color: var(--muted); text-transform: capitalize; }
        .role-badge {
            display: inline-block; padding: 1px 7px; border-radius: 20px; font-size: 10px; font-weight: 600; text-transform: uppercase;
        }
        .role-admin   { background: rgba(201,168,76,.2); color: var(--accent); }
        .role-petugas { background: rgba(76,139,224,.2); color: var(--blue); }
        .role-siswa   { background: rgba(76,175,125,.2); color: var(--green); }

        /* ─── MAIN ─── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1; display: flex; flex-direction: column; min-height: 100vh;
        }
        .topbar {
            height: 60px; background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 28px; position: sticky; top: 0; z-index: 50;
        }
        .topbar-title { font-size: 16px; font-weight: 600; }
        .topbar-actions { display: flex; align-items: center; gap: 16px; }
        .btn-logout {
            display: flex; align-items: center; gap: 6px; padding: 7px 14px;
            background: rgba(224,82,82,.12); color: var(--red); border: 1px solid rgba(224,82,82,.3);
            border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer;
            text-decoration: none; transition: all .2s;
        }
        .btn-logout:hover { background: rgba(224,82,82,.22); }
        .content { padding: 28px; flex: 1; }

        /* ─── ALERTS ─── */
        .alert {
            padding: 12px 16px; border-radius: 10px; margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px; font-size: 14px;
        }
        .alert-success { background: rgba(76,175,125,.12); border: 1px solid rgba(76,175,125,.3); color: #6dd4a0; }
        .alert-error   { background: rgba(224,82,82,.12);  border: 1px solid rgba(224,82,82,.3);  color: #f0908c; }
        .alert-info    { background: rgba(76,139,224,.12); border: 1px solid rgba(76,139,224,.3); color: #80b0f0; }
        .alert-warning { background: rgba(224,124,76,.12); border: 1px solid rgba(224,124,76,.3); color: #f0b07a; }
        .alert-red-alarm {
            background: rgba(224,82,82,.15); border: 1px solid var(--red);
            color: var(--red); animation: pulse-red 2s infinite;
        }
        @keyframes pulse-red {
            0%,100% { box-shadow: 0 0 0 0 rgba(224,82,82,.3); }
            50%      { box-shadow: 0 0 0 6px rgba(224,82,82,0); }
        }

        /* ─── CARDS ─── */
        .card {
            background: var(--card); border: 1px solid var(--border);
            border-radius: 14px; padding: 24px;
        }
        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 18px; font-weight: 600; margin-bottom: 18px;
            color: var(--text);
        }
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; margin-bottom: 28px; }
        .stat-card {
            background: var(--card); border: 1px solid var(--border);
            border-radius: 14px; padding: 20px;
            display: flex; align-items: center; gap: 16px;
            transition: transform .2s, border-color .2s;
        }
        .stat-card:hover { transform: translateY(-2px); border-color: var(--accent); }
        .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; flex-shrink: 0;
        }
        .stat-icon.gold   { background: rgba(201,168,76,.15); color: var(--accent); }
        .stat-icon.blue   { background: rgba(76,139,224,.15); color: var(--blue); }
        .stat-icon.green  { background: rgba(76,175,125,.15); color: var(--green); }
        .stat-icon.red    { background: rgba(224,82,82,.15);  color: var(--red); }
        .stat-icon.orange { background: rgba(224,124,76,.15); color: var(--orange); }
        .stat-value { font-size: 26px; font-weight: 700; line-height: 1; }
        .stat-label { font-size: 12px; color: var(--muted); margin-top: 3px; }

        /* ─── TABLES ─── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        th {
            background: rgba(255,255,255,.03); padding: 10px 14px;
            text-align: left; font-size: 11px; font-weight: 600;
            text-transform: uppercase; letter-spacing: .8px; color: var(--muted);
            border-bottom: 1px solid var(--border);
        }
        td { padding: 12px 14px; border-bottom: 1px solid rgba(255,255,255,.04); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255,255,255,.02); }

        /* ─── BADGES ─── */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;
        }
        .badge-green  { background: rgba(76,175,125,.15); color: var(--green); }
        .badge-red    { background: rgba(224,82,82,.15);  color: var(--red); }
        .badge-blue   { background: rgba(76,139,224,.15); color: var(--blue); }
        .badge-orange { background: rgba(224,124,76,.15); color: var(--orange); }
        .badge-gold   { background: rgba(201,168,76,.15); color: var(--accent); }
        .badge-gray   { background: rgba(124,129,150,.15); color: var(--muted); }

        /* ─── BUTTONS ─── */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 18px; border-radius: 9px; font-size: 13.5px;
            font-weight: 500; cursor: pointer; border: none;
            text-decoration: none; transition: all .2s; font-family: inherit;
        }
        .btn-primary { background: var(--accent); color: #0d0f14; }
        .btn-primary:hover { background: var(--accent2); }
        .btn-outline {
            background: transparent; color: var(--text);
            border: 1px solid var(--border);
        }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }
        .btn-danger { background: rgba(224,82,82,.15); color: var(--red); border: 1px solid rgba(224,82,82,.3); }
        .btn-danger:hover { background: rgba(224,82,82,.25); }
        .btn-sm { padding: 5px 12px; font-size: 12px; }
        .btn-green { background: rgba(76,175,125,.15); color: var(--green); border: 1px solid rgba(76,175,125,.3); }
        .btn-green:hover { background: rgba(76,175,125,.25); }

        /* ─── FORMS ─── */
        .form-group { margin-bottom: 18px; }
        label { display: block; font-size: 13px; font-weight: 500; color: var(--muted); margin-bottom: 6px; }
        input, select, textarea {
            width: 100%; padding: 10px 14px;
            background: var(--bg); border: 1px solid var(--border);
            border-radius: 9px; color: var(--text); font-family: inherit; font-size: 14px;
            transition: border-color .2s;
        }
        input:focus, select:focus, textarea:focus {
            outline: none; border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(201,168,76,.1);
        }
        .form-error { color: var(--red); font-size: 12px; margin-top: 5px; }
        .form-row { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 18px; }

        /* ─── PAGINATION ─── */
        .pagination { display: flex; gap: 6px; justify-content: center; margin-top: 24px; flex-wrap: wrap; }
        .pagination a, .pagination span {
            padding: 7px 12px; border-radius: 8px; font-size: 13px;
            border: 1px solid var(--border); color: var(--muted);
            text-decoration: none; transition: all .2s;
        }
        .pagination a:hover { border-color: var(--accent); color: var(--accent); }
        .pagination .active span { background: var(--accent); color: #0d0f14; border-color: var(--accent); font-weight: 700; }

        /* ─── BOOK GRID ─── */
        .book-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 18px; }
        .book-card {
            background: var(--card); border: 1px solid var(--border);
            border-radius: 14px; overflow: hidden;
            transition: transform .2s, border-color .2s; cursor: pointer;
        }
        .book-card:hover { transform: translateY(-4px); border-color: var(--accent); }
        .book-cover {
            height: 160px; background: linear-gradient(135deg, #1f2535, #252d42);
            display: flex; align-items: center; justify-content: center;
            font-size: 40px; color: var(--muted); position: relative;
            overflow: hidden;
        }
        .book-cover img { width: 100%; height: 100%; object-fit: cover; }
        .book-cover .book-spine {
            position: absolute; left: 0; top: 0; bottom: 0; width: 6px;
            background: var(--accent);
        }
        .book-info { padding: 14px; }
        .book-title { font-size: 13.5px; font-weight: 600; margin-bottom: 4px; line-height: 1.3; }
        .book-author { font-size: 12px; color: var(--muted); margin-bottom: 10px; }

        /* ─── MODAL ─── */
        .modal-backdrop {
            position: fixed; inset: 0; background: rgba(0,0,0,.7);
            z-index: 200; display: none; align-items: center; justify-content: center;
        }
        .modal-backdrop.open { display: flex; }
        .modal {
            background: var(--card); border: 1px solid var(--border);
            border-radius: 16px; padding: 28px; max-width: 500px; width: 90%;
            max-height: 90vh; overflow-y: auto;
        }
        .modal-title { font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 600; margin-bottom: 20px; }
        .modal-close { float: right; cursor: pointer; color: var(--muted); font-size: 20px; background: none; border: none; }

        /* ─── MISC ─── */
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: gap; gap: 12px; }
        .page-title { font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 700; }
        .search-bar {
            display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;
        }
        .search-bar input, .search-bar select {
            flex: 1; min-width: 180px;
        }
        .empty-state { text-align: center; padding: 60px 20px; color: var(--muted); }
        .empty-state i { font-size: 48px; margin-bottom: 14px; opacity: .3; }
        .empty-state p { font-size: 15px; }
        .log-table .kegiatan-login  { color: var(--green); }
        .log-table .kegiatan-logout { color: var(--blue); }
        .log-table .kegiatan-hapus  { color: var(--red); }
        .log-table .kegiatan-transaksi { color: var(--accent); }
        code { font-family: 'JetBrains Mono', monospace; font-size: 12px; background: rgba(255,255,255,.06); padding: 2px 6px; border-radius: 4px; }

        /* ─── MOBILE ─── */
        .hamburger { display: none; background: none; border: none; color: var(--text); font-size: 20px; cursor: pointer; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .hamburger { display: block; }
            .stat-grid { grid-template-columns: 1fr 1fr; }
            .content { padding: 16px; }
            .book-grid { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); }
        }
        .overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 99;
        }
        .overlay.open { display: block; }
    </style>
    @stack('styles')
</head>
<body>

<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon">📚</div>
        <div>
            <div class="logo-text">DigiLib</div>
            <div class="logo-sub">Perpustakaan Digital</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Utama</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> Dashboard
        </a>
        <a href="{{ route('buku.index') }}" class="nav-item {{ request()->routeIs('buku.*') ? 'active' : '' }}">
            <i class="fas fa-book"></i> Koleksi Buku
        </a>

        @if(auth()->user()->isSiswa())
        <div class="nav-section">Siswa</div>
        <a href="{{ route('peminjaman.index') }}" class="nav-item {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
            <i class="fas fa-book-reader"></i> Pinjaman Saya
        </a>
        @endif

        @if(auth()->user()->isStaff())
        <div class="nav-section">Manajemen</div>
        <a href="{{ route('peminjaman.index') }}" class="nav-item {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
            <i class="fas fa-exchange-alt"></i> Peminjaman
        </a>
        <a href="{{ route('laporan.index') }}" class="nav-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i> Laporan
        </a>
        @endif

        @if(auth()->user()->isAdmin())
        <div class="nav-section">Admin</div>
        <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Manajemen User
        </a>
        <a href="{{ route('logs.index') }}" class="nav-item {{ request()->routeIs('logs.*') ? 'active' : '' }}">
            <i class="fas fa-history"></i> Activity Log
        </a>
        @endif
    </nav>

    <div class="sidebar-user">
        <div class="user-avatar">{{ strtoupper(substr(auth()->user()->NamaLengkap, 0, 1)) }}</div>
        <div class="user-info">
            <div class="user-name">{{ auth()->user()->NamaLengkap }}</div>
            <div class="user-role">
                <span class="role-badge role-{{ auth()->user()->Role }}">{{ auth()->user()->Role }}</span>
            </div>
        </div>
    </div>
</aside>

<!-- MAIN -->
<div class="main">
    <header class="topbar">
        <div style="display:flex;align-items:center;gap:12px">
            <button class="hamburger" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
            <span class="topbar-title">@yield('title', 'Dashboard')</span>
        </div>
        <div class="topbar-actions">
            <span style="font-size:12px;color:var(--muted)">{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Keluar</button>
            </form>
        </div>
    </header>

    <main class="content">
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info"><i class="fas fa-info-circle"></i> {{ session('info') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('open');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('overlay').classList.remove('open');
}
// Auto-hide alerts
setTimeout(() => {
    document.querySelectorAll('.alert:not(.alert-red-alarm)').forEach(a => {
        a.style.transition = 'opacity .5s';
        a.style.opacity = '0';
        setTimeout(() => a.remove(), 500);
    });
}, 5000);
</script>
@stack('scripts')
</body>
</html>
