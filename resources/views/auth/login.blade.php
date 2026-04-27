<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — DigiLib Perpustakaan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --accent: #c9a84c; --accent2: #e8c96d; --bg: #0d0f14; --card: #151820; --border: #252936; --text: #e8eaf0; --muted: #7c8196; --red: #e05252; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; }
        .left-panel {
            flex: 1; background: linear-gradient(160deg, #0d1220 0%, #111827 50%, #0d0f14 100%);
            display: flex; flex-direction: column; justify-content: center; padding: 60px;
            position: relative; overflow: hidden;
        }
        .left-panel::before {
            content: ''; position: absolute;
            width: 400px; height: 400px; border-radius: 50%;
            background: radial-gradient(circle, rgba(201,168,76,.12) 0%, transparent 70%);
            top: -100px; left: -100px;
        }
        .left-panel::after {
            content: ''; position: absolute;
            width: 300px; height: 300px; border-radius: 50%;
            background: radial-gradient(circle, rgba(76,139,224,.08) 0%, transparent 70%);
            bottom: -50px; right: -50px;
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(201,168,76,.1); border: 1px solid rgba(201,168,76,.3);
            color: var(--accent); padding: 6px 14px; border-radius: 20px;
            font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;
            margin-bottom: 24px; width: fit-content;
        }
        .hero-title { font-family: 'Playfair Display', serif; font-size: 48px; font-weight: 700; line-height: 1.15; margin-bottom: 20px; }
        .hero-title span { color: var(--accent); }
        .hero-desc { font-size: 16px; color: var(--muted); line-height: 1.7; max-width: 440px; margin-bottom: 40px; }
        .feature-list { display: flex; flex-direction: column; gap: 14px; }
        .feature-item { display: flex; align-items: center; gap: 12px; font-size: 14px; color: #9ba3b8; }
        .feature-icon { width: 32px; height: 32px; border-radius: 8px; background: rgba(201,168,76,.12); display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 13px; flex-shrink: 0; }

        .right-panel {
            width: 480px; display: flex; align-items: center; justify-content: center;
            padding: 40px; background: var(--card); border-left: 1px solid var(--border);
        }
        .login-box { width: 100%; max-width: 380px; }
        .login-logo { text-align: center; margin-bottom: 36px; }
        .login-logo .icon {
            width: 64px; height: 64px; border-radius: 16px;
            background: linear-gradient(135deg, var(--accent), #8b5e1a);
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; margin: 0 auto 12px;
        }
        .login-logo h1 { font-family: 'Playfair Display', serif; font-size: 26px; font-weight: 700; color: var(--accent); }
        .login-logo p { font-size: 13px; color: var(--muted); margin-top: 4px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; font-size: 13px; font-weight: 500; color: var(--muted); margin-bottom: 7px; }
        .input-wrap { position: relative; }
        .input-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 14px; }
        input {
            width: 100%; padding: 12px 14px 12px 40px;
            background: var(--bg); border: 1px solid var(--border);
            border-radius: 10px; color: var(--text); font-family: inherit; font-size: 14px;
            transition: border-color .2s;
        }
        input:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(201,168,76,.1); }
        .input-error { border-color: var(--red) !important; }
        .error-msg { color: var(--red); font-size: 12px; margin-top: 5px; }
        .remember-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; }
        .checkbox-label { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--muted); cursor: pointer; }
        .checkbox-label input { width: auto; padding: 0; }
        .btn-login {
            width: 100%; padding: 13px; background: var(--accent); color: #0d0f14;
            border: none; border-radius: 10px; font-size: 15px; font-weight: 600;
            cursor: pointer; font-family: inherit; transition: background .2s, transform .1s;
        }
        .btn-login:hover { background: var(--accent2); }
        .btn-login:active { transform: scale(.98); }
        .divider { text-align: center; margin: 22px 0; position: relative; }
        .divider::before { content: ''; position: absolute; top: 50%; left: 0; right: 0; height: 1px; background: var(--border); }
        .divider span { background: var(--card); padding: 0 12px; font-size: 12px; color: var(--muted); position: relative; }
        .btn-register {
            width: 100%; padding: 12px; background: transparent; color: var(--text);
            border: 1px solid var(--border); border-radius: 10px; font-size: 14px; font-weight: 500;
            cursor: pointer; font-family: inherit; transition: all .2s; text-decoration: none;
            display: block; text-align: center;
        }
        .btn-register:hover { border-color: var(--accent); color: var(--accent); }
        .demo-accounts { margin-top: 24px; padding: 16px; background: rgba(255,255,255,.03); border-radius: 10px; border: 1px solid var(--border); }
        .demo-title { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); margin-bottom: 10px; font-weight: 600; }
        .demo-row { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 6px; }
        .demo-row:last-child { margin-bottom: 0; }
        code { font-family: monospace; background: rgba(201,168,76,.1); color: var(--accent); padding: 1px 6px; border-radius: 4px; font-size: 11px; }
        @media (max-width: 900px) { .left-panel { display: none; } .right-panel { width: 100%; } }
    </style>
</head>
<body>
    <div class="left-panel">
        <div class="hero-badge"><i class="fas fa-book-open"></i> Perpustakaan Digital SMK</div>
        <h1 class="hero-title">Temukan Ilmu,<br>Raih <span>Prestasi</span></h1>
        <p class="hero-desc">Sistem manajemen perpustakaan digital yang modern. Mudah digunakan oleh admin, petugas, dan siswa.</p>
        <div class="feature-list">
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-books"></i></div>
                Koleksi ribuan judul buku tersedia digital
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-exchange-alt"></i></div>
                Peminjaman & pengembalian real-time
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-chart-bar"></i></div>
                Laporan lengkap & activity log terpusat
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-bell"></i></div>
                Alarm otomatis buku terlambat & denda
            </div>
        </div>
    </div>

    <div class="right-panel">
        <div class="login-box">
            <div class="login-logo">
                <div class="icon">📚</div>
                <h1>DigiLib</h1>
                <p>Masuk ke sistem perpustakaan</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label>Username</label>
                    <div class="input-wrap">
                        <i class="fas fa-user"></i>
                        <input type="text" name="Username" placeholder="Masukkan username" value="{{ old('Username') }}" class="{{ $errors->has('Username') ? 'input-error' : '' }}" required autofocus>
                    </div>
                    @error('Username')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="Password" placeholder="Masukkan password" required>
                    </div>
                </div>
                <div class="remember-row">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember"> Ingat saya
                    </label>
                </div>
                <button type="submit" class="btn-login">Masuk <i class="fas fa-arrow-right" style="margin-left:6px"></i></button>
            </form>

            <div class="divider"><span>atau</span></div>
            <a href="{{ route('register') }}" class="btn-register"><i class="fas fa-user-plus"></i> Daftar Akun Baru (Siswa)</a>

            <div class="demo-accounts">
                <div class="demo-title">🔑 Akun Demo</div>
                <div class="demo-row"><span>Admin</span> <span><code>admin</code> / <code>admin123</code></span></div>
                <div class="demo-row"><span>Petugas</span> <span><code>petugas1</code> / <code>petugas123</code></span></div>
                <div class="demo-row"><span>Siswa</span> <span><code>siswa001</code> / <code>siswa123</code></span></div>
            </div>
        </div>
    </div>
</body>
</html>
