<div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Laporan & Rekapitulasi Tiket</h1>
            <p class="text-slate-500 text-sm mt-1">Saring data kendala IT, analisis riwayat penanganan, dan unduh laporan ke format Excel.</p>
        </div>
        <div>
            <button 
                wire:click="exportExcel" 
                wire:loading.attr="disabled"
                class="inline-flex items-center space-x-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm px-5 py-2.5 rounded-lg shadow-2xs transition cursor-pointer disabled:opacity-50"
            >
                <svg class="w-4 h-4 text-emerald-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span wire:loading.remove wire:target="exportExcel">Export ke Excel (.CSV)</span>
                <span wire:loading wire:target="exportExcel">Menyiapkan Berkas...</span>
            </button>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-2xs mb-6 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Filter Status -->
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Status Tiket</label>
                <select wire:model.live="statusFilter" class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border bg-white">
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
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Kategori Masalah</label>
                <select wire:model.live="categoryFilter" class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Prioritas -->
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Prioritas</label>
                <select wire:model.live="priorityFilter" class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border bg-white">
                    <option value="">Semua Prioritas</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="critical">Critical</option>
                </select>
            </div>

            <!-- Filter IT Support -->
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Petugas IT Support</label>
                <select wire:model.live="assignedToFilter" class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border bg-white">
                    <option value="">Semua Petugas</option>
                    <option value="unassigned">Belum Ditugaskan</option>
                    @foreach ($itSupportStaff as $staff)
                        <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tombol Reset -->
            <div class="flex items-end">
                <button 
                    type="button" 
                    wire:click="resetFilters" 
                    class="w-full py-2 px-3 text-xs font-semibold text-slate-600 hover:text-rose-600 border border-slate-300 hover:border-rose-300 rounded-lg bg-slate-50 hover:bg-rose-50 transition cursor-pointer flex items-center justify-center space-x-1"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span>Reset Filter</span>
                </button>
            </div>
        </div>

        <!-- Rentang Tanggal -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-3 border-t border-slate-100">
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Dari Tanggal Dibuat</label>
                <input type="date" wire:model.live="startDate" class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border bg-white">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Sampai Tanggal Dibuat</label>
                <input type="date" wire:model.live="endDate" class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border bg-white">
            </div>
            <div class="sm:col-span-2 flex items-center justify-end text-xs text-slate-500 pt-4">
                Menemukan <span class="font-bold text-slate-800 mx-1 font-mono text-sm">{{ $totalCount }}</span> data tiket sesuai kriteria filter.
            </div>
        </div>
    </div>

    <!-- Report Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase text-slate-500 border-b border-slate-200 font-bold">
                    <tr>
                        <th class="px-4 py-3.5">No. Tiket</th>
                        <th class="px-4 py-3.5">Subjek</th>
                        <th class="px-4 py-3.5">Kategori</th>
                        <th class="px-4 py-3.5">Prioritas</th>
                        <th class="px-4 py-3.5">Pembuat</th>
                        <th class="px-4 py-3.5">Petugas</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5">Dibuat</th>
                        <th class="px-4 py-3.5">Selesai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($tickets as $t)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-4 py-3 font-mono font-bold text-indigo-600">
                                <a href="{{ route('tickets.show', $t->id) }}" class="hover:underline">
                                    {{ $t->ticket_number }}
                                </a>
                            </td>
                            <td class="px-4 py-3 font-medium text-slate-800 max-w-xs truncate">
                                {{ $t->subject }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $t->category?->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $pClass = match($t->priority) {
                                        'critical' => 'bg-rose-100 text-rose-700',
                                        'high' => 'bg-orange-100 text-orange-700',
                                        'medium' => 'bg-blue-100 text-blue-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $pClass }}">
                                    {{ $t->priority }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-700">
                                {{ $t->creator?->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $t->activeAssignment?->assignedTo?->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $sClass = match($t->status) {
                                        'open' => 'bg-amber-100 text-amber-700',
                                        'assigned' => 'bg-purple-100 text-purple-700',
                                        'in_progress' => 'bg-blue-100 text-blue-700',
                                        'resolved' => 'bg-emerald-100 text-emerald-700',
                                        'closed' => 'bg-slate-100 text-slate-700',
                                        'reopened' => 'bg-rose-100 text-rose-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $sClass }}">
                                    {{ str_replace('_', ' ', $t->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-400">
                                {{ $t->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-slate-400">
                                {{ $t->resolved_at ? $t->resolved_at->format('d/m/Y H:i') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-10 text-center text-slate-400 text-sm">
                                Tidak ada data tiket yang memenuhi kriteria filter laporan.
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
