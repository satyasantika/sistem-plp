@extends('errors.layout')

@php
	$isId = str_starts_with(app()->getLocale(), 'id');
@endphp

@section('error-code', '419')
@section('error-icon')<i class="fas fa-hourglass-half"></i>@endsection
@section('error-title', $isId ? 'Sesi Kedaluwarsa' : 'Session Expired')
@section('error-message', $isId ? 'Permintaan gagal karena sesi keamanan sudah berakhir. Hal ini umum terjadi ketika halaman terlalu lama terbuka sebelum dikirim.' : 'Your request failed because the security session has expired. This often happens when a page stays open too long before submission.')
@section('error-hint', $isId ? 'Silakan kembali ke halaman sebelumnya, muat ulang, lalu kirim ulang data Anda.' : 'Go back, reload the page, and submit your data again.')
