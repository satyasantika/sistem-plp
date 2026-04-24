@extends('errors.layout')

@php
	$isId = str_starts_with(app()->getLocale(), 'id');
@endphp

@section('error-code', '500')
@section('error-icon')<i class="fas fa-server"></i>@endsection
@section('error-title', $isId ? 'Terjadi Kendala Server' : 'Server Error')
@section('error-message', $isId ? 'Aplikasi mengalami gangguan internal saat memproses permintaan Anda. Tim teknis dapat memerlukan waktu singkat untuk pemulihan.' : 'The application encountered an internal issue while processing your request. The technical team may need a short time to recover.')
@section('error-hint', $isId ? 'Coba muat ulang halaman atau kembali beberapa saat lagi. Jika masih berulang, laporkan waktu kejadian dan langkah yang Anda lakukan.' : 'Try reloading the page or return in a moment. If it persists, report the time and actions that triggered the issue.')
