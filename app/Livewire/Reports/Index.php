<?php

namespace App\Livewire\Reports;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Title('Laporan & Rekapitulasi Tiket - IT Helpdesk')]
#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $statusFilter = '';
    public string $categoryFilter = '';
    public string $priorityFilter = '';
    public string $assignedToFilter = '';
    public string $startDate = '';
    public string $endDate = '';

    public function mount(): void
    {
        abort_unless(Auth::user()->hasRole('administrator'), 403, 'Akses terbatas untuk Administrator.');
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

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        if ($this->priorityFilter) {
            $query->where('priority', $this->priorityFilter);
        }

        if ($this->assignedToFilter) {
            if ($this->assignedToFilter === 'unassigned') {
                $query->where('status', 'open')->doesntHave('activeAssignment');
            } else {
                $query->whereHas('activeAssignment', fn ($q) => $q->where('assigned_to', $this->assignedToFilter));
            }
        }

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

        $fileName = 'laporan-tiket-helpdesk-' . now()->format('Ymd-His') . '.csv';
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

        return view('livewire.reports.index', [
            'tickets' => $tickets,
            'totalCount' => $totalCount,
            'categories' => Category::orderBy('name')->get(),
            'itSupportStaff' => User::role('it_support')->where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}
