<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard - IT Helpdesk System')]
#[Layout('components.layouts.app')]
class Dashboard extends Component
{
    public string $period = 'all'; // all, month, today

    public function render()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first() ?? 'employee';

        $applyPeriod = function ($query) {
            if ($this->period === 'month') {
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
            } elseif ($this->period === 'today') {
                $query->whereDate('created_at', today());
            }
            return $query;
        };

        $stats = [];
        $recentTickets = collect();
        $categorySummary = collect();

        if ($role === 'administrator') {
            $stats = [
                'total' => $applyPeriod(Ticket::query())->count(),
                'open' => $applyPeriod(Ticket::where('status', 'open'))->count(),
                'assigned' => $applyPeriod(Ticket::where('status', 'assigned'))->count(),
                'in_progress' => $applyPeriod(Ticket::where('status', 'in_progress'))->count(),
                'resolved' => $applyPeriod(Ticket::where('status', 'resolved'))->count(),
                'closed' => $applyPeriod(Ticket::where('status', 'closed'))->count(),
                'reopened' => $applyPeriod(Ticket::where('status', 'reopened'))->count(),
            ];

            $categorySummary = Category::withCount(['tickets' => function ($q) use ($applyPeriod) {
                $applyPeriod($q);
            }])->get();

            $recentTickets = $applyPeriod(Ticket::with(['creator', 'category']))
                ->latest()
                ->take(8)
                ->get();

        } elseif ($role === 'it_support') {
            $stats = [
                'assigned_to_me' => Ticket::whereHas('activeAssignment', fn ($q) => $q->where('assigned_to', $user->id))->count(),
                'in_progress' => Ticket::where('status', 'in_progress')
                    ->whereHas('activeAssignment', fn ($q) => $q->where('assigned_to', $user->id))->count(),
                'resolved' => Ticket::where('status', 'resolved')
                    ->whereHas('activeAssignment', fn ($q) => $q->where('assigned_to', $user->id))->count(),
                'available_open' => Ticket::where('status', 'open')->doesntHave('activeAssignment')->count(),
            ];

            $recentTickets = Ticket::with(['creator', 'category'])
                ->where(function ($q) use ($user) {
                    $q->whereHas('activeAssignment', fn ($sub) => $sub->where('assigned_to', $user->id))
                      ->orWhere('status', 'open');
                })
                ->latest()
                ->take(8)
                ->get();

        } else { // employee
            $stats = [
                'my_tickets' => Ticket::where('created_by', $user->id)->count(),
                'open' => Ticket::where('created_by', $user->id)->where('status', 'open')->count(),
                'in_progress' => Ticket::where('created_by', $user->id)->where('status', 'in_progress')->count(),
                'resolved' => Ticket::where('created_by', $user->id)->where('status', 'resolved')->count(),
                'closed' => Ticket::where('created_by', $user->id)->where('status', 'closed')->count(),
                'reopened' => Ticket::where('created_by', $user->id)->where('status', 'reopened')->count(),
            ];

            $recentTickets = Ticket::with(['category'])
                ->where('created_by', $user->id)
                ->latest()
                ->take(8)
                ->get();
        }

        return view('livewire.dashboard', [
            'user' => $user,
            'role' => $role,
            'stats' => $stats,
            'recentTickets' => $recentTickets,
            'categorySummary' => $categorySummary,
        ]);
    }
}
