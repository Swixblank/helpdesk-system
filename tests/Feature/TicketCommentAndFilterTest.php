<?php

namespace Tests\Feature;

use App\Livewire\Tickets\Index;
use App\Livewire\Tickets\Show;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class TicketCommentAndFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        Storage::fake('local');
    }

    public function test_user_can_post_comment_on_ticket(): void
    {
        $employee = User::where('email', 'employee@helpdesk.test')->first();
        $category = Category::first();

        $ticket = Ticket::create([
            'ticket_number' => 'TICK-202611-0100',
            'created_by' => $employee->id,
            'category_id' => $category->id,
            'subject' => 'Printer tidak merespons',
            'description' => 'Lampu printer mati.',
            'priority' => 'low',
            'status' => 'open',
        ]);

        $this->actingAs($employee);

        Livewire::test(Show::class, ['ticket' => $ticket->id])
            ->set('commentBody', 'Sudah dicoba pasang kabel power lain tapi tetap mati.')
            ->call('postComment')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('ticket_comments', [
            'ticket_id' => $ticket->id,
            'user_id' => $employee->id,
            'body' => 'Sudah dicoba pasang kabel power lain tapi tetap mati.',
        ]);
    }

    public function test_user_can_post_comment_with_attachment(): void
    {
        $employee = User::where('email', 'employee@helpdesk.test')->first();
        $category = Category::first();

        $ticket = Ticket::create([
            'ticket_number' => 'TICK-202611-0101',
            'created_by' => $employee->id,
            'category_id' => $category->id,
            'subject' => 'Error Blue Screen Windows',
            'description' => 'BSOD muncul kode CRITICAL_PROCESS_DIED',
            'priority' => 'high',
            'status' => 'open',
        ]);

        $this->actingAs($employee);

        $file = UploadedFile::fake()->image('bsod-error.png');

        Livewire::test(Show::class, ['ticket' => $ticket->id])
            ->set('commentBody', 'Berikut foto error saat blue screen muncul.')
            ->set('commentAttachment', $file)
            ->call('postComment')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('ticket_attachments', [
            'ticket_id' => $ticket->id,
            'original_name' => 'bsod-error.png',
        ]);
    }

    public function test_comment_is_forbidden_on_closed_ticket(): void
    {
        $employee = User::where('email', 'employee@helpdesk.test')->first();
        $category = Category::first();

        $ticket = Ticket::create([
            'ticket_number' => 'TICK-202611-0102',
            'created_by' => $employee->id,
            'category_id' => $category->id,
            'subject' => 'Tiket Closed Lama',
            'description' => 'Sudah selesai',
            'priority' => 'low',
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        $this->actingAs($employee);

        Livewire::test(Show::class, ['ticket' => $ticket->id])
            ->set('commentBody', 'Pesan setelah closed.')
            ->call('postComment')
            ->assertStatus(403);
    }

    public function test_tickets_filtering_by_status_and_date(): void
    {
        $admin = User::where('email', 'admin@helpdesk.test')->first();
        $employee = User::where('email', 'employee@helpdesk.test')->first();
        $category = Category::first();

        Ticket::create([
            'ticket_number' => 'TICK-202611-0103',
            'created_by' => $employee->id,
            'category_id' => $category->id,
            'subject' => 'Tiket Open Hari Ini',
            'description' => 'Deskripsi',
            'priority' => 'critical',
            'status' => 'open',
            'created_at' => today(),
        ]);

        Ticket::create([
            'ticket_number' => 'TICK-202611-0104',
            'created_by' => $employee->id,
            'category_id' => $category->id,
            'subject' => 'Tiket Resolved Kemarin',
            'description' => 'Deskripsi',
            'priority' => 'low',
            'status' => 'resolved',
            'created_at' => now()->subDays(5),
        ]);

        $this->actingAs($admin);

        // Uji filter status 'open'
        Livewire::test(Index::class)
            ->set('statusFilter', 'open')
            ->assertSee('Tiket Open Hari Ini')
            ->assertDontSee('Tiket Resolved Kemarin');

        // Uji filter prioritas 'critical'
        Livewire::test(Index::class)
            ->set('priorityFilter', 'critical')
            ->assertSee('Tiket Open Hari Ini')
            ->assertDontSee('Tiket Resolved Kemarin');
    }
}
