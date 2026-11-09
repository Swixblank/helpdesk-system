<?php

use App\Livewire\Auth\Login;
use App\Livewire\Categories\Index as CategoriesIndex;
use App\Livewire\Dashboard;
use App\Livewire\Reports\Index as ReportsIndex;
use App\Livewire\Tickets\Create as TicketsCreate;
use App\Livewire\Tickets\Index as TicketsIndex;
use App\Livewire\Tickets\Show as TicketsShow;
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

    // Modul Tiket (Hari 7 & 8)
    Route::get('/tickets', TicketsIndex::class)->name('tickets.index');
    Route::get('/tickets/create', TicketsCreate::class)->name('tickets.create');
    Route::get('/tickets/{ticket}', TicketsShow::class)->name('tickets.show');

    // Modul Laporan & Export Excel (Hari 16)
    Route::get('/reports', ReportsIndex::class)->name('reports.index');

    // Logout
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});
