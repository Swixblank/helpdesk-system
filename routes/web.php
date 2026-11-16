<?php

use App\Livewire\Auth\Login;
use App\Livewire\Categories\Index as CategoriesIndex;
use App\Livewire\Dashboard;
use App\Livewire\Reports\Index as ReportsIndex;
use App\Livewire\Tickets\Create as TicketsCreate;
use App\Livewire\Tickets\Index as TicketsIndex;
use App\Livewire\Tickets\Show as TicketsShow;
use App\Livewire\Users\Index as UsersIndex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Modul Kategori (Hari 6)
    Route::get('/categories', CategoriesIndex::class)->name('categories.index');

    // Modul Pengguna & Staf (Khusus Administrator)
    Route::get('/users', UsersIndex::class)->name('users.index');
    Route::get('/users/print', function (Request $request) {
        abort_unless(auth()->user()->hasRole('administrator'), 403, 'Akses terbatas untuk Administrator.');

        $query = \App\Models\User::with(['roles', 'tickets', 'assignments']);

        if ($role = $request->query('role')) {
            $query->role($role);
        }

        if ($request->query('status') !== null && $request->query('status') !== '') {
            $query->where('is_active', (bool) $request->query('status'));
        }

        $users = $query->orderBy('name')->get();

        return view('users.print', [
            'users' => $users,
            'roleFilter' => $request->query('role', ''),
            'statusFilter' => $request->query('status', ''),
        ]);
    })->name('users.print');

    // Modul Tiket (Hari 7 & 8)
    Route::get('/tickets', TicketsIndex::class)->name('tickets.index');
    Route::get('/tickets/create', TicketsCreate::class)->name('tickets.create');
    Route::get('/tickets/{ticket}', TicketsShow::class)->name('tickets.show');
    Route::get('/tickets/{ticket}/print', function (\App\Models\Ticket $ticket) {
        abort_unless(auth()->user()->hasRole('administrator'), 403, 'Akses mencetak lembar tiket hanya diizinkan untuk Administrator.');

        $ticket->load(['creator', 'category', 'activeAssignment.assignedTo', 'comments.user']);

        return view('tickets.print', compact('ticket'));
    })->name('tickets.print');

    // Modul Laporan & Export Excel (Hari 16)
    Route::get('/reports', ReportsIndex::class)->name('reports.index');
    Route::get('/reports/print', function (\Illuminate\Http\Request $request) {
        abort_unless(auth()->user()->hasRole('administrator'), 403, 'Akses terbatas untuk Administrator.');

        $query = \App\Models\Ticket::with(['creator', 'category', 'activeAssignment.assignedTo']);

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($categoryId = $request->query('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($priority = $request->query('priority')) {
            $query->where('priority', $priority);
        }

        if ($assignedTo = $request->query('assigned_to')) {
            if ($assignedTo === 'unassigned') {
                $query->where('status', 'open')->doesntHave('activeAssignment');
            } else {
                $query->whereHas('activeAssignment', fn ($q) => $q->where('assigned_to', $assignedTo));
            }
        }

        if ($startDate = $request->query('start_date')) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate = $request->query('end_date')) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $tickets = $query->latest('created_at')->get();
        $categoryName = $request->query('category_id') ? \App\Models\Category::find($request->query('category_id'))?->name : null;

        $periodText = 'Semua Tanggal';
        if ($request->query('start_date') && $request->query('end_date')) {
            $periodText = \Carbon\Carbon::parse($request->query('start_date'))->format('d/m/Y') . ' s/d ' . \Carbon\Carbon::parse($request->query('end_date'))->format('d/m/Y');
        } elseif ($request->query('start_date')) {
            $periodText = 'Mulai ' . \Carbon\Carbon::parse($request->query('start_date'))->format('d/m/Y');
        } elseif ($request->query('end_date')) {
            $periodText = 'Sampai ' . \Carbon\Carbon::parse($request->query('end_date'))->format('d/m/Y');
        }

        $orientation = $request->query('orientation', 'portrait');
        if (!in_array($orientation, ['portrait', 'landscape'])) {
            $orientation = 'portrait';
        }

        return view('reports.print', [
            'tickets' => $tickets,
            'statusFilter' => $request->query('status'),
            'categoryName' => $categoryName,
            'priorityFilter' => $request->query('priority'),
            'periodText' => $periodText,
            'orientation' => $orientation,
        ]);
    })->name('reports.print');

    // Logout
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});
