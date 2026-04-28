<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SEDIA Service Management</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800|space-grotesk:500,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg: #081321;
            --bg-soft: #10263f;
            --brand: #0f766e;
            --brand-deep: #0b5b56;
            --brand-soft: rgba(15,118,110,.12);
            --panel: rgba(255,255,255,.93);
            --panel-line: rgba(255,255,255,.14);
            --text: #15243d;
            --muted: #64748b;
            --line: #d8e3ef;
            --danger: #bf3c47;
            --shadow: 0 30px 80px rgba(4, 12, 24, .34);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Figtree', sans-serif;
            min-height: 100vh;
            color: #fff;
            background:
                radial-gradient(circle at 14% 20%, rgba(15,118,110,.22), transparent 24%),
                radial-gradient(circle at 82% 14%, rgba(255,255,255,.08), transparent 18%),
                linear-gradient(135deg, #06101d 0%, #0a1a2d 42%, #112c47 100%);
            overflow-x: hidden;
        }

        body::before,
        body::after {
            content: "";
            position: fixed;
            border-radius: 999px;
            filter: blur(24px);
            pointer-events: none;
            z-index: 0;
        }

        body::before {
            width: 280px;
            height: 280px;
            top: 6%;
            left: -60px;
            background: rgba(15,118,110,.22);
            animation: driftOne 14s ease-in-out infinite;
        }

        body::after {
            width: 320px;
            height: 320px;
            right: -90px;
            bottom: 3%;
            background: rgba(114,181,255,.12);
            animation: driftTwo 18s ease-in-out infinite;
        }

        .scene {
            position: fixed;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }

        .scene-grid,
        .scene-glow {
            position: absolute;
            inset: 0;
        }

        .scene-grid {
            background-image:
                linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
            background-size: 46px 46px;
            mask-image: linear-gradient(180deg, transparent, rgba(0,0,0,.86) 20%, rgba(0,0,0,.96));
            opacity: .38;
            animation: gridFloat 22s linear infinite;
        }

        .scene-glow {
            background:
                radial-gradient(circle at 68% 28%, rgba(255,255,255,.08), transparent 22%),
                radial-gradient(circle at 58% 72%, rgba(15,118,110,.16), transparent 22%);
            animation: pulseScene 12s ease-in-out infinite;
        }

        .shell {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-shell {
            width: min(1120px, 100%);
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(360px, 430px);
            gap: 24px;
            align-items: stretch;
        }

        .showcase,
        .auth-panel {
            border-radius: 30px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(20px);
        }

        .showcase {
            position: relative;
            overflow: hidden;
            padding: 34px;
            border: 1px solid var(--panel-line);
            background:
                linear-gradient(180deg, rgba(255,255,255,.08), rgba(255,255,255,.03)),
                linear-gradient(135deg, rgba(11,91,86,.95), rgba(10,23,41,.92) 58%, rgba(22,49,78,.94));
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 700px;
        }

        .showcase::before {
            content: "";
            position: absolute;
            width: 420px;
            height: 420px;
            right: -90px;
            top: -120px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(216,255,245,.18) 0%, rgba(216,255,245,0) 70%);
            animation: halo 14s ease-in-out infinite;
        }

        .showcase-inner,
        .hero-copy,
        .brand-bar,
        .meta-card {
            position: relative;
            z-index: 1;
        }

        .brand-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
        }

        .brand-mark {
            display: inline-flex;
            align-items: center;
            padding: 12px 14px;
            border-radius: 18px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.12);
        }

        .brand-mark img {
            width: 132px;
            height: auto;
            display: block;
        }

        .language-switch {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px;
            border-radius: 12px;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.14);
        }

        .lang-btn {
            border: 0;
            background: transparent;
            color: rgba(255,255,255,.72);
            border-radius: 8px;
            padding: 7px 10px;
            font: inherit;
            font-size: .76rem;
            font-weight: 800;
            cursor: pointer;
            line-height: 1;
        }

        .lang-btn.active {
            background: #fff;
            color: var(--brand-deep);
        }

        .hero-copy {
            margin-top: 36px;
            max-width: 610px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            border-radius: 999px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.12);
            color: #d8fff5;
            font-size: .79rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .hero-copy h1 {
            margin-top: 18px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(2.7rem, 5vw, 4.6rem);
            line-height: .95;
            letter-spacing: -.05em;
            max-width: 10ch;
        }

        .hero-copy p {
            margin-top: 18px;
            max-width: 46ch;
            font-size: 1rem;
            line-height: 1.75;
            color: rgba(235,244,255,.82);
        }

        .meta-card {
            width: min(360px, 100%);
            padding: 18px 20px;
            border-radius: 22px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.12);
        }

        .meta-label {
            font-size: .76rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: rgba(216,255,245,.84);
        }

        .time-value {
            margin-top: 10px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(1.9rem, 3vw, 2.5rem);
            letter-spacing: -.04em;
        }

        .time-sub {
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(230,239,248,.74);
            font-size: .83rem;
        }

        .auth-panel {
            background: linear-gradient(180deg, rgba(255,255,255,.95), rgba(248,251,255,.92));
            color: var(--text);
            padding: 28px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-head {
            margin-bottom: 24px;
        }

        .auth-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: #e8fffa;
            color: var(--brand-deep);
            font-size: .78rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .auth-head h2 {
            margin-top: 16px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(2rem, 4vw, 2.7rem);
            line-height: 1;
            letter-spacing: -.04em;
        }

        .auth-head p {
            margin-top: 12px;
            font-size: .93rem;
            line-height: 1.7;
            color: var(--muted);
        }

        .alert-error {
            margin-bottom: 18px;
            padding: 14px 16px;
            border-radius: 16px;
            background: #fff0f1;
            border: 1px solid #f5c5ca;
            color: var(--danger);
            font-size: .88rem;
            line-height: 1.6;
        }

        .form-shell {
            display: grid;
            gap: 16px;
        }

        .field {
            display: grid;
            gap: 8px;
        }

        .field label {
            font-size: .84rem;
            font-weight: 800;
            color: #20324c;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #7b8ea8;
            font-size: .92rem;
        }

        .field input {
            width: 100%;
            padding: 14px 16px 14px 44px;
            border: 1.5px solid var(--line);
            border-radius: 16px;
            font: inherit;
            font-size: .95rem;
            color: var(--text);
            background: #fbfdff;
            transition: border-color .2s, box-shadow .2s, transform .2s;
        }

        .field input:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 4px rgba(15,118,110,.12);
            transform: translateY(-1px);
            background: #fff;
        }

        .inline-tools {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .remember {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--muted);
            font-size: .87rem;
            cursor: pointer;
        }

        .remember input {
            accent-color: var(--brand);
            width: 16px;
            height: 16px;
        }

        .security-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: #f2fbf8;
            color: var(--brand-deep);
            font-size: .78rem;
            font-weight: 700;
        }

        .btn-login {
            width: 100%;
            border: 0;
            border-radius: 18px;
            padding: 15px 18px;
            font: inherit;
            font-size: .98rem;
            font-weight: 800;
            color: #fff;
            cursor: pointer;
            background: linear-gradient(135deg, var(--brand), var(--brand-deep));
            box-shadow: 0 18px 34px rgba(15,118,110,.22);
            transition: transform .2s, box-shadow .2s, filter .2s;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            filter: brightness(1.03);
            box-shadow: 0 22px 38px rgba(15,118,110,.28);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 18px;
            color: var(--muted);
            font-size: .86rem;
            text-decoration: none;
            font-weight: 700;
        }

        .back-link:hover {
            color: var(--brand);
        }

        @keyframes driftOne {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            50% { transform: translate3d(34px, 18px, 0) scale(1.08); }
        }

        @keyframes driftTwo {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            50% { transform: translate3d(-28px, -22px, 0) scale(.92); }
        }

        @keyframes gridFloat {
            from { transform: translateY(0); }
            to { transform: translateY(46px); }
        }

        @keyframes pulseScene {
            0%, 100% { transform: scale(1); opacity: .92; }
            50% { transform: scale(1.04); opacity: 1; }
        }

        @keyframes halo {
            0%, 100% { transform: scale(1) translate3d(0, 0, 0); }
            50% { transform: scale(1.08) translate3d(18px, 16px, 0); }
        }

        @media (max-width: 1080px) {
            .login-shell {
                grid-template-columns: 1fr;
            }

            .showcase {
                min-height: auto;
            }
        }

        @media (max-width: 680px) {
            .shell {
                padding: 14px;
            }

            .showcase,
            .auth-panel {
                border-radius: 24px;
                padding: 20px;
            }

            .brand-bar {
                flex-direction: column;
                align-items: flex-start;
            }

            .language-switch,
            .security-pill {
                width: 100%;
                justify-content: center;
            }

            .hero-copy h1 {
                max-width: none;
            }
        }
    </style>
