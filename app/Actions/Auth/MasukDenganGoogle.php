<?php

namespace App\Actions\Auth;

use App\Exceptions\MasukGoogleDitolak;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\AbstractUser;
use Laravel\Socialite\Contracts\User as GoogleUser;

class MasukDenganGoogle
{
    public function jalankan(GoogleUser $google): User
    {
        $email = strtolower(trim((string) $google->getEmail()));
        $raw = $google instanceof AbstractUser ? $google->getRaw() : [];
        $terverifikasi = filter_var($raw['email_verified'] ?? $raw['verified_email'] ?? false, FILTER_VALIDATE_BOOL);

        if ($email === '' || ! $terverifikasi) {
            throw new MasukGoogleDitolak('Surel akun Google Anda belum terverifikasi.');
        }

        $user = User::where('google_id', $google->getId())->first()
            ?? User::whereRaw('LOWER(email) = ?', [$email])->first();

        if ($user === null) {
            $user = User::create([
                'name' => $google->getName() ?: Str::before($email, '@'),
                'username' => $this->usernameUnik($email),
                'email' => $email,
                'password' => Hash::make(Str::random(40)),
                'provider' => 'google',
                'is_active' => true,
            ]);
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        if ($user->google_id !== null && $user->google_id !== $google->getId()) {
            throw new MasukGoogleDitolak('Akun ini sudah ditautkan ke akun Google lain.');
        }

        if (! $user->is_active) {
            throw new MasukGoogleDitolak('Akun Anda dinonaktifkan. Hubungi admin.');
        }

        if ($user->google_id === null) {
            $tautan = ['google_id' => $google->getId()];

            // Akun hasil registrasi mandiri tanpa verifikasi surel dan tanpa peran bisa saja
            // didaftarkan lebih dulu oleh pihak lain: putar kredensialnya sebelum ditautkan.
            if ($user->email_verified_at === null && $user->roles()->doesntExist()) {
                $tautan += [
                    'password' => Hash::make(Str::random(40)),
                    'remember_token' => null,
                    'email_verified_at' => now(),
                ];
            }

            $user->forceFill($tautan)->save();
        }

        return $user;
    }

    private function usernameUnik(string $email): string
    {
        $dasar = Str::before($email, '@');
        $username = $dasar;

        while (User::where('username', $username)->exists()) {
            $username = $dasar.'-'.random_int(1000, 9999);
        }

        return $username;
    }
}
