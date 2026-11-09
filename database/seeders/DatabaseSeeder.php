<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketAssignment;
use App\Models\TicketComment;
use App\Models\TicketStatusLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles
        $adminRole = Role::firstOrCreate(['name' => 'administrator']);
        $supportRole = Role::firstOrCreate(['name' => 'it_support']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);

        // 2. Default Initial Categories
        $categories = [
            'Hardware' => Category::firstOrCreate(['name' => 'Hardware'], ['description' => 'Kendala perangkat keras seperti PC, Laptop, Mouse, Keyboard.']),
            'Software' => Category::firstOrCreate(['name' => 'Software'], ['description' => 'Kendala sistem operasi, instalasi program, atau lisensi software.']),
            'Network' => Category::firstOrCreate(['name' => 'Network'], ['description' => 'Kendala koneksi internet, LAN, Wi-Fi, dan VPN.']),
            'Printer' => Category::firstOrCreate(['name' => 'Printer'], ['description' => 'Kendala printer, scanner, dan penggantian tinta/toner.']),
            'Account & Access' => Category::firstOrCreate(['name' => 'Account & Access'], ['description' => 'Permintaan reset password, akses email, dan izin folder jaringan.']),
            'Other' => Category::firstOrCreate(['name' => 'Other'], ['description' => 'Kendala IT lain yang tidak masuk ke kategori di atas.']),
        ];

        // 3. Default Users for Testing
        $admin = User::firstOrCreate(
            ['email' => 'admin@helpdesk.test'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $admin->syncRoles([$adminRole]);

        $support = User::firstOrCreate(
            ['email' => 'support@helpdesk.test'],
            [
                'name' => 'IT Support Specialist',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $support->syncRoles([$supportRole]);

        $employee = User::firstOrCreate(
            ['email' => 'employee@helpdesk.test'],
            [
                'name' => 'Staff Employee',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $employee->syncRoles([$employeeRole]);

        // 4. Dummy Tickets for Demonstrations & Reports (Hari 18)
        // Tiket 1: OPEN
        $t1 = Ticket::firstOrCreate(
            ['ticket_number' => 'TICK-202611-0001'],
            [
                'created_by' => $employee->id,
                'category_id' => $categories['Printer']->id,
                'subject' => 'Printer Epson L3110 Lampu Indikator Berkedip & Paper Jam',
                'description' => 'Saat mencetak laporan berkas, kertas tersangkut di dalam roller printer dan lampu indikator kertas menyala merah berkedip.',
                'priority' => 'medium',
                'status' => 'open',
            ]
        );
        TicketStatusLog::firstOrCreate(
            ['ticket_id' => $t1->id, 'new_status' => 'open'],
            ['changed_by' => $employee->id, 'notes' => 'Tiket kendala berhasil dibuat.']
        );

        // Tiket 2: ASSIGNED
        $t2 = Ticket::firstOrCreate(
            ['ticket_number' => 'TICK-202611-0002'],
            [
                'created_by' => $employee->id,
                'category_id' => $categories['Account & Access']->id,
                'subject' => 'Permintaan Reset Password Email & Hak Akses Shared Folder',
                'description' => 'Akun email Outlook kantor terkunci karena 3 kali salah memasukkan kata sandi. Sekaligus mohon pembukaan akses folder Finance-2026.',
                'priority' => 'low',
                'status' => 'assigned',
            ]
        );
        TicketAssignment::firstOrCreate(
            ['ticket_id' => $t2->id, 'assigned_to' => $support->id],
            ['assigned_by' => $admin->id, 'assigned_at' => now(), 'notes' => 'Ditugaskan untuk segera di-reset.']
        );
        TicketStatusLog::firstOrCreate(
            ['ticket_id' => $t2->id, 'new_status' => 'assigned'],
            ['changed_by' => $admin->id, 'notes' => 'Administrator menugaskan tiket kepada IT Support.']
        );

        // Tiket 3: IN_PROGRESS
        $t3 = Ticket::firstOrCreate(
            ['ticket_number' => 'TICK-202611-0003'],
            [
                'created_by' => $employee->id,
                'category_id' => $categories['Network']->id,
                'subject' => 'Koneksi Wi-Fi Lantai 2 Sering Putus & Kecepatan Drop',
                'description' => 'Jaringan Wi-Fi ruangan marketing lantai 2 sering disconnect otomatis setiap 15 menit dan kecepatan sangat lambat.',
                'priority' => 'high',
                'status' => 'in_progress',
            ]
        );
        TicketAssignment::firstOrCreate(
            ['ticket_id' => $t3->id, 'assigned_to' => $support->id],
            ['assigned_by' => $support->id, 'assigned_at' => now()->subHours(2), 'notes' => 'Klaim mandiri oleh teknisi.']
        );
        TicketStatusLog::firstOrCreate(
            ['ticket_id' => $t3->id, 'new_status' => 'in_progress'],
            ['changed_by' => $support->id, 'notes' => 'Petugas memulai pengecekan access point dan konfigurasi IP pool.']
        );
        TicketComment::firstOrCreate(
            ['ticket_id' => $t3->id, 'body' => 'Sedang dilakukan pembaruan firmware dan pengaturan ulang channel radio frekuensi Wi-Fi.'],
            ['user_id' => $support->id]
        );

        // Tiket 4: RESOLVED
        $t4 = Ticket::firstOrCreate(
            ['ticket_number' => 'TICK-202611-0004'],
            [
                'created_by' => $employee->id,
                'category_id' => $categories['Hardware']->id,
                'subject' => 'PC Kasir / Front Office Mati Total (Power Supply Rusak)',
                'description' => 'Komputer kasir mendadak mati saat operasional dan tidak ada lampu motherboard yang menyala.',
                'priority' => 'critical',
                'status' => 'resolved',
                'resolved_at' => now()->subHours(1),
            ]
        );
        TicketAssignment::firstOrCreate(
            ['ticket_id' => $t4->id, 'assigned_to' => $support->id],
            ['assigned_by' => $admin->id, 'assigned_at' => now()->subHours(3)]
        );
        TicketStatusLog::firstOrCreate(
            ['ticket_id' => $t4->id, 'new_status' => 'resolved'],
            ['changed_by' => $support->id, 'notes' => 'Unit Power Supply 450W yang rusak telah diganti. PC telah dibersihkan dan diuji coba stabil selama 30 menit.']
        );

        // Tiket 5: CLOSED
        $t5 = Ticket::firstOrCreate(
            ['ticket_number' => 'TICK-202611-0005'],
            [
                'created_by' => $employee->id,
                'category_id' => $categories['Software']->id,
                'subject' => 'Instalasi Software Adobe Acrobat Reader Pro & Lisensi',
                'description' => 'Mohon bantuan instalasi software PDF Editor berlisensi untuk kebutuhan penandatanganan dokumen digital.',
                'priority' => 'low',
                'status' => 'closed',
                'resolved_at' => now()->subDays(1),
                'closed_at' => now()->subHours(12),
            ]
        );
        TicketStatusLog::firstOrCreate(
            ['ticket_id' => $t5->id, 'new_status' => 'closed'],
            ['changed_by' => $employee->id, 'notes' => 'Pelapor mengonfirmasi bahwa software telah aktif dan tiket ditutup.']
        );

        // Tiket 6: REOPENED
        $t6 = Ticket::firstOrCreate(
            ['ticket_number' => 'TICK-202611-0006'],
            [
                'created_by' => $employee->id,
                'category_id' => $categories['Hardware']->id,
                'subject' => 'Layar Laptop Berkedip Garis Hitam Horisontal',
                'description' => 'Layar laptop kerja bergaris hitam di bagian bawah ketika engsel digerakkan.',
                'priority' => 'high',
                'status' => 'reopened',
            ]
        );
        TicketAssignment::firstOrCreate(
            ['ticket_id' => $t6->id, 'assigned_to' => $support->id],
            ['assigned_by' => $admin->id, 'assigned_at' => now()->subDays(2)]
        );
        TicketStatusLog::firstOrCreate(
            ['ticket_id' => $t6->id, 'new_status' => 'reopened'],
            ['changed_by' => $employee->id, 'notes' => 'Garis hitam di layar muncul kembali saat laptop dipindahkan. Mohon dicek kembali panel LCD.']
        );
    }
}