</head>
<body>
<div class="scene" aria-hidden="true">
    <div class="scene-grid"></div>
    <div class="scene-glow"></div>
</div>

<main class="shell">
    <div class="login-shell">
        <section class="showcase">
            <div class="showcase-inner">
                <div class="brand-bar">
                    <div class="brand-mark">
                        <img src="{{ asset('img/sedia.png') }}" alt="SEDIA Logo">
                    </div>
                    <div class="language-switch" aria-label="Language selector">
                        <button type="button" class="lang-btn active" data-lang="id" onclick="setLanguage('id')">ID</button>
                        <button type="button" class="lang-btn" data-lang="en" onclick="setLanguage('en')">EN</button>
                        <button type="button" class="lang-btn" data-lang="zh" onclick="setLanguage('zh')">中文</button>
                    </div>
                </div>

                <div class="hero-copy">
                    <div class="eyebrow">
                        <i class="fas fa-shield-halved"></i>
                        Portal Admin SEDIA
                    </div>
                    <h1>Masuk ke dashboard layanan yang lebih terarah.</h1>
                    <p>Akses panel admin untuk memantau tiket, melihat progres penanganan, dan menjaga operasional tetap responsif dalam satu ruang kerja.</p>
                </div>
            </div>

            <div class="meta-card">
                <div class="meta-label">Waktu Server</div>
                <div class="time-value" id="clock-value">--:--:--</div>
                <div class="time-sub">
                    <i class="fas fa-location-dot"></i>
                    <span id="clock-date">Memuat waktu lokal...</span>
                </div>
            </div>
        </section>

        <section class="auth-panel">
            <div class="auth-head">
                <div class="auth-kicker">
                    <i class="fas fa-lock"></i>
                    Admin Sign In
                </div>
                <h2>Welcome back.</h2>
                <p>Masukkan akun Anda untuk membuka panel admin SEDIA.</p>
            </div>

            @if($errors->any())
                <div class="alert-error">
                    <i class="fas fa-circle-exclamation"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="form-shell">
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="contoh@scm.com" required autofocus>
                    </div>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-key"></i>
                        <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                    </div>
                </div>

                <div class="inline-tools">
                    <label class="remember">
                        <input type="checkbox" name="remember">
                        Ingat saya
                    </label>
                    <div class="security-pill">
                        <i class="fas fa-user-shield"></i>
                        Akses internal terenkripsi
                    </div>
                </div>

                <button type="submit" class="btn-login">Masuk ke Dashboard</button>
            </form>

            <a href="{{ url('/') }}" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Form Laporan
            </a>
        </section>
    </div>
