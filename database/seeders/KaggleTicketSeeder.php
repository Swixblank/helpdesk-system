<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketAssignment;
use App\Models\TicketComment;
use App\Models\TicketStatusLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KaggleTicketSeeder extends Seeder
{
    /**
     * Jumlah baris yang akan diimpor.
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
        $admin = User::where('email', 'admin@helpdesk.test')->first() ?? User::first();

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

        $this->command->info("Menghapus data tiket Kaggle sebelumnya untuk penyegaran status...");
        Ticket::where('ticket_number', 'like', 'TICK-KAG-%')->delete();

        $imported = 0;
        $statusCounts = [
            'open' => 0,
            'assigned' => 0,
            'in_progress' => 0,
            'resolved' => 0,
            'closed' => 0,
            'reopened' => 0,
        ];

        $this->command->info("Memulai proses impor data tiket Kaggle dengan distribusi 6 status lengkap...");

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false && $imported < $this->importLimit) {
                if (count($row) < 13) {
                    continue;
                }

                $ticketId = trim($row[0] ?? '');
                $product = trim($row[5] ?? '');
                $ticketType = trim($row[7] ?? '');
                $subjectRaw = trim($row[8] ?? '');
                $descRaw = trim($row[9] ?? '');
                $resolution = trim($row[11] ?? '');
                $priorityRaw = strtolower(trim($row[12] ?? ''));
                $timestampRaw = trim($row[14] ?? '');

                // 1. Nomor Tiket Unik
                $ticketNumber = 'TICK-KAG-' . str_pad($ticketId ?: (string) ($imported + 1), 5, '0', STR_PAD_LEFT);

                // 2. Subjek Masalah
                $subject = $subjectRaw;
                if (! empty($product)) {
                    $subject = $subjectRaw . ' - ' . $product;
                }
                $subject = mb_substr($subject, 0, 145);
                if (empty($subject)) {
                    $subject = 'Kendala Teknis ' . ($ticketType ?: 'Perangkat');
                }

                // 3. Deskripsi Masalah
                $desc = str_replace('{product_purchased}', $product ?: 'perangkat IT', $descRaw);
                if (empty($desc)) {
                    $desc = 'Tidak ada deskripsi rinci yang disertakan.';
                }

                // 4. Pemetaan Kategori
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

                // 5. Pemetaan Prioritas
                $priority = match ($priorityRaw) {
                    'critical' => 'critical',
                    'high' => 'high',
                    'low' => 'low',
                    default => 'medium',
                };

                // 6. Distribusi 6 Status Alur Kerja Secara Proporsional
                // ~25% open, ~15% assigned, ~20% in_progress, ~20% resolved, ~15% closed, ~5% reopened
                $rand = rand(1, 100);
                if ($rand <= 25) {
                    $status = 'open';
                } elseif ($rand <= 40) {
                    $status = 'assigned';
                } elseif ($rand <= 60) {
                    $status = 'in_progress';
                } elseif ($rand <= 80) {
                    $status = 'resolved';
                } elseif ($rand <= 95) {
                    $status = 'closed';
                } else {
                    $status = 'reopened';
                }

                $statusCounts[$status]++;

                // 7. Waktu Dibuat & Selesai
                try {
                    $createdAt = ! empty($timestampRaw) ? Carbon::parse($timestampRaw) : now()->subDays(rand(1, 45));
                } catch (\Exception $e) {
                    $createdAt = now()->subDays(rand(1, 45));
                }

                $resolvedAt = in_array($status, ['resolved', 'closed', 'reopened']) 
                    ? (clone $createdAt)->addHours(rand(2, 24)) 
                    : null;

                $closedAt = $status === 'closed' 
                    ? (clone $resolvedAt)->addHours(rand(1, 12)) 
                    : null;

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

                // 9. Log Status Awal (Selalu ada status OPEN saat tiket dibuat)
                TicketStatusLog::create([
                    'ticket_id' => $ticket->id,
                    'changed_by' => $employee->id,
                    'old_status' => null,
                    'new_status' => 'open',
                    'notes' => 'Tiket kendala berhasil dibuat dan menunggu penanganan.',
                    'created_at' => $createdAt,
                ]);

                // 10. Penugasan & Log Alur Kerja untuk Status Lanjutan
                if ($status !== 'open') {
                    $assignTime = (clone $createdAt)->addMinutes(rand(10, 30));

                    TicketAssignment::create([
                        'ticket_id' => $ticket->id,
                        'assigned_to' => $support->id,
                        'assigned_by' => $admin->id,
                        'assigned_at' => $assignTime,
                        'unassigned_at' => null,
                        'notes' => 'Tiket ditugaskan ke staf IT Support.',
                        'created_at' => $assignTime,
                    ]);

                    TicketStatusLog::create([
                        'ticket_id' => $ticket->id,
                        'changed_by' => $admin->id,
                        'old_status' => 'open',
                        'new_status' => 'assigned',
                        'notes' => "Administrator menugaskan tiket kepada {$support->name}.",
                        'created_at' => $assignTime,
                    ]);

                    if (in_array($status, ['in_progress', 'resolved', 'closed', 'reopened'])) {
                        $progressTime = (clone $assignTime)->addMinutes(rand(15, 60));

                        TicketStatusLog::create([
                            'ticket_id' => $ticket->id,
                            'changed_by' => $support->id,
                            'old_status' => 'assigned',
                            'new_status' => 'in_progress',
                            'notes' => 'Petugas memulai pemeriksaan dan pengerjaan kendala teknis.',
                            'created_at' => $progressTime,
                        ]);

                        if ($status === 'in_progress') {
                            TicketComment::create([
                                'ticket_id' => $ticket->id,
                                'user_id' => $support->id,
                                'body' => 'Sedang dalam pengecekan perangkat dan pengetesan hardware.',
                                'created_at' => (clone $progressTime)->addMinutes(10),
                            ]);
                        }
                    }

                    if (in_array($status, ['resolved', 'closed', 'reopened'])) {
                        $solusiText = ! empty($resolution) 
                            ? $resolution 
                            : 'Perbaikan teknis telah dilakukan dan perangkat sudah diuji berjalan normal.';

                        TicketStatusLog::create([
                            'ticket_id' => $ticket->id,
                            'changed_by' => $support->id,
                            'old_status' => 'in_progress',
                            'new_status' => 'resolved',
                            'notes' => 'Solusi perbaikan: ' . $solusiText,
                            'created_at' => $resolvedAt,
                        ]);
                    }

                    if ($status === 'closed') {
                        TicketStatusLog::create([
                            'ticket_id' => $ticket->id,
                            'changed_by' => $employee->id,
                            'old_status' => 'resolved',
                            'new_status' => 'closed',
                            'notes' => 'Pelapor mengonfirmasi masalah selesai sepenuhnya dan tiket ditutup.',
                            'created_at' => $closedAt,
                        ]);
                    }

                    if ($status === 'reopened') {
                        $reopenTime = (clone $resolvedAt)->addHours(rand(1, 5));

                        TicketStatusLog::create([
                            'ticket_id' => $ticket->id,
                            'changed_by' => $employee->id,
                            'old_status' => 'resolved',
                            'new_status' => 'reopened',
                            'notes' => 'Karyawan mengajukan pembukaan ulang: Kendala sempat normal namun muncul kembali saat perangkat digunakan.',
                            'created_at' => $reopenTime,
                        ]);
                    }
                }

                $imported++;
            }

            DB::commit();
            fclose($handle);

            $this->command->info("BERHASIL! {$imported} tiket berhasil diimpor dengan distribusi status:");
            foreach ($statusCounts as $s => $c) {
                $this->command->line(" - " . strtoupper($s) . ": {$c} tiket");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            $this->command->error("Terjadi error saat impor: " . $e->getMessage());
        }
    }
}
