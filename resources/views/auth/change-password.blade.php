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
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @elseif (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form method="POST"  action="{{ route('update-password') }}" aria-label="abdul" data-id="abdul" class="needs-validation" novalidate=""
                            autocomplete="off">
                            @csrf
                            <div class="mb-3">
                                <label class="mb-2 text-muted" for="email">Password lama Anda</label>
                                <div class="input-group input-group-join mb-3">
                                    <input id="old_password" type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password" required autofocus autocomplete="current-password">
                                    <span class="input-group-text rounded-end password cursor-pointer">&nbsp<i class="fa fa-key"></i>&nbsp</span>
                                    @error('old_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="mb-2 text-muted" for="password">Password Baru</label>
                                <div class="input-group input-group-join mb-3">
                                    <input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password">
                                    <span class="input-group-text rounded-end password cursor-pointer">&nbsp<i class="fa fa-key"></i>&nbsp</span>
                                    @error('new_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="mb-2 text-muted" for="password">Konfirmasi Password</label>
                                <div class="input-group input-group-join mb-3">
                                    <input id="new_password_confirmation" type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" name="new_password_confirmation" >
                                    <span class="input-group-text rounded-end password cursor-pointer">&nbsp<i class="fa fa-key"></i>&nbsp</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="mb-2 w-100">
                                    <button type="submit" class="btn btn-primary ms-auto">
                                        {{ __('Update') }}
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
