<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class OtorisasiPenggunaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        // PermissionSeeder mengandaikan peran admin sudah ada (dibuat CreateAdminUserSeeder).
        Role::create(['name' => 'admin']);
        $this->seed(PermissionSeeder::class);
        Permission::firstOrCreate(['name' => 'active-read']);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function admin(): User
    {
        return User::factory()->create()->assignRole('admin');
    }

    public function test_pengguna_tanpa_peran_tidak_bisa_mereset_sandi_pengguna_lain()
    {
        $penyerang = User::factory()->create();
        $korban = User::factory()->create();
        $hashLama = $korban->password;

        $this->actingAs($penyerang)
            ->post(route('reset-password', $korban->id))
            ->assertForbidden();

        $this->assertSame($hashLama, $korban->fresh()->password);
    }

    public function test_pengguna_tanpa_peran_tidak_bisa_membuka_profil_pengguna_lain()
    {
        $penyerang = User::factory()->create();
        $korban = User::factory()->create();

        $this->actingAs($penyerang)
            ->get(route('profiles.edit', $korban->id))
            ->assertForbidden();
    }

    public function test_pengguna_tanpa_peran_tidak_bisa_mengubah_email_pengguna_lain()
    {
        $penyerang = User::factory()->create();
        $korban = User::factory()->create(['email' => 'korban@example.com']);

        $this->actingAs($penyerang)
            ->put(route('profiles.update', $korban->id), [
                'name' => 'Dibajak',
                'email' => 'penyerang@example.com',
                'npwp' => '000',
            ])
            ->assertForbidden();

        $korban->refresh();
        $this->assertSame('korban@example.com', $korban->email);
        $this->assertNotSame('Dibajak', $korban->name);
        $this->assertNotSame('000', $korban->npwp);
    }

    public function test_pengguna_tanpa_peran_tidak_bisa_mengaktifkan_akun()
    {
        $penyerang = User::factory()->create();
        $lain = User::factory()->create();

        $this->actingAs($penyerang)
            ->post(route('users.activation', $lain->id))
            ->assertForbidden();
        $this->actingAs($penyerang)
            ->post(route('users.activation', $penyerang->id))
            ->assertForbidden();

        $this->assertFalse($penyerang->fresh()->hasPermissionTo('active-read'));
        $this->assertFalse($lain->fresh()->hasPermissionTo('active-read'));
    }

    public function test_pengguna_bisa_mengubah_kolom_profil_sendiri_yang_diizinkan()
    {
        $pengguna = User::factory()->create([
            'username' => 'asli',
            'email' => 'asli@example.com',
            'is_active' => false,
        ]);
        $hashLama = $pengguna->password;

        $this->actingAs($pengguna)
            ->get(route('profiles.edit', $pengguna->id))
            ->assertOk();

        $this->actingAs($pengguna)
            ->put(route('profiles.update', $pengguna->id), [
                'name' => 'Nama Baru',
                'phone' => '8512345678',
                'npwp' => '12.345.678.9-012.345',
                'bank' => 'BRI',
                'nomor_rekening' => '1234567890',
                // kolom akun berikut harus diabaikan pada swalayan profil
                'username' => 'diganti',
                'email' => 'diganti@example.com',
                'password' => 'sandibaru',
                'is_active' => true,
            ])
            ->assertOk();

        $pengguna->refresh();
        $this->assertSame('Nama Baru', $pengguna->name);
        $this->assertSame('8512345678', $pengguna->phone);
        $this->assertSame('12.345.678.9-012.345', $pengguna->npwp);
        $this->assertSame('BRI', $pengguna->bank);
        $this->assertSame('1234567890', $pengguna->nomor_rekening);
        $this->assertSame('asli', $pengguna->username);
        $this->assertSame('asli@example.com', $pengguna->email);
        $this->assertSame($hashLama, $pengguna->password);
        $this->assertFalse((bool) $pengguna->is_active);
    }

    public function test_pengguna_dengan_izin_users_update_tetap_bisa_mengelola_pengguna_lain()
    {
        $admin = $this->admin();
        $this->assertTrue($admin->can('users-update'));
        $target = User::factory()->create(['username' => 'target']);

        $this->actingAs($admin)
            ->post(route('reset-password', $target->id))
            ->assertOk();
        $this->assertTrue(Hash::check('target', $target->fresh()->password));

        $this->actingAs($admin)
            ->get(route('profiles.edit', $target->id))
            ->assertOk();

        $this->actingAs($admin)
            ->put(route('profiles.update', $target->id), ['name' => 'Diubah Admin', 'npwp' => '999'])
            ->assertOk();
        $this->assertSame('Diubah Admin', $target->fresh()->name);
        $this->assertSame('999', $target->fresh()->npwp);

        $this->actingAs($admin)
            ->post(route('users.activation', $target->id))
            ->assertOk();
        $this->assertTrue($target->fresh()->hasPermissionTo('active-read'));
    }

    public function test_admin_memperbarui_pengguna_tidak_menyimpan_sandi_mentah_maupun_is_active()
    {
        $admin = $this->admin();
        $target = User::factory()->create(['is_active' => false]);
        $hashLama = $target->password;

        $this->actingAs($admin)
            ->put(route('users.update', $target->id), [
                'name' => 'Nama Diperbarui',
                'username' => $target->username,
                'email' => $target->email,
                'password' => 'sandi-mentah',
                'is_active' => true,
            ])
            ->assertOk();

        $target->refresh();
        $this->assertSame('Nama Diperbarui', $target->name);
        $this->assertSame($hashLama, $target->password);
        $this->assertFalse((bool) $target->is_active);
    }

    public function test_profil_dengan_tanggal_lahir_tidak_valid_ditolak_dengan_galat_validasi()
    {
        $pengguna = User::factory()->create(['name' => 'Nama Lama']);

        $this->actingAs($pengguna)
            ->putJson(route('profiles.update', $pengguna->id), [
                'name' => 'Nama Baru',
                'birth_date' => 'bukan-tanggal',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('birth_date');

        $this->actingAs($pengguna)
            ->put(route('profiles.update', $pengguna->id), [
                'name' => 'Nama Baru',
                'birth_date' => 'bukan-tanggal',
            ])
            ->assertRedirect()
            ->assertSessionHasErrors('birth_date');

        $this->assertSame('Nama Lama', $pengguna->fresh()->name);
    }

    public function test_pesan_pembaruan_profil_meloloskan_html_pada_nama()
    {
        $pengguna = User::factory()->create();

        $response = $this->actingAs($pengguna)
            ->put(route('profiles.update', $pengguna->id), ['name' => '<script>x</script>'])
            ->assertOk();

        $this->assertStringNotContainsString('<script>', $response->json('message'));
        $this->assertStringContainsString('&lt;script&gt;', $response->json('message'));
    }

    public function test_admin_mereset_sandi_pengguna_yang_tidak_ada_mendapat_404()
    {
        $this->actingAs($this->admin())
            ->post(route('reset-password', 999999))
            ->assertNotFound();
    }
}