</main>

<script>
const translations = {
    id: {},
    en: {
        'Login - SEDIA Service Management': 'Login - SEDIA Service Management',
        'Language selector': 'Language selector',
        'Portal Admin SEDIA': 'SEDIA Admin Portal',
        'Masuk ke dashboard layanan yang lebih terarah.': 'Access a more focused service dashboard.',
        'Akses panel admin untuk memantau tiket, melihat progres penanganan, dan menjaga operasional tetap responsif dalam satu ruang kerja.': 'Access the admin panel to monitor tickets, review handling progress, and keep operations responsive in one workspace.',
        'Waktu Server': 'Server Time',
        'Memuat waktu lokal...': 'Loading local time...',
        'Admin Sign In': 'Admin Sign In',
        'Welcome back.': 'Welcome back.',
        'Masukkan akun Anda untuk membuka panel admin SEDIA.': 'Enter your account to open the SEDIA admin panel.',
        'Email': 'Email',
        'contoh@scm.com': 'example@scm.com',
        'Password': 'Password',
        'Masukkan password': 'Enter password',
        'Ingat saya': 'Remember me',
        'Akses internal terenkripsi': 'Encrypted internal access',
        'Masuk ke Dashboard': 'Sign In to Dashboard',
        'Kembali ke Form Laporan': 'Back to Report Form'
    },
    zh: {
        'Login - SEDIA Service Management': '登录 - SEDIA 服务管理',
        'Language selector': '语言选择',
        'Portal Admin SEDIA': 'SEDIA 管理入口',
        'Masuk ke dashboard layanan yang lebih terarah.': '进入更清晰的服务仪表板。',
        'Akses panel admin untuk memantau tiket, melihat progres penanganan, dan menjaga operasional tetap responsif dalam satu ruang kerja.': '进入管理面板，监控工单、查看处理进度，并在一个工作空间内保持运营高效响应。',
        'Waktu Server': '服务器时间',
        'Memuat waktu lokal...': '正在加载本地时间...',
        'Admin Sign In': '管理员登录',
        'Welcome back.': '欢迎回来。',
        'Masukkan akun Anda untuk membuka panel admin SEDIA.': '请输入您的账号以打开 SEDIA 管理面板。',
        'Email': '邮箱',
        'contoh@scm.com': 'example@scm.com',
        'Password': '密码',
        'Masukkan password': '请输入密码',
        'Ingat saya': '记住我',
        'Akses internal terenkripsi': '加密的内部访问',
        'Masuk ke Dashboard': '进入仪表板',
        'Kembali ke Form Laporan': '返回报告表单'
    }
};

