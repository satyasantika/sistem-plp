@extends('errors.layout')

@php
	$isId = str_starts_with(app()->getLocale(), 'id');
@endphp

@section('error-code', '503')
@section('error-icon')<i class="fas fa-tools"></i>@endsection
@section('error-title', $isId ? 'Layanan Sementara Tidak Tersedia' : 'Service Temporarily Unavailable')
@section('error-message', $isId ? 'Sistem sedang dalam pemeliharaan atau kapasitas server sedang tinggi sehingga layanan sementara tidak dapat diakses.' : 'The system is under maintenance or handling high load, so the service is temporarily unavailable.')
@section('error-hint', $isId ? 'Silakan coba lagi dalam beberapa menit.' : 'Please try again in a few minutes.')
