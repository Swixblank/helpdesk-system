<?php

namespace Tests\Feature;

use App\Livewire\Categories\Index;
use App\Models\Category;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_admin_can_access_categories_page(): void
    {
        $admin = User::where('email', 'admin@helpdesk.test')->first();

        $response = $this->actingAs($admin)->get('/categories');
        $response->assertStatus(200);
        $response->assertSee('Manajemen Kategori Kendala');
    }

    public function test_non_admin_cannot_access_categories_page(): void
    {
        $employee = User::where('email', 'employee@helpdesk.test')->first();

        $response = $this->actingAs($employee)->get('/categories');
        $response->assertStatus(403);
    }

    public function test_admin_can_create_new_category(): void
    {
        $admin = User::where('email', 'admin@helpdesk.test')->first();

        $this->actingAs($admin);

        Livewire::test(Index::class)
            ->call('openCreateModal')
            ->set('name', 'Cloud & Server Hosting')
            ->set('description', 'Kendala terkait VPS dan cloud storage.')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('categories', [
            'name' => 'Cloud & Server Hosting',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_toggle_category_status(): void
    {
        $admin = User::where('email', 'admin@helpdesk.test')->first();
        $category = Category::first();

        $this->actingAs($admin);

        Livewire::test(Index::class)
            ->call('toggleStatus', $category->id);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'is_active' => ! $category->is_active,
        ]);
    }
}
