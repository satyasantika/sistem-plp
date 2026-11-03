@if (config('services.google.aktif'))
    @if (session('galat_google'))
        <div class="alert alert-danger mt-3" role="alert">{{ session('galat_google') }}</div>
    @endif

    <div class="text-center mt-3">
        <a class="btn btn-outline-secondary w-100" href="{{ route('auth.google.redirect', ['asal' => url()->current()]) }}">Masuk dengan Google</a>
    </div>
@endif
