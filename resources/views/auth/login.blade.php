@extends('layouts.auth')

@section('content')
    <h1 class="fs-1 text-center fw-bold mb-4">
        Selamat datang di Sistem PLP <br>FKIP-EDU 2025
    </h1>
    <div class="card shadow-lg">
        <div class="card-body p-4">
            <h1 class="fs-4 text-center fw-bold mb-4">Silakan login</h1>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="username" class="mb-2 text-muted">{{ __('Username') }}</label>

                    <div class="input-group input-group-join mb-3">
                        <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                        <span class="input-group-text rounded-end">&nbsp<i class="fa fa-envelope"></i>&nbsp</span>

                        @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="mb-2 text-muted">{{ __('Password') }}</label>

                    <div class="input-group input-group-join mb-3">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        <span class="input-group-text rounded-end password cursor-pointer">&nbsp<i class="fa fa-eye"></i>&nbsp</span>

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- <div class="row mb-3">
                    <div class="col-md-6 offset-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>
                </div> --}}

                <div class="mb-3">
                    <div class="mb-2 w-100">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Login') }}
                        </button>

                        {{-- @if (Route::has('password.request'))
                            <a class="btn btn-link float-end" href="{{ route('password.request') }}">
                                {{ __('Lupa Password?') }}
                            </a>
                        @endif --}}
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-4">

            <a href="https://drive.google.com/file/d/1QGM0yNbERPGAw6Oz-O0l0smwSDcYv-B4/view?usp=drive_link" target="_blank" class="btn btn-outline-primary mb-2">Download Pedoman FKIP EDU 2025</a>
            <a href="https://scribehow.com/shared/Panduan_Sistem-PLP_bagi_Mahasiswa__yo6klHZkRWWDfqsRGCqfvg" target="_blank" class="btn btn-outline-primary mb-2">Panduan Sistem-PLP bagi Mahasiswa</a>
            <a href="https://scribehow.com/shared/Panduan_Menilai_dalam_Sistem_PLP_untuk_Guru_Pamong__pN3dVQosSq-KiRnz7E_z9A" target="_blank" class="btn btn-outline-primary mb-2">Panduan Sistem-PLP bagi Guru Pamong</a>
            <a href="https://scribehow.com/shared/Panduan_Penggunaan_Sistem-PLP_bagi_Dosen__T8RGNhsQTp6xf1yMJhX6rg" target="_blank" class="btn btn-outline-primary mb-2">Panduan Sistem-PLP bagi Dosen Pembimbing</a>
        </div>
    </div>

@endsection
