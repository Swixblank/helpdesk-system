<?php

namespace Tests\Feature;

use App\Livewire\Users\Index;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_admin_can_access_users_page(): void
    {
        $admin = User::where('email', 'admin@helpdesk.test')->first();

        $response = $this->actingAs($admin)->get('/users');
        $response->assertStatus(200);
        $response->assertSee('Manajemen Pengguna & Staf');
    }

    public function test_non_admin_cannot_access_users_page(): void
    {
        $employee = User::where('email', 'employee@helpdesk.test')->first();
        $support = User::where('email', 'support@helpdesk.test')->first();

        $this->actingAs($employee)->get('/users')->assertStatus(403);
        $this->actingAs($support)->get('/users')->assertStatus(403);
    }

    public function test_admin_can_create_new_user_with_role(): void
    {
        $admin = User::where('email', 'admin@helpdesk.test')->first();

        $this->actingAs($admin);

        Livewire::test(Index::class)
            ->set('name', 'Ahmad Teknisi')
            ->set('email', 'ahmad@kumalacentral.co.id')
            ->set('password', 'password123')
            ->set('role', 'it_support')
            ->set('is_active', true)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'name' => 'Ahmad Teknisi',
            'email' => 'ahmad@kumalacentral.co.id',
            'is_active' => true,
        ]);

        $newUser = User::where('email', 'ahmad@kumalacentral.co.id')->first();
        $this->assertTrue($newUser->hasRole('it_support'));
    }

    public function test_admin_can_toggle_user_status(): void
    {
        $admin = User::where('email', 'admin@helpdesk.test')->first();
        $employee = User::where('email', 'employee@helpdesk.test')->first();

        $this->actingAs($admin);

        Livewire::test(Index::class)
            ->call('toggleStatus', $employee->id);

        $this->assertFalse($employee->fresh()->is_active);

        Livewire::test(Index::class)
            ->call('toggleStatus', $employee->id);

        $this->assertTrue($employee->fresh()->is_active);
    }

    public function test_admin_cannot_toggle_own_status(): void
    {
        $admin = User::where('email', 'admin@helpdesk.test')->first();

        $this->actingAs($admin);

        Livewire::test(Index::class)
            ->call('toggleStatus', $admin->id);

        $this->assertTrue($admin->fresh()->is_active);
    }

    public function test_admin_can_reset_user_filters(): void
    {
        $admin = User::where('email', 'admin@helpdesk.test')->first();

        $this->actingAs($admin);

        Livewire::test(Index::class)
            ->set('search', 'Ahmad')
            ->set('roleFilter', 'administrator')
            ->set('statusFilter', '1')
            ->call('resetFilters')
            ->assertSet('search', '')
            ->assertSet('roleFilter', '')
            ->assertSet('statusFilter', '');
    }

    public function test_admin_can_access_users_print_page(): void
    {
        $admin = User::where('email', 'admin@helpdesk.test')->first();

        $response = $this->actingAs($admin)->get('/users/print');
        $response->assertStatus(200);
        $response->assertSee('LAPORAN DATA PENGGUNA DAN STAF IT SUPPORT');
        $response->assertSee('PT. KUMALA CENTRAL PARTINDO');
    }
}
