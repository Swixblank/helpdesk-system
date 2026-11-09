<?php

namespace App\Livewire\Tickets;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Daftar Tiket Kendala - IT Helpdesk')]
#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $categoryFilter = '';
    public string $priorityFilter = '';
    public string $assignedToFilter = '';
    public string $startDate = '';
    public string $endDate = '';
    public string $sortBy = 'latest';

    public function updatingSearch(): void
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

    public function updatingSortBy(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset([
            'search',
            'statusFilter',
            'categoryFilter',
            'priorityFilter',
            'assignedToFilter',
            'startDate',
            'endDate',
            'sortBy',
        ]);
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();
        $query = Ticket::with(['creator', 'category', 'activeAssignment.assignedTo']);

        // Hak akses isolasi role
        if ($user->hasRole('employee')) {
            $query->where('created_by', $user->id);
        } elseif ($user->hasRole('it_support')) {
            $query->where(function ($q) use ($user) {
                $q->whereHas('activeAssignment', fn ($sub) => $sub->where('assigned_to', $user->id))
                  ->orWhere('status', 'open');
            });
        }
        // Administrator dapat melihat semua tiket tanpa batasan

        // 1. Filter pencarian teks
        if ($this->search) {
            $term = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('ticket_number', 'like', $term)
                  ->orWhere('subject', 'like', $term);
            });
        }

        // 2. Filter status
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // 3. Filter kategori
        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        // 4. Filter prioritas
        if ($this->priorityFilter) {
            $query->where('priority', $this->priorityFilter);
        }

        // 5. Filter petugas (Hari 15)
        if ($this->assignedToFilter) {
            if ($this->assignedToFilter === 'unassigned') {
                $query->where('status', 'open')->doesntHave('activeAssignment');
            } else {
                $query->whereHas('activeAssignment', fn ($sub) => $sub->where('assigned_to', $this->assignedToFilter));
            }
        }

        // 6. Filter rentang tanggal (Hari 15)
        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        // 7. Pengurutan data (Sorting)
        if ($this->sortBy === 'oldest') {
            $query->oldest('created_at');
        } elseif ($this->sortBy === 'priority') {
            $query->orderByRaw("CASE priority WHEN 'critical' THEN 1 WHEN 'high' THEN 2 WHEN 'medium' THEN 3 WHEN 'low' THEN 4 ELSE 5 END")
                  ->latest('created_at');
        } else {
            $query->latest('created_at');
        }

        $tickets = $query->paginate(10);

        $itSupportStaff = $user->hasRole('administrator')
            ? User::role('it_support')->where('is_active', true)->orderBy('name')->get()
            : collect();

        return view('livewire.tickets.index', [
            'tickets' => $tickets,
            'categories' => Category::orderBy('name')->get(),
            'itSupportStaff' => $itSupportStaff,
        ]);
    }
}
