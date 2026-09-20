<?php

namespace Tests\Feature;

use App\Enums\AccountStatus;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirect_ke_google(): void
    {
        $provider = Mockery::mock(GoogleProvider::class);
        $provider->shouldReceive('redirect')
            ->once()
            ->andReturn(redirect('https://accounts.google.com/o/oauth2/auth?test=1'));
        Socialite::shouldReceive('driver')->once()->with('google')->andReturn($provider);

        $this->get(route('google.redirect'))
            ->assertRedirect('https://accounts.google.com/o/oauth2/auth?test=1');
    }

    public function test_callback_membuat_warga_baru_dan_login(): void
    {
        $this->fakeGoogleUser('google-1', 'baru@example.com', 'Baru');

        $this->get(route('google.callback'))
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'baru@example.com',
            'google_id' => 'google-1',
            'role' => Role::User->value,
            'status' => AccountStatus::Active->value,
        ]);
    }

    public function test_callback_menyatu_dengan_akun_email_yang_sama(): void
    {
        $native = User::factory()->create(['email' => 'nyatu@example.com']);

        $this->fakeGoogleUser('google-9', 'nyatu@example.com', 'Nyatu');

        $this->get(route('google.callback'))
            ->assertRedirect(route('dashboard'));

        // Tetap satu baris: google_id menempel ke akun native.
        $this->assertEquals(1, User::where('email', 'nyatu@example.com')->count());
        $this->assertDatabaseHas('users', [
            'id' => $native->id,
            'google_id' => 'google-9',
        ]);
    }

    public function test_callback_gagal_kembali_ke_login(): void
    {
        $provider = Mockery::mock(GoogleProvider::class);
        $provider->shouldReceive('user')->once()->andThrow(new \Exception('ditolak'));
        Socialite::shouldReceive('driver')->once()->with('google')->andReturn($provider);

        $this->get(route('google.callback'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    private function fakeGoogleUser(string $id, string $email, string $name): void
    {
        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getId')->andReturn($id);
        $socialiteUser->shouldReceive('getEmail')->andReturn($email);
        $socialiteUser->shouldReceive('getName')->andReturn($name);
        $socialiteUser->shouldReceive('getAvatar')->andReturn(null);

        $provider = Mockery::mock(GoogleProvider::class);
        $provider->shouldReceive('user')->once()->andReturn($socialiteUser);
        Socialite::shouldReceive('driver')->once()->with('google')->andReturn($provider);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
