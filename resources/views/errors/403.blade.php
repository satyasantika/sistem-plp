@extends('errors.layout')

@php
	$isId = str_starts_with(app()->getLocale(), 'id');
@endphp

@section('error-code', '403')
@section('error-icon')<i class="fas fa-user-lock"></i>@endsection
@section('error-title', $isId ? 'Akses Ditolak' : 'Access Denied')
@section('error-message', $isId ? 'Anda tidak memiliki izin untuk membuka halaman ini. Hak akses Anda mungkin belum diberikan atau sesi login Anda berubah.' : 'You do not have permission to open this page. Your access rights may be missing, or your session context has changed.')
@section('error-hint', $isId ? 'Pastikan Anda login dengan akun yang tepat, lalu coba lagi. Jika seharusnya Anda punya akses, minta admin untuk memeriksa role dan permission akun Anda.' : 'Make sure you are signed in with the correct account, then try again. If you should have access, ask the admin to verify your role and permissions.')
