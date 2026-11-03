<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\User as GoogleUser;
use Tests\TestCase;

class MasukGoogleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.google.aktif' => true,
            'services.google.client_id' => 'id-uji',
            'services.google.client_secret' => 'rahasia',
        ]);
    }

    private function googleSebagai(string $email, array $tambahan = []): void
    {
        Socialite::fake('google', GoogleUser::fake(array_merge([
            'id' => 'g-'.md5($email),
            'name' => 'Nama Google',
            'email' => $email,
            'email_verified' => true,
        ], $tambahan)));
    }

    private function buatUser(array $atribut = []): User
    {
        return User::factory()->create(array_merge([
            'username' => 'budi',
            'email' => 'budi@unsil.ac.id',
        ], $atribut));
    }

    public function testFiturNonaktifKeduaRuteNotFoundDanHalamanMasukTidakMemuatTombol()
    {
        config(['services.google.aktif' => false]);

        $this->get(route('auth.google.redirect'))->assertNotFound();
        $this->get(route('auth.google.callback'))->assertNotFound();
        $this->get(route('login'))->assertOk()->assertDontSee('Masuk dengan Google');
    }

    public function testHalamanMasukMemuatTombolDanTautannya()
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Masuk dengan Google')
            ->assertSee(route('auth.google.redirect', ['asal' => route('login')]), false);
    }

    public function testMengarahkanKeGoogleDenganRedirectUriBawaan()
    {
        $lokasi = $this->get(route('auth.google.redirect'))->assertRedirect()->headers->get('Location');

        $this->assertStringStartsWith('https://accounts.google.com/', $lokasi);
        parse_str(parse_url($lokasi, PHP_URL_QUERY), $q);
        $this->assertSame(route('auth.google.callback'), $q['redirect_uri']);
        $this->assertStringContainsString('openid', urldecode($q['scope']));
    }

    public function testMemasukkanAkunYangSudahAdaDanMenautkanGoogleId()
    {
        $user = $this->buatUser();
        $this->googleSebagai('budi@unsil.ac.id');

        $this->get(route('auth.google.callback'))->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);
        $this->assertSame('g-'.md5('budi@unsil.ac.id'), $user->refresh()->google_id);
    }

    public function testMencocokkanSurelTanpaPeduliHuruf()
    {
        $user = $this->buatUser();
        $this->googleSebagai('Budi@Unsil.ac.id');

        $this->get(route('auth.google.callback'));

        $this->assertAuthenticatedAs($user);
        $this->assertSame(1, User::count());
    }

    public function testMembuatAkunBaruUntukSurelYangBelumTerdaftar()
    {
        $this->googleSebagai('baru@gmail.com');

        $this->get(route('auth.google.callback'))->assertRedirect('/dashboard');

        $u = User::firstWhere('email', 'baru@gmail.com');
        $this->assertNotNull($u);
        $this->assertSame('Nama Google', $u->name);
        $this->assertSame('baru', $u->username);
        $this->assertSame('google', $u->provider);
        $this->assertTrue($u->is_active);
        $this->assertNotNull($u->email_verified_at);
        $this->assertSame('g-'.md5('baru@gmail.com'), $u->google_id);
        $this->assertAuthenticatedAs($u);
    }

    public function testNamaPenggunaAkunBaruDiberiSufiksBilaBentrok()
    {
        $this->buatUser(['username' => 'baru', 'email' => 'lain@unsil.ac.id']);
        $this->googleSebagai('baru@gmail.com');

        $this->get(route('auth.google.callback'));

        $u = User::firstWhere('email', 'baru@gmail.com');
        $this->assertNotNull($u);
        $this->assertNotSame('baru', $u->username);
        $this->assertStringStartsWith('baru', $u->username);
    }

    public function testMenolakSurelYangBelumDiverifikasiGoogle()
    {
        $this->googleSebagai('x@gmail.com', ['email_verified' => false]);

        $this->get(route('auth.google.callback'))
            ->assertSessionHas('galat_google', 'Surel akun Google Anda belum terverifikasi.');

        $this->assertGuest();
        $this->assertSame(0, User::count());
    }

    public function testMenolakAkunYangSudahTertautKeGoogleLain()
    {
        $this->buatUser(['google_id' => 'google-lain']);
        $this->googleSebagai('budi@unsil.ac.id');

        $this->get(route('auth.google.callback'))
            ->assertSessionHas('galat_google', 'Akun ini sudah ditautkan ke akun Google lain.');

        $this->assertGuest();
    }

    public function testMenolakAkunNonaktif()
    {
        $this->buatUser(['is_active' => false]);
        $this->googleSebagai('budi@unsil.ac.id');

        $this->get(route('auth.google.callback'))
            ->assertSessionHas('galat_google', 'Akun Anda dinonaktifkan. Hubungi admin.');

        $this->assertGuest();
    }

    public function testMembatalkanDiGoogleKembaliKeHalamanMasuk()
    {
        $this->get(route('auth.google.callback', ['error' => 'access_denied']))
            ->assertRedirect(route('login'))
            ->assertSessionHas('galat_google', 'Masuk dengan Google dibatalkan.');

        $this->assertGuest();
    }

    public function testStateOauthTidakCocokKembaliKeHalamanMasuk()
    {
        Socialite::fake('google', fn () => throw new InvalidStateException);

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('login'))
            ->assertSessionHas('galat_google', 'Sesi masuk Google kedaluwarsa. Silakan coba lagi.');

        $this->assertGuest();
    }

    public function testKembaliKeAsalBilaAsalDiDalamAplikasiMengabaikanAsalDiLuarAplikasi()
    {
        $this->withSession(['google_asal' => url('/halaman-x')])
            ->get(route('auth.google.callback', ['error' => 'access_denied']))
            ->assertRedirect(url('/halaman-x'));

        $this->withSession(['google_asal' => 'https://evil.example/'])
            ->get(route('auth.google.callback', ['error' => 'access_denied']))
            ->assertRedirect(route('login'));
    }

    public function testPenggunaYangSudahMasukLangsungDiarahkan()
    {
        $user = $this->buatUser();

        $this->actingAs($user)
            ->get(route('auth.google.redirect'))
            ->assertRedirect('/dashboard');
    }

    public function testMembatasi10PermintaanPerMenitPerIp()
    {
        $this->googleSebagai('x@gmail.com', ['email_verified' => false]);

        for ($i = 0; $i < 10; $i++) {
            $this->get(route('auth.google.callback'))->assertRedirect();
        }

        $this->get(route('auth.google.callback'))->assertStatus(429);
    }
}
