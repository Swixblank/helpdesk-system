<div>
    <!-- Welcome Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Halo, {{ $user->name }}!</h1>
            <p class="text-slate-500 text-sm mt-1">
                Selamat datang di Dashboard <span class="capitalize font-semibold text-indigo-600">{{ str_replace('_', ' ', $role) }}</span>.
            </p>
        </div>

        @if ($role === 'administrator')
            <!-- Filter Periode untuk Admin (Hari 14) -->
            <div class="inline-flex rounded-lg border border-slate-200 bg-white p-1 shadow-2xs text-xs font-medium">
                <button 
                    wire:click="$set('period', 'all')" 
                    class="px-3 py-1.5 rounded-md transition cursor-pointer {{ $period === 'all' ? 'bg-indigo-600 text-white font-semibold' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    Semua Waktu
                </button>
                <button 
                    wire:click="$set('period', 'month')" 
                    class="px-3 py-1.5 rounded-md transition cursor-pointer {{ $period === 'month' ? 'bg-indigo-600 text-white font-semibold' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    Bulan Ini
                </button>
                <button 
                    wire:click="$set('period', 'today')" 
                    class="px-3 py-1.5 rounded-md transition cursor-pointer {{ $period === 'today' ? 'bg-indigo-600 text-white font-semibold' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    Hari Ini
                </button>
            </div>
        @elseif ($role === 'employee')
            <div>
                <a 
                    href="{{ route('tickets.create') }}" 
                    class="inline-flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-4 py-2.5 rounded-lg shadow-2xs transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Buat Tiket Kendala Baru</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Stat Cards Berdasarkan Role -->
    @if ($role === 'administrator')
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-8">
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs">
                <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Tiket</div>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['total'] }}</div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-amber-200 shadow-2xs bg-amber-50/30">
                <div class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">Open</div>
                <div class="text-2xl font-extrabold text-amber-600 mt-1">{{ $stats['open'] }}</div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-purple-200 shadow-2xs bg-purple-50/30">
                <div class="text-[11px] font-bold text-purple-700 uppercase tracking-wider">Assigned</div>
                <div class="text-2xl font-extrabold text-purple-600 mt-1">{{ $stats['assigned'] }}</div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-blue-200 shadow-2xs bg-blue-50/30">
                <div class="text-[11px] font-bold text-blue-700 uppercase tracking-wider">In Progress</div>
                <div class="text-2xl font-extrabold text-blue-600 mt-1">{{ $stats['in_progress'] }}</div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-emerald-200 shadow-2xs bg-emerald-50/30">
                <div class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Resolved</div>
                <div class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $stats['resolved'] }}</div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs">
                <div class="text-[11px] font-bold text-slate-600 uppercase tracking-wider">Closed / Reopen</div>
                <div class="text-xl font-extrabold text-slate-800 mt-1">
                    {{ $stats['closed'] }} <span class="text-xs font-normal text-rose-500 font-mono">({{ $stats['reopened'] }} rpn)</span>
                </div>
            </div>
        </div>
    @elseif ($role === 'it_support')
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-5 rounded-xl border border-indigo-200 shadow-2xs bg-indigo-50/30">
                <div class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Ditugaskan ke Saya</div>
                <div class="text-3xl font-extrabold text-indigo-600 mt-2">{{ $stats['assigned_to_me'] }}</div>
            </div>
            <div class="bg-white p-5 rounded-xl border border-amber-200 shadow-2xs bg-amber-50/30">
                <div class="text-xs font-bold text-amber-700 uppercase tracking-wider">Tiket Open (Bisa Diambil)</div>
                <div class="text-3xl font-extrabold text-amber-600 mt-2">{{ $stats['available_open'] }}</div>
            </div>
            <div class="bg-white p-5 rounded-xl border border-blue-200 shadow-2xs bg-blue-50/30">
                <div class="text-xs font-bold text-blue-700 uppercase tracking-wider">Sedang Dikerjakan</div>
                <div class="text-3xl font-extrabold text-blue-600 mt-2">{{ $stats['in_progress'] }}</div>
            </div>
            <div class="bg-white p-5 rounded-xl border border-emerald-200 shadow-2xs bg-emerald-50/30">
                <div class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Selesai (Resolved)</div>
                <div class="text-3xl font-extrabold text-emerald-600 mt-2">{{ $stats['resolved'] }}</div>
            </div>
        </div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-2xs">
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Tiket Saya</div>
                <div class="text-3xl font-extrabold text-slate-900 mt-2">{{ $stats['my_tickets'] }}</div>
            </div>
            <div class="bg-white p-5 rounded-xl border border-amber-200 shadow-2xs bg-amber-50/30">
                <div class="text-xs font-bold text-amber-700 uppercase tracking-wider">Menunggu Diproses</div>
                <div class="text-3xl font-extrabold text-amber-600 mt-2">{{ $stats['open'] }}</div>
            </div>
            <div class="bg-white p-5 rounded-xl border border-blue-200 shadow-2xs bg-blue-50/30">
                <div class="text-xs font-bold text-blue-700 uppercase tracking-wider">Sedang Ditangani</div>
                <div class="text-3xl font-extrabold text-blue-600 mt-2">{{ $stats['in_progress'] }}</div>
            </div>
            <div class="bg-white p-5 rounded-xl border border-emerald-200 shadow-2xs bg-emerald-50/30">
                <div class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Selesai (Resolved)</div>
                <div class="text-3xl font-extrabold text-emerald-600 mt-2">{{ $stats['resolved'] }}</div>
            </div>
        </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 {{ $role === 'administrator' ? 'lg:grid-cols-3' : '' }} gap-8">
        <!-- Recent Tickets Table -->
        <div class="{{ $role === 'administrator' ? 'lg:col-span-2' : '' }} bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
            <div class="p-5 border-b border-slate-200 flex items-center justify-between">
                <h2 class="font-bold text-slate-800 text-sm uppercase tracking-wider">
                    {{ $role === 'employee' ? 'Tiket Saya Terbaru' : 'Aktivitas Tiket Terkini' }}
                </h2>
                <a href="{{ route('tickets.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                    Lihat Semua &rarr;
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3">No. Tiket</th>
                            <th class="px-5 py-3">Subjek</th>
                            <th class="px-5 py-3">Kategori</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentTickets as $ticket)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-3.5 font-bold text-indigo-600 font-mono text-xs">
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="hover:underline">
                                        {{ $ticket->ticket_number }}
                                    </a>
                                </td>
                                <td class="px-5 py-3.5 text-slate-800 font-medium max-w-xs truncate">
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="hover:text-indigo-600">
                                        {{ $ticket->subject }}
                                    </a>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-600">
                                    {{ $ticket->category->name ?? '-' }}
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
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $sClass }}">
                                        {{ str_replace('_', ' ', $ticket->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                                        Buka &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-400 text-xs">
                                    Belum ada tiket yang terdaftar pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sidebar / Category Summary for Admin -->
        @if ($role === 'administrator')
            <div class="bg-white rounded-xl border border-slate-200 shadow-2xs p-5">
                <h2 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">
                    Rekapitulasi Kategori
                </h2>
                <div class="space-y-4">
                    @php
                        $totalCatTickets = $categorySummary->sum('tickets_count');
                    @endphp
                    @forelse ($categorySummary as $cat)
                        @php
                            $percentage = $totalCatTickets > 0 ? round(($cat->tickets_count / $totalCatTickets) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="font-medium text-slate-700">{{ $cat->name }}</span>
                                <span class="text-slate-500 font-mono">{{ $cat->tickets_count }} tiket ({{ $percentage }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 text-center py-4">Belum ada kategori</div>
                    @endforelse
                </div>
            </div>
        @endif
    </div>
</div>
