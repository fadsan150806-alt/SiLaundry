<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create(['name' => 'Admin Utama']);
    }

    public function test_admin_can_view_user_list(): void
    {
        $kasir = User::factory()->kasir()->create(['name' => 'Kasir Satu']);

        $this->actingAs($this->admin)
            ->get(route('users.index'))
            ->assertOk()
            ->assertSee('Pengguna')
            ->assertSee('Admin Utama')
            ->assertSee($kasir->email);
    }

    public function test_admin_can_search_and_filter_users(): void
    {
        User::factory()->kasir()->create(['name' => 'Rina Kasir']);
        User::factory()->pelanggan()->create(['name' => 'Joko Pelanggan']);

        $this->actingAs($this->admin)
            ->get(route('users.index', ['q' => 'Rina']))
            ->assertSee('Rina Kasir')
            ->assertDontSee('Joko Pelanggan');

        $this->actingAs($this->admin)
            ->get(route('users.index', ['role' => 'pelanggan']))
            ->assertSee('Joko Pelanggan')
            ->assertDontSee('Rina Kasir');
    }

    public function test_admin_can_open_create_form(): void
    {
        $this->actingAs($this->admin)
            ->get(route('users.create'))
            ->assertOk()
            ->assertSee('Tambah Pengguna');
    }

    public function test_admin_can_create_kasir(): void
    {
        $this->actingAs($this->admin)
            ->post(route('users.store'), [
                'name' => 'Andi Pratama',
                'username' => 'andi',
                'email' => 'andi@silaundry.test',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => User::ROLE_KASIR,
            ])
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('success');

        $user = User::where('email', 'andi@silaundry.test')->firstOrFail();

        $this->assertSame(User::ROLE_KASIR, $user->role);
        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertNull($user->pelanggan);
    }

    public function test_creating_pelanggan_also_creates_pelanggan_profile(): void
    {
        $this->actingAs($this->admin)
            ->post(route('users.store'), [
                'name' => 'Rudi Hartono',
                'username' => 'rudi',
                'email' => 'rudi@silaundry.test',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => User::ROLE_PELANGGAN,
                'no_hp' => '081111222333',
                'alamat' => 'Jl. Mawar No. 1',
            ])
            ->assertRedirect(route('users.index'));

        $user = User::where('email', 'rudi@silaundry.test')->firstOrFail();

        $this->assertDatabaseHas('pelanggan', [
            'user_id' => $user->id,
            'nama' => 'Rudi Hartono',
            'no_hp' => '081111222333',
        ]);
    }

    public function test_new_user_can_login_with_assigned_role(): void
    {
        $this->actingAs($this->admin)->post(route('users.store'), [
            'name' => 'Kasir Baru',
            'username' => 'kasirbaru',
            'email' => 'kasirbaru@silaundry.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => User::ROLE_KASIR,
        ]);

        $this->post(route('logout'));

        $this->post(route('login.process'), [
            'email' => 'kasirbaru@silaundry.test',
            'password' => 'password123',
        ])->assertRedirect(route('dashboard.kasir'));
    }

    public function test_create_user_validates_input(): void
    {
        $existing = User::factory()->kasir()->create();

        $this->actingAs($this->admin)
            ->from(route('users.create'))
            ->post(route('users.store'), [
                'name' => '',
                'username' => $existing->username,
                'email' => $existing->email,
                'password' => 'pendek',
                'password_confirmation' => 'beda',
                'role' => 'superuser',
            ])
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['name', 'username', 'email', 'password', 'role']);
    }

    public function test_pelanggan_role_requires_contact_data(): void
    {
        $this->actingAs($this->admin)
            ->post(route('users.store'), [
                'name' => 'Tanpa Alamat',
                'username' => 'tanpaalamat',
                'email' => 'tanpa@silaundry.test',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => User::ROLE_PELANGGAN,
            ])
            ->assertSessionHasErrors(['no_hp', 'alamat']);
    }

    public function test_admin_can_open_edit_form(): void
    {
        $user = User::factory()->kasir()->create();

        $this->actingAs($this->admin)
            ->get(route('users.edit', $user))
            ->assertOk()
            ->assertSee('Ubah Pengguna')
            ->assertSee($user->email);
    }

    public function test_admin_can_update_name_email_and_role(): void
    {
        $user = User::factory()->kasir()->create(['password' => 'passwordlama']);
        $oldHash = $user->password;

        $this->actingAs($this->admin)
            ->put(route('users.update', $user), [
                'name' => 'Nama Baru',
                'username' => 'namabaru',
                'email' => 'baru@silaundry.test',
                'role' => User::ROLE_ADMIN,
                'password' => '',
                'password_confirmation' => '',
            ])
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('success');

        $user->refresh();

        $this->assertSame('Nama Baru', $user->name);
        $this->assertSame('baru@silaundry.test', $user->email);
        $this->assertSame(User::ROLE_ADMIN, $user->role);
        $this->assertSame($oldHash, $user->password, 'Password tidak boleh berubah jika dikosongkan.');
    }

    public function test_admin_can_reset_user_password(): void
    {
        $user = User::factory()->kasir()->create();

        $this->actingAs($this->admin)
            ->put(route('users.update', $user), [
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'password' => 'passwordbaru123',
                'password_confirmation' => 'passwordbaru123',
            ])
            ->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check('passwordbaru123', $user->fresh()->password));
    }

    public function test_changing_role_to_pelanggan_creates_profile(): void
    {
        $user = User::factory()->kasir()->create();

        $this->actingAs($this->admin)
            ->put(route('users.update', $user), [
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'role' => User::ROLE_PELANGGAN,
                'no_hp' => '089999888777',
                'alamat' => 'Jl. Anggrek No. 3',
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('pelanggan', [
            'user_id' => $user->id,
            'alamat' => 'Jl. Anggrek No. 3',
        ]);
    }

    public function test_admin_cannot_change_own_role(): void
    {
        $this->actingAs($this->admin)
            ->put(route('users.update', $this->admin), [
                'name' => $this->admin->name,
                'username' => $this->admin->username,
                'email' => $this->admin->email,
                'role' => User::ROLE_KASIR,
            ])
            ->assertSessionHasErrors('role');

        $this->assertSame(User::ROLE_ADMIN, $this->admin->fresh()->role);
    }

    public function test_admin_can_delete_user(): void
    {
        $user = User::factory()->pelanggan()->create();
        Pelanggan::factory()->create(['user_id' => $user->id]);

        $this->actingAs($this->admin)
            ->delete(route('users.destroy', $user))
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('success');

        $this->assertModelMissing($user);
        $this->assertDatabaseMissing('pelanggan', ['user_id' => $user->id]);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $this->actingAs($this->admin)
            ->delete(route('users.destroy', $this->admin))
            ->assertSessionHas('error');

        $this->assertModelExists($this->admin);
    }

    public function test_pelanggan_with_transactions_cannot_be_deleted(): void
    {
        $kasir = User::factory()->kasir()->create();
        $pelanggan = Pelanggan::factory()->create();
        $layanan = Layanan::factory()->create();

        Transaksi::factory()->create([
            'kasir_id' => $kasir->id,
            'pelanggan_id' => $pelanggan->id,
            'layanan_id' => $layanan->id,
        ]);

        $this->actingAs($this->admin)
            ->delete(route('users.destroy', $pelanggan->user))
            ->assertSessionHas('error');

        $this->assertModelExists($pelanggan->user);
        $this->assertDatabaseCount('transaksi', 1);
    }

    public function test_kasir_with_transactions_cannot_be_deleted(): void
    {
        $kasir = User::factory()->kasir()->create();
        $pelanggan = Pelanggan::factory()->create();
        $layanan = Layanan::factory()->create();

        Transaksi::factory()->create([
            'kasir_id' => $kasir->id,
            'pelanggan_id' => $pelanggan->id,
            'layanan_id' => $layanan->id,
        ]);

        $this->actingAs($this->admin)
            ->delete(route('users.destroy', $kasir))
            ->assertSessionHas('error');

        $this->assertModelExists($kasir);
    }
}
