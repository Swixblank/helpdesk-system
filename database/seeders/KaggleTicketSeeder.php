<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketAssignment;
use App\Models\TicketStatusLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KaggleTicketSeeder extends Seeder
{
    /**
     * Jumlah baris yang akan diimpor (dapat disesuaikan).
     */
    protected int $importLimit = 200;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = database_path('data/customer_support_tickets.csv');

        if (! file_exists($csvPath)) {
            $this->command->error("File CSV tidak ditemukan di: {$csvPath}");
            return;
        }

        $employee = User::where('email', 'employee@helpdesk.test')->first() ?? User::first();
        $support = User::where('email', 'support@helpdesk.test')->first() ?? User::first();

        // Ambil daftar kategori dari database
        $categories = Category::all()->keyBy('name');
        $defaultCategory = $categories->first();

        $handle = fopen($csvPath, 'r');
        if (! $handle) {
            $this->command->error("Gagal membuka file CSV.");
            return;
        }

        // Baca header baris pertama
        $header = fgetcsv($handle);
        if (! $header) {
            fclose($handle);
            return;
        }

        $imported = 0;
        $this->command->info("Memulai proses impor data tiket Kaggle (maksimal {$this->importLimit} baris)...");

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false && $imported < $this->importLimit) {
                // Pastikan baris memiliki data minimal
                if (count($row) < 13) {
                    continue;
                }

                $ticketId = trim($row[0] ?? '');
                $product = trim($row[5] ?? '');
                $ticketType = trim($row[7] ?? '');
                $subjectRaw = trim($row[8] ?? '');
                $descRaw = trim($row[9] ?? '');
                $statusRaw = strtolower(trim($row[10] ?? ''));
                $resolution = trim($row[11] ?? '');
                $priorityRaw = strtolower(trim($row[12] ?? ''));
                $timestampRaw = trim($row[14] ?? '');

                // 1. Nomor Tiket Unik
                $ticketNumber = 'TICK-KAG-' . str_pad($ticketId ?: (string) ($imported + 1), 5, '0', STR_PAD_LEFT);

                // Cek jika nomor tiket sudah ada
                if (Ticket::where('ticket_number', $ticketNumber)->exists()) {
                    continue;
                }

                // 2. Subjek
                $subject = $subjectRaw;
                if (! empty($product)) {
                    $subject = $subjectRaw . ' - ' . $product;
                }
                $subject = mb_substr($subject, 0, 145);
                if (empty($subject)) {
                    $subject = 'Kendala Teknis ' . ($ticketType ?: 'Perangkat');
                }

                // 3. Deskripsi
                $desc = str_replace('{product_purchased}', $product ?: 'perangkat IT', $descRaw);
                if (empty($desc)) {
                    $desc = 'Tidak ada deskripsi rinci yang disertakan.';
                }

                // 4. Mapping Kategori
                $categoryText = strtolower($ticketType . ' ' . $subjectRaw . ' ' . $product);
                $selectedCategory = $defaultCategory;

                if (str_contains($categoryText, 'hardware') || str_contains($categoryText, 'tv') || str_contains($categoryText, 'peripheral') || str_contains($categoryText, 'gopro') || str_contains($categoryText, 'laptop')) {
                    $selectedCategory = $categories['Hardware'] ?? $defaultCategory;
                } elseif (str_contains($categoryText, 'software') || str_contains($categoryText, 'setup') || str_contains($categoryText, 'installation') || str_contains($categoryText, 'bug')) {
                    $selectedCategory = $categories['Software'] ?? $defaultCategory;
                } elseif (str_contains($categoryText, 'network') || str_contains($categoryText, 'wifi') || str_contains($categoryText, 'connection')) {
                    $selectedCategory = $categories['Network'] ?? $defaultCategory;
                } elseif (str_contains($categoryText, 'printer') || str_contains($categoryText, 'scanner')) {
                    $selectedCategory = $categories['Printer'] ?? $defaultCategory;
                } elseif (str_contains($categoryText, 'account') || str_contains($categoryText, 'billing') || str_contains($categoryText, 'access') || str_contains($categoryText, 'login')) {
                    $selectedCategory = $categories['Account & Access'] ?? $defaultCategory;
                } else {
                    $selectedCategory = $categories['Other'] ?? $defaultCategory;
                }

                // 5. Mapping Prioritas
                $priority = match ($priorityRaw) {
                    'critical' => 'critical',
                    'high' => 'high',
                    'low' => 'low',
                    default => 'medium',
                };

                // 6. Mapping Status
                $status = match ($statusRaw) {
                    'closed' => 'closed',
                    'in progress' => 'in_progress',
                    'pending customer response' => 'resolved',
                    default => 'open',
                };

                // 7. Waktu Dibuat & Selesai
                try {
                    $createdAt = ! empty($timestampRaw) ? Carbon::parse($timestampRaw) : now()->subDays(rand(1, 45));
                } catch (\Exception $e) {
                    $createdAt = now()->subDays(rand(1, 45));
                }

                $resolvedAt = in_array($status, ['resolved', 'closed']) ? (clone $createdAt)->addHours(rand(2, 24)) : null;
                $closedAt = $status === 'closed' ? (clone $resolvedAt)->addHours(rand(1, 12)) : null;

                // 8. Simpan Tiket
                $ticket = Ticket::create([
                    'ticket_number' => $ticketNumber,
                    'created_by' => $employee->id,
                    'category_id' => $selectedCategory->id,
                    'subject' => $subject,
                    'description' => $desc,
                    'priority' => $priority,
                    'status' => $status,
                    'resolved_at' => $resolvedAt,
                    'closed_at' => $closedAt,
                    'created_at' => $createdAt,
                    'updated_at' => $closedAt ?? $resolvedAt ?? $createdAt,
                ]);

                // 9. Penugasan jika status bukan open
                if ($status !== 'open') {
                    TicketAssignment::create([
                        'ticket_id' => $ticket->id,
                        'assigned_to' => $support->id,
                        'assigned_by' => $support->id,
                        'assigned_at' => (clone $createdAt)->addMinutes(15),
                        'unassigned_at' => null,
                        'notes' => 'Penugasan otomatis dari import tiket.',
                        'created_at' => (clone $createdAt)->addMinutes(15),
                    ]);
                }

                // 10. Log Status Awal & Resolusi
                TicketStatusLog::create([
                    'ticket_id' => $ticket->id,
                    'changed_by' => $employee->id,
                    'old_status' => null,
                    'new_status' => 'open',
                    'notes' => 'Tiket kendala diimpor dari dataset Kaggle.',
                    'created_at' => $createdAt,
                ]);

                if (! empty($resolution) && in_array($status, ['resolved', 'closed'])) {
                    TicketStatusLog::create([
                        'ticket_id' => $ticket->id,
                        'changed_by' => $support->id,
                        'old_status' => 'in_progress',
                        'new_status' => $status,
                        'notes' => 'Solusi perbaikan: ' . $resolution,
                        'created_at' => $resolvedAt ?? now(),
                    ]);
                }

                $imported++;
            }

            DB::commit();
            fclose($handle);

            $this->command->info("BERHASIL! {$imported} data tiket dari dataset Kaggle berhasil diimpor.");
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            $this->command->error("Terjadi error saat impor: " . $e->getMessage());
        }
    }
}
