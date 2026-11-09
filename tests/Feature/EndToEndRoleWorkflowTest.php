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

class EndToEndRoleWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_complete_employee_to_it_support_to_resolution_lifecycle(): void
    {
        $employee = User::where('email', 'employee@helpdesk.test')->first();
        $support = User::where('email', 'support@helpdesk.test')->first();
        $category = Category::first();

        // 1. Employee Membuat Tiket Baru
        $this->actingAs($employee);
        Livewire::test(Create::class)
            ->set('subject', 'Monitor blank saat booting')
            ->set('category_id', (string) $category->id)
            ->set('priority', 'high')
            ->set('description', 'Layar monitor tidak menampilkan display.')
            ->call('save')
            ->assertHasNoErrors();

        $ticket = Ticket::where('subject', 'Monitor blank saat booting')->first();
        $this->assertNotNull($ticket);
        $this->assertEquals('open', $ticket->status);

        // 2. IT Support Mengambil Tiket (Self-Claim)
        $this->actingAs($support);
        Livewire::test(Show::class, ['ticket' => $ticket->id])
            ->call('claimTicket')
            ->assertHasNoErrors();

        $ticket->refresh();
        $this->assertEquals('assigned', $ticket->status);
        $this->assertEquals($support->id, $ticket->activeAssignment->assigned_to);

        // 3. IT Support Memulai Pengerjaan
        Livewire::test(Show::class, ['ticket' => $ticket->id])
            ->call('startProgress')
            ->assertHasNoErrors();

        $ticket->refresh();
        $this->assertEquals('in_progress', $ticket->status);

        // 4. IT Support & Employee Berkomunikasi via Komentar
        Livewire::test(Show::class, ['ticket' => $ticket->id])
            ->set('commentBody', 'Sedang dicek kabel HDMI dan adaptor monitor.')
            ->call('postComment')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('ticket_comments', [
            'ticket_id' => $ticket->id,
            'user_id' => $support->id,
            'body' => 'Sedang dicek kabel HDMI dan adaptor monitor.',
        ]);

        // 5. IT Support Menyelesaikan Tiket dengan Catatan Solusi
        Livewire::test(Show::class, ['ticket' => $ticket->id])
            ->set('resolutionNotes', 'Kabel HDMI diganti dengan kabel baru, display kembali normal.')
            ->call('resolveTicket')
            ->assertHasNoErrors();

        $ticket->refresh();
        $this->assertEquals('resolved', $ticket->status);
        $this->assertNotNull($ticket->resolved_at);

        // 6. Employee Mengonfirmasi Selesai (Closed)
        $this->actingAs($employee);
        Livewire::test(Show::class, ['ticket' => $ticket->id])
            ->call('closeTicket')
            ->assertHasNoErrors();

        $ticket->refresh();
        $this->assertEquals('closed', $ticket->status);
        $this->assertNotNull($ticket->closed_at);

        // 7. Verifikasi Komentar Terkunci Setelah Closed
        Livewire::test(Show::class, ['ticket' => $ticket->id])
            ->set('commentBody', 'Komentar tambahan pasca penutupan')
            ->call('postComment')
            ->assertStatus(403);
    }
}