const textSources = new WeakMap();
let currentLang = 'id';

function translateText(source) {
    return translations[currentLang][source] || source;
}

function applyLanguage(lang) {
    currentLang = translations[lang] ? lang : 'id';
    document.documentElement.lang = currentLang === 'zh' ? 'zh-CN' : currentLang;
    document.title = translateText('Login - SEDIA Service Management');

    document.querySelectorAll('.lang-btn').forEach((button) => {
        button.classList.toggle('active', button.dataset.lang === currentLang);
    });

    const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, {
        acceptNode(node) {
            const parent = node.parentElement;
            if (!parent || ['SCRIPT', 'STYLE'].includes(parent.tagName)) {
                return NodeFilter.FILTER_REJECT;
            }
            return node.nodeValue.trim() ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
        }
    });

    while (walker.nextNode()) {
        const node = walker.currentNode;
        if (!textSources.has(node)) {
            textSources.set(node, node.nodeValue.trim());
        }
        const source = textSources.get(node);
        const leading = node.nodeValue.match(/^\s*/)?.[0] || '';
        const trailing = node.nodeValue.match(/\s*$/)?.[0] || '';
        node.nodeValue = leading + translateText(source) + trailing;
    }

    document.querySelectorAll('[placeholder]').forEach((element) => {
        if (!element.dataset.i18nPlaceholderSource) {
            element.dataset.i18nPlaceholderSource = element.getAttribute('placeholder');
        }
        element.setAttribute('placeholder', translateText(element.dataset.i18nPlaceholderSource));
    });

    document.querySelectorAll('[aria-label]').forEach((element) => {
        if (!element.dataset.i18nAriaSource) {
            element.dataset.i18nAriaSource = element.getAttribute('aria-label');
        }
        element.setAttribute('aria-label', translateText(element.dataset.i18nAriaSource));
    });

    updateClock();
}

function setLanguage(lang) {
    applyLanguage(lang);
}

function updateClock() {
    const now = new Date();
    const timeTarget = document.getElementById('clock-value');
    const dateTarget = document.getElementById('clock-date');

    if (!timeTarget || !dateTarget) {
        return;
    }

    const localeMap = {
        id: 'id-ID',
        en: 'en-US',
        zh: 'zh-CN',
    };

    const locale = localeMap[currentLang] || 'id-ID';

    timeTarget.textContent = now.toLocaleTimeString(locale, {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });

    dateTarget.textContent = now.toLocaleDateString(locale, {
        weekday: 'long',
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    }) + ' | WITA';
}

document.addEventListener('DOMContentLoaded', () => {
    applyLanguage('id');
    setInterval(updateClock, 1000);
});
</script>
</body>
</html>
