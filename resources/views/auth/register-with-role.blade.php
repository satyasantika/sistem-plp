@extends('layouts.master')

@section('content')
<div class="main-content">
    <div class="title">
        Dashboard
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <h1 class="fs-4 text-center fw-bold mb-4">Register Akun PLP</h1>
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @elseif (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="mb-2 text-muted">{{ __('Nama Lengkap') }}</label>

                                <div class="input-group input-group-join mb-3">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                    <span class="input-group-text rounded-end">&nbsp<i class="fa fa-user"></i>&nbsp</span>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="username" class="mb-2 text-muted">{{ __('Username') }}</label>

                                <div class="input-group input-group-join mb-3">
                                    <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                                    <span class="input-group-text rounded-end">&nbsp<i class="fa fa-user"></i>&nbsp</span>

                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="mb-2 text-muted">{{ __('Email') }}</label>

                                <div class="input-group input-group-join mb-3">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                    <span class="input-group-text rounded-end">&nbsp<i class="fa fa-envelope"></i>&nbsp</span>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="mb-2 text-muted">{{ __('Password') }}</label>

                                <div class="input-group input-group-join mb-3">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                    <span class="input-group-text rounded-end password cursor-pointer">&nbsp<i class="fa fa-eye"></i>&nbsp</span>

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="mb-2 text-muted">{{ __('Role') }}</label>

                                <div class="input-group input-group-join mb-3">
                                    <select id="role" class="form-control @error('role') is-invalid @enderror" name="role" required>
                                        <option value="">-- Tanpa Role --</option>
                                        @foreach ($roles->sort() as $role)
                                            <option value="{{ $role }}">{{ Str::ucfirst($role) }}</option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-text rounded-end password cursor-pointer">&nbsp<i class="fa fa-user"></i>&nbsp</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="subject" class="mb-2 text-muted">{{ __('Mata Pelajaran') }}</label>

                                <div class="input-group input-group-join mb-3">
                                    <select id="subject" class="form-control @error('subject') is-invalid @enderror" name="subject" required>
                                        <option value="">-- Tanpa Mapel --</option>
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->id }}-{{ Str::ucfirst($subject->name) }}</option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-text rounded-end password cursor-pointer">&nbsp<i class="fa fa-user"></i>&nbsp</span>
                                </div>
                            </div>

                            <div class="mb-0">
                                <div class="">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
