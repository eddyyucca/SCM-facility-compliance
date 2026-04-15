<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SCM Complaint Management</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700" rel="stylesheet" />
    <style>
        :root {
            --bg: #f4efe7;
            --brand: #8f4f22;
            --brand-d: #6d3b17;
            --line: #ddd0be;
            --text: #2f241c;
            --muted: #6d5b4c;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Figtree', sans-serif;
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(233,201,165,.65), transparent 32%),
                radial-gradient(circle at bottom right, rgba(143,79,34,.14), transparent 28%),
                linear-gradient(180deg, #f7f2eb 0%, var(--bg) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .login-box {
            width: 100%;
            max-width: 420px;
            background: rgba(255,255,255,.92);
            border: 1px solid var(--line);
            border-radius: 24px;
            padding: 36px 32px;
            box-shadow: 0 24px 60px rgba(93,64,38,.13);
        }
        .brand {
            text-align: center;
            margin-bottom: 28px;
        }
        .brand-icon {
            width: 200px;
            max-width: 100%;
            height: auto;
            margin-bottom: 14px;
        }
        .brand h1 {
            font-size: 1.08rem;
            color: var(--brand-d);
        }
        .brand p {
            font-size: .88rem;
            color: var(--muted);
            margin-top: 4px;
        }
        .field { margin-bottom: 16px; }
        .field label {
            display: block;
            font-size: .9rem;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--text);
        }
        .field input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--line);
            border-radius: 12px;
            font: inherit;
            font-size: .95rem;
            background: #fffdf9;
            color: var(--text);
            transition: border-color .2s, box-shadow .2s;
        }
        .field input:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(143,79,34,.1);
        }
        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .88rem;
            color: var(--muted);
            margin-bottom: 20px;
        }
        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--brand), var(--brand-d));
            color: #fff;
            border: none;
            border-radius: 14px;
            font: inherit;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(109,59,23,.2);
            transition: filter .2s;
        }
        .btn-login:hover { filter: brightness(1.05); }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 16px;
            font-size: .85rem;
            color: var(--muted);
            text-decoration: none;
        }
        .back-link:hover { color: var(--brand); }
        .demo-hint {
            margin-top: 24px;
            padding: 14px;
            background: #fdf8f3;
            border: 1px solid var(--line);
            border-radius: 12px;
            font-size: .78rem;
            color: var(--muted);
            line-height: 1.6;
        }
        .demo-hint strong { color: var(--text); }
    </style>
</head>
<body>
<div class="login-box">
    <div class="brand">
        <img src="{{ asset('icons/scm-logo-transparent.png') }}" alt="SCM Logo" class="brand-icon">
        <h1>PT. Sulawesi Cahaya Mineral</h1>
        <p>Complaint Management System</p>
    </div>

    @if($errors->any())
        <div style="background:#fdecea;border:1px solid #f5c6c0;border-radius:10px;padding:10px 14px;margin-bottom:16px;font-size:.88rem;color:#c0392b;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="contoh@scm.com" required autofocus />
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   placeholder="Masukkan password" required />
        </div>
        <label class="remember">
            <input type="checkbox" name="remember"> Ingat saya
        </label>
        <button type="submit" class="btn-login">Masuk</button>
    </form>

    <a href="{{ url('/') }}" class="back-link">Kembali ke Form Laporan</a>

    <div class="demo-hint">
        <strong>Akun Demo:</strong><br>
        superadmin@ga.com | hk@ga.com<br>
        receptionist@ga.com | laundry@ga.com<br>
        Password semua: <strong>password123</strong>
    </div>
</div>
</body>
</html>

