@extends('errors.layout')

@php
	$isId = str_starts_with(app()->getLocale(), 'id');
@endphp

@section('error-code', '404')
@section('error-icon')<i class="fas fa-compass"></i>@endsection
@section('error-title', $isId ? 'Halaman Tidak Ditemukan' : 'Page Not Found')
@section('error-message', $isId ? 'Halaman yang Anda cari tidak tersedia, sudah dipindahkan, atau alamat URL yang diakses tidak tepat.' : 'The page you are looking for is unavailable, moved, or the URL is incorrect.')
@section('error-hint', $isId ? 'Periksa kembali alamat halaman, atau gunakan tombol Home dan Dashboard untuk melanjutkan navigasi.' : 'Check the URL again, or use Home and Dashboard to continue browsing.')
