@extends('layouts.master')

@section('content')
<div class="main-content">
    <div class="title">
        Ubah Password
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if($errors)
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger">{{ $error }}</div>
                            @endforeach
                        @endif
                        <form method="POST"  action="{{ route('change-password') }}">
                            @csrf
                            <div class="mb-3{{ $errors->has('current-password') ? ' has-error' : '' }}">
                                <label class="mb-2 text-muted" for="current-password">Password saat ini</label>
                                <div class="input-group input-group-join mb-3">
                                    <input id="current-password" type="password" class="form-control @error('current-password') is-invalid @enderror" name="current-password" required autofocus autocomplete="current-password">
                                    <span class="input-group-text rounded-end password cursor-pointer">&nbsp<i class="fa fa-key"></i>&nbsp</span>
                                    @error('current-password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3{{ $errors->has('new-password') ? ' has-error' : '' }}">
                                <label class="mb-2 text-muted" for="new-password">Password Baru</label>
                                <div class="input-group input-group-join mb-3">
                                    <input id="new-password" type="password" class="form-control @error('new-password') is-invalid @enderror" name="new-password">
                                    <span class="input-group-text rounded-end password cursor-pointer">&nbsp<i class="fa fa-key"></i>&nbsp</span>
                                    @error('new-password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="mb-2 text-muted" for="new-password_confirmation">Konfirmasi Password</label>
                                <div class="input-group input-group-join mb-3">
                                    <input id="new-password_confirmation" type="password" class="form-control @error('new-password_confirmation') is-invalid @enderror" name="new-password_confirmation" >
                                    <span class="input-group-text rounded-end password cursor-pointer">&nbsp<i class="fa fa-key"></i>&nbsp</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="mb-2 w-100">
                                    <button type="submit" class="btn btn-primary ms-auto">
                                        {{ __('Ubah Password') }}
                                    </button>
                                    <a href="{{ route('dashboard') }}" class="btn btn-secondary float-end">Kembali</a>
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
