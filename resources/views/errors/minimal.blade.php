@php
    $statusCode = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500;
    $isId = str_starts_with(app()->getLocale(), 'id');
    $titles = [
        401 => $isId ? 'Belum Terautentikasi' : 'Unauthenticated',
        402 => $isId ? 'Pembayaran Diperlukan' : 'Payment Required',
        405 => $isId ? 'Metode Tidak Diizinkan' : 'Method Not Allowed',
        408 => $isId ? 'Permintaan Timeout' : 'Request Timeout',
        410 => $isId ? 'Konten Sudah Tidak Tersedia' : 'Content No Longer Available',
        422 => $isId ? 'Data Tidak Valid' : 'Invalid Data',
        502 => $isId ? 'Gateway Bermasalah' : 'Bad Gateway',
        504 => $isId ? 'Gateway Timeout' : 'Gateway Timeout',
    ];
    $messages = [
        401 => $isId ? 'Permintaan ini membutuhkan autentikasi. Silakan login terlebih dahulu.' : 'This request requires authentication. Please sign in first.',
        402 => $isId ? 'Aksi ini membutuhkan prasyarat pembayaran atau lisensi tertentu.' : 'This action requires a payment or license prerequisite.',
        405 => $isId ? 'Permintaan menggunakan metode HTTP yang tidak didukung oleh endpoint ini.' : 'The request uses an HTTP method not supported by this endpoint.',
        408 => $isId ? 'Server tidak menerima permintaan lengkap dalam batas waktu.' : 'The server did not receive a complete request within the allowed timeout.',
        410 => $isId ? 'Sumber daya yang diminta sudah tidak tersedia secara permanen.' : 'The requested resource is permanently unavailable.',
        422 => $isId ? 'Permintaan diterima, namun data yang dikirim tidak memenuhi validasi.' : 'The request was accepted, but the submitted data did not pass validation.',
        502 => $isId ? 'Server perantara menerima respons tidak valid dari layanan inti.' : 'An upstream server returned an invalid response to the gateway.',
        504 => $isId ? 'Server perantara kehabisan waktu saat menunggu respons layanan inti.' : 'The gateway timed out while waiting for an upstream response.',
    ];
    $icons = [
        401 => 'fas fa-fingerprint',
        402 => 'fas fa-receipt',
        405 => 'fas fa-ban',
        408 => 'fas fa-hourglass-half',
        410 => 'fas fa-trash-alt',
        422 => 'fas fa-exclamation-triangle',
        502 => 'fas fa-random',
        504 => 'fas fa-stopwatch',
    ];
@endphp

@extends('errors.layout')

@section('error-code', (string) $statusCode)
@section('error-icon')<i class="{{ $icons[$statusCode] ?? 'fas fa-exclamation-circle' }}"></i>@endsection
@section('error-title', $titles[$statusCode] ?? ($isId ? 'Terjadi Kendala' : 'Unexpected Issue'))
@section('error-message', $messages[$statusCode] ?? ($isId ? 'Permintaan Anda belum dapat diproses saat ini. Silakan coba beberapa saat lagi.' : 'Your request cannot be processed right now. Please try again shortly.'))
@section('error-hint', $isId ? 'Jika Anda membutuhkan akses segera, hubungi admin aplikasi dengan menyertakan kode error ini.' : 'If you need immediate access, contact the application admin and include this error code.')
