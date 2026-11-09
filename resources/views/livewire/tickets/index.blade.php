<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Daftar Tiket Kendala</h1>
            <p class="text-slate-500 text-sm mt-1">Pantau status laporan dan tindak lanjut penanganan teknis kendala IT.</p>
        </div>
        <div>
            <a 
                href="{{ route('tickets.create') }}" 
                class="inline-flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-4 py-2.5 rounded-lg shadow-2xs transition cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Buat Tiket Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar Lanjutan (Hari 15) -->
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-2xs mb-6 space-y-4">
        <!-- Baris 1: Pencarian & Filter Utama -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Search Text -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Pencarian</label>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Cari nomor tiket atau subjek masalah..."
                    class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border"
                >
            </div>

            <!-- Filter Status -->
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Status</label>
                <select 
                    wire:model.live="statusFilter" 
                    class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border bg-white"
                >
                    <option value="">Semua Status</option>
                    <option value="open">Open</option>
                    <option value="assigned">Assigned</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                    <option value="closed">Closed</option>
                    <option value="reopened">Reopened</option>
                </select>
            </div>

            <!-- Filter Kategori -->
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Kategori</label>
                <select 
                    wire:model.live="categoryFilter" 
                    class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border bg-white"
                >
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Baris 2: Prioritas, Petugas, Rentang Tanggal, & Sorting -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 pt-3 border-t border-slate-100 items-end">
            <!-- Filter Prioritas -->
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Prioritas</label>
                <select 
                    wire:model.live="priorityFilter" 
                    class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border bg-white"
                >
                    <option value="">Semua Prioritas</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="critical">Critical</option>
                </select>
            </div>

            <!-- Filter Petugas (Hanya Admin) -->
            @if (auth()->user()->hasRole('administrator'))
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Petugas Teknisi</label>
                    <select 
                        wire:model.live="assignedToFilter" 
                        class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border bg-white"
                    >
                        <option value="">Semua Petugas</option>
                        <option value="unassigned">Belum Ditugaskan</option>
                        @foreach ($itSupportStaff as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Dari Tanggal -->
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Dari Tanggal</label>
                <input 
                    type="date" 
                    wire:model.live="startDate" 
                    class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border bg-white"
                >
            </div>

            <!-- Sampai Tanggal -->
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Sampai Tanggal</label>
                <input 
                    type="date" 
                    wire:model.live="endDate" 
                    class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border bg-white"
                >
            </div>

            <!-- Urutan & Reset Filter -->
            <div class="flex items-center space-x-2">
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Urutan</label>
                    <select 
                        wire:model.live="sortBy" 
                        class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border bg-white"
                    >
                        <option value="latest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="priority">Prioritas Tertinggi</option>
                    </select>
                </div>

                @if ($search || $statusFilter || $categoryFilter || $priorityFilter || $assignedToFilter || $startDate || $endDate || $sortBy !== 'latest')
                    <div>
                        <label class="block text-xs font-semibold text-transparent uppercase mb-1">Reset</label>
                        <button 
                            wire:click="resetFilters" 
                            title="Reset Semua Filter"
                            class="p-2 text-rose-600 hover:text-white border border-rose-300 hover:bg-rose-600 rounded-lg bg-rose-50 transition cursor-pointer flex items-center justify-center"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3.5">No. Tiket</th>
                        <th class="px-5 py-3.5">Subjek</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5">Prioritas</th>
                        @if (!auth()->user()->hasRole('employee'))
                            <th class="px-5 py-3.5">Pelapor</th>
                        @endif
                        <th class="px-5 py-3.5">Petugas</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($tickets as $ticket)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-3.5 font-mono text-xs font-bold text-indigo-600">
                                <a href="{{ route('tickets.show', $ticket->id) }}" class="hover:underline">
                                    {{ $ticket->ticket_number }}
                                </a>
                            </td>
                            <td class="px-5 py-3.5 font-medium text-slate-800 max-w-xs truncate">
                                <a href="{{ route('tickets.show', $ticket->id) }}" class="hover:text-indigo-600">
                                    {{ $ticket->subject }}
                                </a>
                            </td>
                            <td class="px-5 py-3.5">
                                {{ $ticket->category->name ?? '-' }}
                            </td>
                            <td class="px-5 py-3.5">
                                @php
                                    $pClass = match($ticket->priority) {
                                        'critical' => 'bg-rose-100 text-rose-700',
                                        'high' => 'bg-orange-100 text-orange-700',
                                        'medium' => 'bg-blue-100 text-blue-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded text-[11px] font-semibold uppercase {{ $pClass }}">
                                    {{ $ticket->priority }}
                                </span>
                            </td>
                            @if (!auth()->user()->hasRole('employee'))
                                <td class="px-5 py-3.5 text-xs text-slate-700">
                                    {{ $ticket->creator->name ?? '-' }}
                                </td>
                            @endif
                            <td class="px-5 py-3.5 text-xs">
                                @if ($ticket->activeAssignment && $ticket->activeAssignment->assignedTo)
                                    <span class="text-slate-800 font-medium">
                                        {{ $ticket->activeAssignment->assignedTo->name }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">Belum ada</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                @php
                                    $sClass = match($ticket->status) {
                                        'open' => 'bg-amber-100 text-amber-700',
                                        'assigned' => 'bg-purple-100 text-purple-700',
                                        'in_progress' => 'bg-blue-100 text-blue-700',
                                        'resolved' => 'bg-emerald-100 text-emerald-700',
                                        'closed' => 'bg-slate-100 text-slate-700',
                                        'reopened' => 'bg-rose-100 text-rose-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded text-[11px] font-semibold uppercase {{ $sClass }}">
                                    {{ str_replace('_', ' ', $ticket->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-400">
                                {{ $ticket->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a 
                                    href="{{ route('tickets.show', $ticket->id) }}" 
                                    class="inline-flex items-center text-xs font-semibold text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1.5 rounded transition"
                                >
                                    Detail &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ !auth()->user()->hasRole('employee') ? 9 : 8 }}" class="px-5 py-10 text-center text-slate-400">
                                Tidak ada tiket kendala yang sesuai dengan kriteria filter saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($tickets->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>
