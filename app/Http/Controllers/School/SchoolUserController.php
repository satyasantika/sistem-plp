<?php

namespace App\Http\Controllers\School;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SchoolUserProposal;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class SchoolUserController extends Controller
{
    public function index()
    {
        $teachers = SchoolUserProposal::all();
        foreach ($teachers as $teacher) {
            if(User::where('username',$teacher->nip)->doesntExist()){
                User::create([
                        'username'  => $teacher->nip,
                        'name'      => $teacher->name,
                        'phone'     => $teacher->phone,
                        'email'     => $teacher->nip."@gmail.com",
                        'password'  => Hash::make("siliwangi2022"),
                        'subject_id' => $teacher->subject_id,
                    ])->assignRole($teacher->role);
            }

            $teacher->registered = 1;
            $teacher->save();
        }

        return 'all teacher registered';
    }

    public function update(SchoolUserProposal $schooluserproposal)
    {
        $schooluserproposal->registered = 1;
        $schooluserproposal->save();

        return response()->json([
            'status' => 'success',
            'message' => '<strong>'.$schooluserproposal->name.'</strong> telah disetujui'
        ]);
    }

}
