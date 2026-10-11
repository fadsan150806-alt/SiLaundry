<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class MultiRoleLoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{string, string}>
     */
    public static function roleDashboardProvider(): array
    {
        return [
            'admin' => [User::ROLE_ADMIN, 'dashboard.admin'],
            'kasir' => [User::ROLE_KASIR, 'dashboard.kasir'],
            'pelanggan' => [User::ROLE_PELANGGAN, 'dashboard.pelanggan'],
        ];
    }

    public function test_login_page_can_be_rendered(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Kata Sandi')
            ->assertSee('img/logo.png', false);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
        $this->get(route('dashboard.admin'))->assertRedirect(route('login'));
        $this->get(route('users.index'))->assertRedirect(route('login'));
    }

    #[DataProvider('roleDashboardProvider')]
    public function test_each_role_is_redirected_to_its_own_dashboard(string $role, string $dashboardRoute): void
    {
        $user = User::factory()->create([
            'role' => $role,
            'password' => 'password123',
        ]);

        $this->post(route('login.process'), [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertRedirect(route($dashboardRoute));

        $this->assertAuthenticatedAs($user);

        $this->get(route($dashboardRoute))->assertOk();
        $this->get('/')->assertRedirect(route($dashboardRoute));
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->admin()->create(['password' => 'password123']);

        $this->from(route('login'))
            ->post(route('login.process'), [
                'email' => $user->email,
                'password' => 'salah-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_user_with_unknown_role_cannot_login(): void
    {
        $user = User::factory()->create([
            'role' => 'tamu',
            'password' => 'password123',
        ]);

        $this->post(route('login.process'), [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_authenticated_user_visiting_login_is_sent_to_dashboard(): void
    {
        $kasir = User::factory()->kasir()->create();

        $this->actingAs($kasir)
            ->get(route('login'))
            ->assertRedirect(route('home'));
    }

    public function test_kasir_cannot_access_admin_pages(): void
    {
        $kasir = User::factory()->kasir()->create();

        $this->actingAs($kasir);

        $this->get(route('dashboard.admin'))->assertForbidden();
        $this->get(route('dashboard.pelanggan'))->assertForbidden();
        $this->get(route('users.index'))->assertForbidden();
        $this->get(route('users.create'))->assertForbidden();
    }

    public function test_pelanggan_cannot_access_admin_or_kasir_pages(): void
    {
        $pelanggan = User::factory()->pelanggan()->create();
        $target = User::factory()->kasir()->create();

        $this->actingAs($pelanggan);

        $this->get(route('dashboard.admin'))->assertForbidden();
        $this->get(route('dashboard.kasir'))->assertForbidden();
        $this->get(route('users.index'))->assertForbidden();
        $this->delete(route('users.destroy', $target))->assertForbidden();

        $this->assertModelExists($target);
    }

    public function test_admin_cannot_access_other_role_dashboards(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin);

        $this->get(route('dashboard.kasir'))->assertForbidden();
        $this->get(route('dashboard.pelanggan'))->assertForbidden();
    }

    public function test_user_can_logout(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_login_is_locked_after_too_many_failed_attempts(): void
    {
        $user = User::factory()->admin()->create(['password' => 'password123']);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('login.process'), [
                'email' => $user->email,
                'password' => 'salah-password',
            ])->assertSessionHasErrors('email');
        }

        // Percobaan ke-6 tetap ditolak walau kata sandi benar.
        $this->post(route('login.process'), [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertSessionHasErrors('email');

        $this->assertStringStartsWith('Terlalu banyak percobaan masuk.', session('errors')->first('email'));
        $this->assertGuest();
    }

    #[DataProvider('roleDashboardProvider')]
    public function test_dashboard_shows_logo_and_active_role_without_descriptions(string $role, string $dashboardRoute): void
    {
        $user = User::factory()->create(['role' => $role]);

        $this->actingAs($user)
            ->get(route($dashboardRoute))
            ->assertOk()
            ->assertSee('img/logo.png', false)
            ->assertSee($user->roleLabel())
            ->assertDontSee('Sistem Informasi Manajemen Laundry')
            ->assertDontSee('Segera');
    }

    public function test_forbidden_page_shows_active_account(): void
    {
        $kasir = User::factory()->kasir()->create(['name' => 'Kasir Uji']);

        $this->actingAs($kasir)
            ->get(route('users.index'))
            ->assertForbidden()
            ->assertSee('Akses Ditolak')
            ->assertSee('Kasir Uji');
    }
}
