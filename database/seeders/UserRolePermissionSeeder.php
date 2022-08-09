<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_user_value = [
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
        DB::beginTransaction();
        try {
            $admin = User::create(array_merge([
                'name' => 'admin',
                'email' => 'admin@gmail.com'
            ], $default_user_value));

            $operator = User::create(array_merge([
                'name' => 'operator',
                'email' => 'operator@gmail.com'
            ], $default_user_value));

            $ketua = User::create(array_merge([
                'name' => 'ketua',
                'email' => 'ketua@gmail.com'
            ], $default_user_value));

            $dekanat = User::create(array_merge([
                'name' => 'dekanat',
                'email' => 'dekanat@gmail.com'
            ], $default_user_value));

            $sekretariat = User::create(array_merge([
                'name' => 'sekretariat',
                'email' => 'sekretariat@gmail.com'
            ], $default_user_value));

            $akademik = User::create(array_merge([
                'name' => 'akademik',
                'email' => 'akademik@gmail.com'
            ], $default_user_value));

            $data = User::create(array_merge([
                'name' => 'data',
                'email' => 'data@gmail.com'
            ], $default_user_value));

            $dosen = User::create(array_merge([
                'name' => 'dosen',
                'email' => 'dosen@gmail.com'
            ], $default_user_value));

            $guru = User::create(array_merge([
                'name' => 'guru',
                'email' => 'guru@gmail.com'
            ], $default_user_value));

            $mahasiswa = User::create(array_merge([
                'name' => 'mahasiswa',
                'email' => 'mahasiswa@gmail.com'
            ], $default_user_value));

            $kajur = User::create(array_merge([
                'name' => 'kajur',
                'email' => 'kajur@gmail.com'
            ], $default_user_value));

            $kepsek = User::create(array_merge([
                'name' => 'kepsek',
                'email' => 'kepsek@gmail.com'
            ], $default_user_value));

            $korguru = User::create(array_merge([
                'name' => 'korguru',
                'email' => 'korguru@gmail.com'
            ], $default_user_value));

            $kordosen = User::create(array_merge([
                'name' => 'kordosen',
                'email' => 'kordosen@gmail.com'
            ], $default_user_value));

            $admin_role = Role::create(['name' => 'admin']);
            Role::create(['name' => 'operator']);
            Role::create(['name' => 'dekanat']);
            Role::create(['name' => 'ketua']);
            Role::create(['name' => 'sekretariat']);
            Role::create(['name' => 'akademik']);
            Role::create(['name' => 'data']);
            Role::create(['name' => 'dosen']);
            Role::create(['name' => 'guru']);
            Role::create(['name' => 'mahasiswa']);
            Role::create(['name' => 'kajur']);
            Role::create(['name' => 'kepsek']);
            Role::create(['name' => 'korguru']);
            Role::create(['name' => 'kordosen']);
            Role::create(['name' => 'new_user']);

            Permission::create(['name' => 'create role']);
            Permission::create(['name' => 'read role']);
            Permission::create(['name' => 'update role']);
            Permission::create(['name' => 'delete role']);

            $admin_role->givePermissionTo(Permission::all());
            $operator->assignRole('operator');
            $dekanat->assignRole('dekanat');
            $ketua->assignRole('ketua');
            $sekretariat->assignRole('sekretariat');
            $akademik->assignRole('akademik');
            $data->assignRole('data');
            $dosen->assignRole('dosen');
            $guru->assignRole('guru');
            $mahasiswa->assignRole('mahasiswa');
            $kajur->assignRole('kajur');
            $kepsek->assignRole('kepsek');
            $korguru->assignRole('korguru');
            $kordosen->assignRole('kordosen');

            DB::commit();

        } catch (\Throwable $th) {

            DB::rollback();
        }



    }
}
