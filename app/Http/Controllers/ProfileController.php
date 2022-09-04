<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    function __construct()
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

    public function edit(User $profile)
    {
        return view('profile.profile-action', [
            'user' => $profile,
            'providers' => ['Telkomsel','Indosat Oreedoo'],
            'is_pns' => ['nonPNS','PNS'],
            'golongans' => ['I','II','III','IV'],
            'banks' => ['Mandiri','BRI','BJB','BTN', 'BCA'],
        ]);
    }

    public function update(Request $request, User $profile)
    {
        $data = $request->all();
        $profile->fill($data)->save();
        return response()->json([
            'status' => 'success',
            'message' => 'User <strong>'.$request->name.'</strong> telah diperbarui'
        ]);
    }

}
