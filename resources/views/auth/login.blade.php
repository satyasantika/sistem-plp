@extends('layouts.auth')

@section('content')
<section class="container h-100">
    <div class="row justify-content-sm-center h-100 align-items-center">
        <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-7 col-sm-8">
            <div class="card shadow-lg">
                <div class="card-body p-4">
                    <h1 class="fs-4 text-center fw-bold mb-4">Login PLP</h1>
                    @error('password')
                        <span class="alert alert-danger" role="alert">
                            <strong>{{ session('status') }}</strong>
                        </span>
                    @enderror
                    {{-- <h1 class="fs-6 mb-3">{{ session('status') }}</h1> --}}
                    <form method="POST"  action="{{ route('login') }}" aria-label="abdul" data-id="abdul" class="needs-validation" novalidate=""
                        autocomplete="off">
                        @csrf
                        <div class="mb-3">
                            <label class="mb-2 text-muted" for="email">Username</label>
                            <div class="input-group input-group-join mb-3">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                <span class="input-group-text rounded-end">&nbsp<i class="fa fa-envelope"></i>&nbsp</span>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="mb-2 text-muted" for="password">Password</label>
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

                        <div class="mb-3">
                            <div class="mb-2 w-100">
                                <button type="submit" class="btn btn-primary ms-auto">
                                    {{ __('Login') }}
                                </button>
                                @if (Route::has('password.request'))
                                    <a class="float-end btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Lupa password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center mt-5 text-muted">
                Copyright &copy; 2022 &mdash; FKIP Universitas Siliwangi
            </div>
        </div>
    </div>
</section>
@endsection
