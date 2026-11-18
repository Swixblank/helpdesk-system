<?php

namespace App\Livewire\Reports;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Title('Pusat Laporan & Rekapitulasi - IT Helpdesk')]
#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    #[Url(as: 'type')]
    public string $reportType = 'all'; // 'all', 'status', 'technician', 'category', 'priority', 'users'

    public string $statusFilter = '';
    public string $categoryFilter = '';
    public string $priorityFilter = '';
    public string $assignedToFilter = '';
    public string $startDate = '';
    public string $endDate = '';

    public function mount(): void
    {
        abort_unless(Auth::user()->hasRole('administrator'), 403, 'Akses terbatas untuk Administrator.');

        // Sinkronisasi tipe laporan awal jika ada query string type
        $allowedTypes = ['all', 'status', 'technician', 'category', 'priority', 'users'];
        if (!in_array($this->reportType, $allowedTypes)) {
            $this->reportType = 'all';
        }
    }

    public function setReportType(string $type): void
    {
        $allowedTypes = ['all', 'status', 'technician', 'category', 'priority', 'users'];
        if (in_array($type, $allowedTypes)) {
            $this->reportType = $type;
            $this->resetPage();
        }
    }

    public function updatingReportType(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPriorityFilter(): void
    {
        $this->resetPage();
    }

    public function updatingAssignedToFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStartDate(): void
    {
        $this->resetPage();
    }

    public function updatingEndDate(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset([
            'statusFilter',
            'categoryFilter',
            'priorityFilter',
            'assignedToFilter',
            'startDate',
            'endDate',
        ]);
        $this->resetPage();
    }

    protected function getFilteredQuery()
    {
        $query = Ticket::with(['creator', 'category', 'activeAssignment.assignedTo', 'statusLogs']);

        // Filter status jika dipilih
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Filter kategori jika dipilih
        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        // Filter prioritas jika dipilih
        if ($this->priorityFilter) {
            $query->where('priority', $this->priorityFilter);
        }

        // Filter penugasan petugas
        if ($this->assignedToFilter) {
            if ($this->assignedToFilter === 'unassigned') {
                $query->where('status', 'open')->doesntHave('activeAssignment');
            } else {
                $query->whereHas('activeAssignment', fn ($q) => $q->where('assigned_to', $this->assignedToFilter));
            }
        }

        // Filter rentang tanggal
        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        return $query->latest('created_at');
    }

    public function exportExcel(): StreamedResponse
    {
        abort_unless(Auth::user()->hasRole('administrator'), 403);

        if ($this->reportType === 'users') {
            $fileName = 'laporan-tiket-helpdesk-pengguna-' . now()->format('Ymd-His') . '.csv';
            $users = User::with('roles')->withCount(['tickets', 'assignments'])->orderBy('name')->get();
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

            return response()->stream(function () use ($users) {
                $handle = fopen('php://output', 'w');
                fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
                fputcsv($handle, [
                    'No',
                    'Nama Pengguna',
                    'Email Kantor',
                    'Hak Akses (Role)',
                    'Status Akun',
                    'Total Tiket Dibuat',
                    'Total Penanganan Tiket',
                    'Tanggal Terdaftar',
                ]);

                foreach ($users as $index => $u) {
                    fputcsv($handle, [
                        $index + 1,
                        $u->name,
                        $u->email,
                        strtoupper($u->roles->pluck('name')->implode(', ')),
                        $u->is_active ? 'Aktif' : 'Nonaktif',
                        $u->tickets_count,
                        $u->assignments_count,
                        $u->created_at->format('d/m/Y H:i'),
                    ]);
                }

                fclose($handle);
            }, 200, $headers);
        }

        $typePrefix = match ($this->reportType) {
            'status' => 'laporan-tiket-helpdesk-status-',
            'technician' => 'laporan-tiket-helpdesk-petugas-',
            'category' => 'laporan-tiket-helpdesk-kategori-',
            'priority' => 'laporan-tiket-helpdesk-prioritas-',
            default => 'laporan-tiket-helpdesk-',
        };

        $fileName = $typePrefix . now()->format('Ymd-His') . '.csv';
        $tickets = $this->getFilteredQuery()->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($tickets) {
            $handle = fopen('php://output', 'w');

            // Tambahkan UTF-8 BOM agar Microsoft Excel membuka aksen/karakter dengan benar
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header kolom laporan sesuai FR-11
            fputcsv($handle, [
                'No. Tiket',
                'Subjek Kendala',
                'Kategori',
                'Prioritas',
                'Pelapor (Employee)',
                'Email Pelapor',
                'Petugas (IT Support)',
                'Status',
                'Tanggal Dibuat',
                'Tanggal Selesai (Resolved)',
                'Tanggal Ditutup (Closed)',
                'Catatan Solusi / Status Terakhir',
            ]);

            foreach ($tickets as $t) {
                $latestNote = $t->statusLogs->last()?->notes ?? '-';
                $assignedStaff = $t->activeAssignment?->assignedTo?->name ?? 'Belum Ditugaskan';

                fputcsv($handle, [
                    $t->ticket_number,
                    $t->subject,
                    $t->category?->name ?? '-',
                    strtoupper($t->priority),
                    $t->creator?->name ?? '-',
                    $t->creator?->email ?? '-',
                    $assignedStaff,
                    strtoupper(str_replace('_', ' ', $t->status)),
                    $t->created_at->format('d/m/Y H:i'),
                    $t->resolved_at ? $t->resolved_at->format('d/m/Y H:i') : '-',
                    $t->closed_at ? $t->closed_at->format('d/m/Y H:i') : '-',
                    $latestNote,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function render()
    {
        $query = $this->getFilteredQuery();
        $totalCount = (clone $query)->count();
        $tickets = $query->paginate(15);

        // Ringkasan 1: Kinerja Petugas IT Support
        $techniciansSummary = User::role('it_support')
            ->withCount([
                'assignments as total_assigned',
                'assignments as total_resolved' => fn ($q) => $q->whereHas('ticket', fn ($t) => $t->whereIn('status', ['resolved', 'closed'])),
                'assignments as total_in_progress' => fn ($q) => $q->whereHas('ticket', fn ($t) => $t->where('status', 'in_progress')),
            ])
            ->get();

        // Ringkasan 2: Distribusi Kategori
        $categoriesSummary = Category::withCount([
            'tickets as total_tickets',
            'tickets as resolved_tickets' => fn ($q) => $q->whereIn('status', ['resolved', 'closed']),
            'tickets as pending_tickets' => fn ($q) => $q->whereIn('status', ['open', 'assigned', 'in_progress', 'reopened']),
        ])
        ->orderByDesc('total_tickets')
        ->get();

        // Ringkasan 3: Distribusi Prioritas
        $prioritySummary = [
            'critical' => Ticket::where('priority', 'critical')->count(),
            'high' => Ticket::where('priority', 'high')->count(),
            'medium' => Ticket::where('priority', 'medium')->count(),
            'low' => Ticket::where('priority', 'low')->count(),
        ];

        // Ringkasan 4: Distribusi Status
        $statusSummary = [
            'total' => Ticket::count(),
            'completed' => Ticket::whereIn('status', ['resolved', 'closed'])->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'assigned' => Ticket::where('status', 'assigned')->count(),
            'open' => Ticket::where('status', 'open')->count(),
            'reopened' => Ticket::where('status', 'reopened')->count(),
        ];

        // Ringkasan 5: Data Pengguna & Staf
        $usersSummary = [
            'total' => User::count(),
            'admins' => User::role('administrator')->count(),
            'it_support' => User::role('it_support')->count(),
            'employees' => User::role('employee')->count(),
            'active' => User::where('is_active', true)->count(),
        ];
        $usersList = User::with('roles')->withCount(['tickets', 'assignments'])->orderBy('name')->get();

        return view('livewire.reports.index', [
            'tickets' => $tickets,
            'totalCount' => $totalCount,
            'categories' => Category::orderBy('name')->get(),
            'itSupportStaff' => User::role('it_support')->where('is_active', true)->orderBy('name')->get(),
            'techniciansSummary' => $techniciansSummary,
            'categoriesSummary' => $categoriesSummary,
            'prioritySummary' => $prioritySummary,
            'statusSummary' => $statusSummary,
            'usersSummary' => $usersSummary,
            'usersList' => $usersList,
        ]);
    }
}
