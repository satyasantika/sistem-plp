<?php

namespace App\Http\Controllers;

use Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    function __construct()
    {
        $this->middleware('auth');
    }

    public function changePassword()
    {
        return view('auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:6',
        ]);
        $user = auth()->user();
        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->back()->withErrors(['old_password' => 'Password lama salah']);
        }
        $user->password = Hash::make($request->new_password);
        $user->save();
        return to_route('change-password')->with('status', 'Password berhasil diubah');
    }
}
