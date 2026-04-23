@extends('layouts.auth')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        body {
            background: radial-gradient(1200px 600px at 5% 0%, #1a4c75 0%, transparent 60%),
                        radial-gradient(800px 500px at 100% 0%, #0d796f 0%, transparent 55%),
                        linear-gradient(160deg, #071c2f 0%, #0a2135 55%, #072843 100%);
            color: #f4f8ff;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .auth-wrap {
            position: relative;
            animation: rise .7s ease-out;
        }

        .auth-wrap::before {
            content: '';
            position: absolute;
            inset: -20px;
            border-radius: 26px;
            background: radial-gradient(circle at top right, rgba(244, 185, 66, 0.23), transparent 48%),
                        radial-gradient(circle at bottom left, rgba(18, 180, 166, 0.25), transparent 52%);
            z-index: 0;
            filter: blur(8px);
        }

        .auth-card,
        .guide-card {
            position: relative;
            z-index: 1;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.24);
            background: linear-gradient(170deg, rgba(255, 255, 255, 0.14), rgba(255, 255, 255, 0.08));
            box-shadow: 0 20px 46px rgba(4, 13, 26, 0.38);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .auth-heading {
            text-align: center;
            font-family: 'Sora', sans-serif;
            font-size: clamp(26px, 3vw, 34px);
            line-height: 1.2;
            font-weight: 700;
            margin-bottom: 16px;
            color: #ffffff;
        }

        .auth-subtitle {
            text-align: center;
            color: #d1deef;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .auth-title {
            font-family: 'Sora', sans-serif;
            text-align: center;
            font-size: 21px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #fff4d7;
        }

        .auth-label {
            color: #d8e3f1;
            font-weight: 600;
            font-size: 13px;
            letter-spacing: 0.3px;
        }

        .auth-input,
        .auth-addon {
            background: rgba(5, 15, 29, 0.38);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #f4f8ff;
        }

        .auth-input {
            border-right: 0;
            border-radius: 12px 0 0 12px;
            padding: 11px 14px;
        }

        .auth-input:focus {
            background: rgba(5, 15, 29, 0.5);
            color: #ffffff;
            border-color: #12b4a6;
            box-shadow: 0 0 0 0.25rem rgba(18, 180, 166, 0.22);
        }

        .auth-addon {
            border-radius: 0 12px 12px 0;
            border-left: 0;
        }

        .auth-btn {
            width: 100%;
            border: 0;
            border-radius: 12px;
            padding: 11px 16px;
            font-weight: 700;
            letter-spacing: .3px;
            color: #13283d;
            background: linear-gradient(135deg, #f4b942, #ffd27d);
            box-shadow: 0 12px 24px rgba(244, 185, 66, 0.3);
            transition: transform .2s ease;
        }

        .auth-btn:hover {
            transform: translateY(-1px);
        }

        .quick-link {
            display: block;
            text-decoration: none;
            padding: 11px 12px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(6, 20, 37, 0.34);
            color: #d9e8fa;
            font-size: 13px;
            margin-bottom: 9px;
            transition: border-color .2s ease, transform .2s ease, color .2s ease;
        }

        .quick-link:hover {
            color: #ffffff;
            border-color: rgba(18, 180, 166, 0.7);
            transform: translateY(-1px);
        }

        .to-home {
            text-align: center;
            margin-top: 12px;
        }

        .to-home a {
            color: #9adfd8;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }

        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <div class="auth-wrap">
        <h1 class="auth-heading">FKIP EDU</h1>
        <div class="auth-subtitle">Eksplorasi Edukasi • Transformasi Pembelajaran Berdampak</div>

        <div class="card auth-card mb-3">
            <div class="card-body p-4">
                <div class="auth-title">Masuk ke Sistem</div>
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="username" class="mb-2 auth-label">{{ __('Username') }}</label>
                        <div class="input-group input-group-join mb-1">
                            <input id="username" type="text" class="form-control auth-input @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                            <span class="input-group-text rounded-end auth-addon"><i class="fa fa-user"></i></span>

                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="mb-2 auth-label">{{ __('Password') }}</label>
                        <div class="input-group input-group-join mb-1">
                            <input id="password" type="password" class="form-control auth-input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            <span class="input-group-text rounded-end auth-addon password cursor-pointer"><i class="fa fa-eye"></i></span>

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="auth-btn">{{ __('Login') }}</button>
                </form>

                <div class="to-home">
                    <a href="{{ route('welcome') }}">Kembali ke halaman utama</a>
                </div>
            </div>
        </div>

    </div>

@endsection
