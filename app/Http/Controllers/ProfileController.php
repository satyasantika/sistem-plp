<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:profiles-read', ['only' => ['index','show']]);
        // $this->middleware('permission:profiles-create', ['only' => ['create','store']]);
        // $this->middleware('permission:profiles-update', ['only' => ['edit','update']]);
        // $this->middleware('permission:profiles-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $user = auth()->user();

        return view('profile.index', compact('user'));
    }

    /**
     * Kolom yang boleh diubah lewat halaman profil. Kolom akun (username, email,
     * password, is_active) sengaja dikecualikan; admin mengubahnya lewat
     * manajemen pengguna (konfigurasi/users).
     */
    private const KOLOM_PROFIL = [
        'name',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'phone',
        'provider',
        'is_pns',
        'golongan',
        'npwp',
        'nomor_rekening',
        'bank',
    ];

    private function izinkanAkses(User $profile): void
    {
        $user = auth()->user();
        abort_unless($profile->is($user) || $user->can('users-update'), 403);
    }

    public function edit(User $profile)
    {
        $this->izinkanAkses($profile);

        return view('profile.profile-action', [
            'user' => $profile,
            'providers' => ['Telkomsel', 'Indosat Oreedoo'],
            'is_pns' => ['nonPNS', 'PNS', 'PPPK'],
            'golongans' => ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'XIV', 'XV', 'XVI', 'XVII'],
            'banks' => ['Mandiri', 'BRI', 'BJB', 'BTN', 'BCA', 'BNI'],
        ]);
    }

    public function update(Request $request, User $profile)
    {
        $this->izinkanAkses($profile);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['nullable', 'in:L,P'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:30'],
            'provider' => ['nullable', 'string', 'max:255'],
            'is_pns' => ['nullable', 'in:0,1,2'],
            'golongan' => ['nullable', 'string', 'max:255'],
            'npwp' => ['nullable', 'string', 'max:255'],
            'nomor_rekening' => ['nullable', 'string', 'max:255'],
            'bank' => ['nullable', 'string', 'max:255'],
        ]);
        $profile->fill(array_intersect_key($data, array_flip(self::KOLOM_PROFIL)))->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User <strong>'.e($profile->name).'</strong> telah diperbarui',
        ]);
    }
}
