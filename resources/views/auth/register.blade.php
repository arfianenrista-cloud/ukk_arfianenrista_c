<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi — DigiLib</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --accent:#c9a84c; --bg:#0d0f14; --card:#151820; --border:#252936; --text:#e8eaf0; --muted:#7c8196; --red:#e05252; }
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:40px 20px;}
        .box{width:100%;max-width:540px;background:var(--card);border:1px solid var(--border);border-radius:18px;padding:40px;}
        .logo{text-align:center;margin-bottom:28px;}
        .logo-icon{width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,var(--accent),#8b5e1a);display:flex;align-items:center;justify-content:center;font-size:24px;margin:0 auto 10px;}
        h1{font-family:'Playfair Display',serif;font-size:24px;text-align:center;margin-bottom:4px;color:var(--accent);}
        .sub{text-align:center;font-size:13px;color:var(--muted);margin-bottom:28px;}
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
        .form-group{margin-bottom:16px;}
        label{display:block;font-size:13px;font-weight:500;color:var(--muted);margin-bottom:6px;}
        .input-wrap{position:relative;}
        .input-wrap i{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:13px;}
        input,select{width:100%;padding:11px 13px 11px 38px;background:var(--bg);border:1px solid var(--border);border-radius:9px;color:var(--text);font-family:inherit;font-size:13.5px;transition:border-color .2s;}
        input:focus,select:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 3px rgba(201,168,76,.1);}
        .err{border-color:var(--red)!important;}
        .error-msg{color:var(--red);font-size:11.5px;margin-top:4px;}
        .btn{width:100%;padding:13px;background:var(--accent);color:#0d0f14;border:none;border-radius:10px;font-size:15px;font-weight:600;cursor:pointer;font-family:inherit;transition:background .2s;margin-top:4px;}
        .btn:hover{background:#e8c96d;}
        .login-link{text-align:center;margin-top:18px;font-size:13px;color:var(--muted);}
        .login-link a{color:var(--accent);text-decoration:none;font-weight:500;}
        @media(max-width:500px){.form-row{grid-template-columns:1fr;}}
    </style>
</head>
<body>
<div class="box">
    <div class="logo">
        <div class="logo-icon">📚</div>
        <h1>DigiLib</h1>
    </div>
    <p class="sub">Daftar akun siswa perpustakaan</p>

    @if($errors->any())
        <div style="background:rgba(224,82,82,.1);border:1px solid rgba(224,82,82,.3);border-radius:8px;padding:12px 14px;margin-bottom:18px;font-size:13px;color:#f0908c;">
            @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-row">
            <div class="form-group" style="grid-column:1/-1">
                <label>Nama Lengkap</label>
                <div class="input-wrap"><i class="fas fa-user"></i>
                    <input type="text" name="NamaLengkap" value="{{ old('NamaLengkap') }}" placeholder="Nama lengkap sesuai identitas" class="{{ $errors->has('NamaLengkap') ? 'err' : '' }}" required>
                </div>
            </div>
            <div class="form-group">
                <label>Username</label>
                <div class="input-wrap"><i class="fas fa-at"></i>
                    <input type="text" name="Username" value="{{ old('Username') }}" placeholder="username unik" class="{{ $errors->has('Username') ? 'err' : '' }}" required>
                </div>
            </div>
            <div class="form-group">
                <label>NIS</label>
                <div class="input-wrap"><i class="fas fa-id-card"></i>
                    <input type="text" name="NIS" value="{{ old('NIS') }}" placeholder="Nomor Induk Siswa" class="{{ $errors->has('NIS') ? 'err' : '' }}" required>
                </div>
            </div>
            <div class="form-group" style="grid-column:1/-1">
                <label>Email</label>
                <div class="input-wrap"><i class="fas fa-envelope"></i>
                    <input type="email" name="Email" value="{{ old('Email') }}" placeholder="email@sekolah.id" class="{{ $errors->has('Email') ? 'err' : '' }}" required>
                </div>
            </div>
            <div class="form-group">
                <label>Rayon</label>
                <div class="input-wrap"><i class="fas fa-map-marker-alt"></i>
                    <input type="text" name="Rayon" value="{{ old('Rayon') }}" placeholder="A / B / C" required>
                </div>
            </div>
            <div class="form-group">
                <label>Rombel</label>
                <div class="input-wrap"><i class="fas fa-users"></i>
                    <input type="text" name="Rombel" value="{{ old('Rombel') }}" placeholder="XII RPL 1" required>
                </div>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="input-wrap"><i class="fas fa-lock"></i>
                    <input type="password" name="Password" placeholder="min. 6 karakter" required>
                </div>
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <div class="input-wrap"><i class="fas fa-lock"></i>
                    <input type="password" name="Password_confirmation" placeholder="ulangi password" required>
                </div>
            </div>
        </div>
        <button type="submit" class="btn"><i class="fas fa-user-plus"></i> Daftar Sekarang</button>
    </form>
    <div class="login-link">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></div>
</div>
</body>
</html>
