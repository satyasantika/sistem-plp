@extends('errors.layout')

@php
	$isId = str_starts_with(app()->getLocale(), 'id');
@endphp

@section('error-code', '429')
@section('error-icon')<i class="fas fa-stopwatch"></i>@endsection
@section('error-title', $isId ? 'Terlalu Banyak Permintaan' : 'Too Many Requests')
@section('error-message', $isId ? 'Sistem menerima terlalu banyak request dalam waktu singkat dari sisi Anda, sehingga sementara dibatasi untuk menjaga kestabilan layanan.' : 'The system has received too many requests from your side in a short period, so access is temporarily limited for stability.')
@section('error-hint', $isId ? 'Tunggu beberapa saat lalu coba lagi secara bertahap.' : 'Please wait a moment and try again gradually.')
