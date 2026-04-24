<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('error-code') - @yield('error-title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"
        integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w=="
        crossorigin="anonymous" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #071c2f;
            --bg-soft: #0f2e4a;
            --surface: rgba(255, 255, 255, 0.1);
            --surface-strong: rgba(255, 255, 255, 0.16);
            --text: #f4f8ff;
            --muted: #b9c8dc;
            --primary: #12b4a6;
            --secondary: #f4b942;
            --danger: #ff8e76;
            --shadow: 0 22px 60px rgba(2, 8, 20, 0.42);
            --radius: 22px;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
            color: var(--text);
            background:
                radial-gradient(1200px 700px at 0% 0%, #1b4d77 0%, transparent 60%),
                radial-gradient(900px 540px at 100% 0%, #0c7d72 0%, transparent 56%),
                linear-gradient(155deg, var(--bg) 0%, #0a2135 58%, #092f4e 100%);
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .grain {
            position: fixed;
            inset: 0;
            pointer-events: none;
            opacity: 0.14;
            background-image: radial-gradient(circle at 1px 1px, #ffffff 1px, transparent 0);
            background-size: 20px 20px;
            mix-blend-mode: soft-light;
            z-index: 0;
        }

        .orb {
            position: fixed;
            border-radius: 999px;
            filter: blur(4px);
            opacity: 0.36;
            pointer-events: none;
            z-index: 0;
            animation: float 9s ease-in-out infinite;
        }

        .orb-a {
            width: 220px;
            height: 220px;
            background: radial-gradient(circle at 30% 30%, #ffd27d, #f4b942);
            top: 12%;
            right: 8%;
        }

        .orb-b {
            width: 170px;
            height: 170px;
            background: radial-gradient(circle at 30% 30%, #35d7ca, #12b4a6);
            bottom: 10%;
            left: 7%;
            animation-delay: 1.2s;
        }

        .page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 22px;
            position: relative;
            z-index: 1;
        }

        .card {
            width: min(860px, 100%);
            background: linear-gradient(165deg, rgba(255, 255, 255, 0.13), rgba(255, 255, 255, 0.06));
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: clamp(24px, 4vw, 40px);
            overflow: hidden;
            position: relative;
            animation: reveal .7s ease forwards;
        }

        .card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, rgba(255, 255, 255, 0.11), transparent 40%);
            pointer-events: none;
        }

        .status-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            position: relative;
        }

        .status-code {
            font-family: 'Sora', sans-serif;
            font-size: clamp(44px, 10vw, 92px);
            font-weight: 800;
            line-height: 0.95;
            margin: 0;
            letter-spacing: 1px;
            color: #fff3d2;
            text-shadow: 0 10px 30px rgba(244, 185, 66, 0.22);
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 13px;
            color: #dcf6f2;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .status-visual {
            width: 72px;
            height: 72px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.28);
            color: #ffe5ab;
            box-shadow: inset 0 0 24px rgba(255, 255, 255, 0.05);
        }

        .title {
            margin: 14px 0 8px;
            font-family: 'Sora', sans-serif;
            font-size: clamp(24px, 4vw, 36px);
            line-height: 1.2;
        }

        .message {
            margin: 0;
            color: var(--muted);
            line-height: 1.75;
            font-size: 15px;
            max-width: 68ch;
        }

        .hint {
            margin-top: 14px;
            border-left: 4px solid var(--danger);
            background: rgba(255, 255, 255, 0.08);
            border-radius: 0 14px 14px 0;
            padding: 12px 14px;
            color: #ffdfd7;
            font-size: 14px;
            line-height: 1.6;
        }

        .actions {
            margin-top: 22px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .btn {
            appearance: none;
            border: 0;
            border-radius: 12px;
            padding: 11px 16px;
            font-size: 14px;
            line-height: 1;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: transform .22s ease, box-shadow .22s ease, background .22s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-home {
            background: linear-gradient(135deg, var(--secondary), #ffd27d);
            color: #13283d;
            box-shadow: 0 12px 24px rgba(244, 185, 66, 0.3);
        }

        .btn-dashboard {
            background: linear-gradient(135deg, var(--primary), #3dd8cb);
            color: #08313a;
            box-shadow: 0 12px 24px rgba(18, 180, 166, 0.28);
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .footer-note {
            margin-top: 18px;
            font-size: 12px;
            color: #cbd9ec;
        }

        @keyframes reveal {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%,
            100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-14px);
            }
        }

        @media (max-width: 700px) {
            .page {
                padding: 14px;
            }

            .actions {
                gap: 10px;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    @php
        $isId = str_starts_with(app()->getLocale(), 'id');
    @endphp
    <div class="grain"></div>
    <div class="orb orb-a"></div>
    <div class="orb orb-b"></div>

    <main class="page">
        <section class="card" role="alert" aria-live="polite">
            <div class="status-wrap">
                <h1 class="status-code">@yield('error-code')</h1>
                <div style="display:flex; align-items:center; gap:10px;">
                    @hasSection('error-icon')
                        <span class="status-visual">@yield('error-icon')</span>
                    @endif
                    <span class="status-pill">FKIP EDU System</span>
                </div>
            </div>

            <h2 class="title">@yield('error-title')</h2>
            <p class="message">@yield('error-message')</p>

            @hasSection('error-hint')
                <div class="hint">@yield('error-hint')</div>
            @endif

            <div class="actions">
                <button type="button" class="btn btn-back" onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href='{{ route('welcome') }}'; }">
                    ← {{ $isId ? 'Kembali' : 'Back' }}
                </button>
                <a href="{{ route('welcome') }}" class="btn btn-home">{{ $isId ? 'Beranda' : 'Home' }}</a>
                <a href="{{ route('dashboard') }}" class="btn btn-dashboard">{{ $isId ? 'Dasbor' : 'Dashboard' }}</a>
            </div>

            <div class="footer-note">
                {{ $isId ? 'Jika masalah berulang, hubungi admin sistem dan sertakan kode error di atas.' : 'If this issue keeps happening, contact the system administrator and include the error code above.' }}
            </div>
        </section>
    </main>
</body>
</html>
