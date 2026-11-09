<?php

namespace Tests\Feature;

use App\Livewire\Tickets\Create;
use App\Livewire\Tickets\Show;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TicketWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_employee_can_create_ticket_with_auto_generated_number(): void
    {
        $employee = User::where('email', 'employee@helpdesk.test')->first();
        $category = Category::where('is_active', true)->first();

        $this->actingAs($employee);

        Livewire::test(Create::class)
            ->set('subject', 'Keyboard tidak berfungsi')
            ->set('category_id', (string) $category->id)
            ->set('priority', 'high')
            ->set('description', 'Tombol space dan enter macet.')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('tickets', [
            'created_by' => $employee->id,
            'subject' => 'Keyboard tidak berfungsi',
            'priority' => 'high',
            'status' => 'open',
        ]);

        $ticket = Ticket::where('subject', 'Keyboard tidak berfungsi')->first();
        $this->assertStringStartsWith('TICK-' . now()->format('Ym') . '-', $ticket->ticket_number);

        $this->assertDatabaseHas('ticket_status_logs', [
            'ticket_id' => $ticket->id,
            'new_status' => 'open',
        ]);
    }

    public function test_employee_cannot_view_another_employee_ticket(): void
    {
        $employee1 = User::where('email', 'employee@helpdesk.test')->first();
        $employee2 = User::factory()->create([
            'is_active' => true,
            'password' => bcrypt('password'),
        ]);
        $employee2->assignRole('employee');

        $category = Category::first();
        $ticket = Ticket::create([
            'ticket_number' => 'TICK-202611-9999',
            'created_by' => $employee1->id,
            'category_id' => $category->id,
            'subject' => 'Tiket Rahasia',
            'description' => 'Deskripsi tiket',
            'priority' => 'medium',
            'status' => 'open',
        ]);

        $response = $this->actingAs($employee2)->get(route('tickets.show', $ticket->id));
        $response->assertStatus(403);
    }

    public function test_it_support_can_claim_and_progress_ticket(): void
    {
        $employee = User::where('email', 'employee@helpdesk.test')->first();
        $support = User::where('email', 'support@helpdesk.test')->first();
        $category = Category::first();

        $ticket = Ticket::create([
            'ticket_number' => 'TICK-202611-0010',
            'created_by' => $employee->id,
            'category_id' => $category->id,
            'subject' => 'Wi-Fi lantai 2 putus',
            'description' => 'Tidak bisa terhubung ke SSID kantor.',
            'priority' => 'critical',
            'status' => 'open',
        ]);

        $this->actingAs($support);

        // 1. Klaim Tiket
        Livewire::test(Show::class, ['ticket' => $ticket->id])
            ->call('claimTicket')
            ->assertHasNoErrors();

        $ticket->refresh();
        $this->assertEquals('assigned', $ticket->status);
        $this->assertEquals($support->id, $ticket->activeAssignment->assigned_to);

        // 2. Mulai Pengerjaan (In Progress)
        Livewire::test(Show::class, ['ticket' => $ticket->id])
            ->call('startProgress')
            ->assertHasNoErrors();

        $ticket->refresh();
        $this->assertEquals('in_progress', $ticket->status);

        // 3. Selesaikan Tiket (Resolve)
        Livewire::test(Show::class, ['ticket' => $ticket->id])
            ->set('resolutionNotes', 'Access point sudah direstart dan firmware diperbarui.')
            ->call('resolveTicket')
            ->assertHasNoErrors();

        $ticket->refresh();
        $this->assertEquals('resolved', $ticket->status);
        $this->assertNotNull($ticket->resolved_at);
    }

    public function test_employee_can_close_or_reopen_resolved_ticket(): void
    {
        $employee = User::where('email', 'employee@helpdesk.test')->first();
        $category = Category::first();

        $ticket = Ticket::create([
            'ticket_number' => 'TICK-202611-0020',
            'created_by' => $employee->id,
            'category_id' => $category->id,
            'subject' => 'Printer macet',
            'description' => 'Paper jam',
            'priority' => 'low',
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        $this->actingAs($employee);

        // Uji Reopen
        Livewire::test(Show::class, ['ticket' => $ticket->id])
            ->set('reopenNotes', 'Kertas masih sering tersangkut saat print bolak balik.')
            ->call('reopenTicket')
            ->assertHasNoErrors();

        $ticket->refresh();
        $this->assertEquals('reopened', $ticket->status);

        // Set kembali ke resolved untuk uji konfirmasi close
        $ticket->update(['status' => 'resolved']);

        Livewire::test(Show::class, ['ticket' => $ticket->id])
            ->call('closeTicket')
            ->assertHasNoErrors();

        $ticket->refresh();
        $this->assertEquals('closed', $ticket->status);
        $this->assertNotNull($ticket->closed_at);
    }

    public function test_admin_can_assign_ticket_to_it_support(): void
    {
        $admin = User::where('email', 'admin@helpdesk.test')->first();
        $support = User::where('email', 'support@helpdesk.test')->first();
        $employee = User::where('email', 'employee@helpdesk.test')->first();
        $category = Category::first();

        $ticket = Ticket::create([
            'ticket_number' => 'TICK-202611-0030',
            'created_by' => $employee->id,
            'category_id' => $category->id,
            'subject' => 'Setup PC Karyawan Baru',
            'description' => 'Install Windows dan Office.',
            'priority' => 'medium',
            'status' => 'open',
        ]);

        $this->actingAs($admin);

        Livewire::test(Show::class, ['ticket' => $ticket->id])
            ->set('assignToUserId', $support->id)
            ->set('assignmentNotes', 'Tolong prioritaskan sebelum siang ini.')
            ->call('assignTicket')
            ->assertHasNoErrors();

        $ticket->refresh();
        $this->assertEquals('assigned', $ticket->status);
        $this->assertEquals($support->id, $ticket->activeAssignment->assigned_to);
        $this->assertEquals('Tolong prioritaskan sebelum siang ini.', $ticket->activeAssignment->notes);
    }
}
