<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithUpserts;

class UsersImport implements ToModel, WithUpserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (!isset($row[1])) {
            return null;
        }
        return new User([
            'name'     => $row[0],
            'username'     => $row[1],
            'email'    => $row[2],
            'password' => bcrypt($row[3]),
            'subject_id'    => $row[4],
            'phone'    => $row[5],
            'address'    => $row[6],
        ]);
    }

    public function uniqueBy()
    {
        return 'username';
    }
}
