<?php

namespace Tests\Feature;

use App\Livewire\Reports\Index;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReportExportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_admin_can_access_reports_page(): void
    {
        $admin = User::where('email', 'admin@helpdesk.test')->first();

        $response = $this->actingAs($admin)->get('/reports');
        $response->assertStatus(200);
        $response->assertSee('Laporan & Rekapitulasi Tiket');
    }

    public function test_non_admin_cannot_access_reports_page(): void
    {
        $employee = User::where('email', 'employee@helpdesk.test')->first();
        $support = User::where('email', 'support@helpdesk.test')->first();

        $this->actingAs($employee)->get('/reports')->assertStatus(403);
        $this->actingAs($support)->get('/reports')->assertStatus(403);
    }

    public function test_admin_can_filter_and_export_tickets(): void
    {
        $admin = User::where('email', 'admin@helpdesk.test')->first();
        $employee = User::where('email', 'employee@helpdesk.test')->first();
        $category = Category::first();

        $ticket = Ticket::create([
            'ticket_number' => 'TICK-202611-0200',
            'created_by' => $employee->id,
            'category_id' => $category->id,
            'subject' => 'Printer Rusak Parah',
            'description' => 'Mati total',
            'priority' => 'critical',
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        $this->actingAs($admin);

        $component = Livewire::test(Index::class)
            ->set('statusFilter', 'resolved')
            ->assertSee('TICK-202611-0200')
            ->assertSee('Printer Rusak Parah')
            ->call('exportExcel');

        $response = $component->instance()->exportExcel();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('laporan-tiket-helpdesk-', $response->headers->get('Content-Disposition'));
    }
}
