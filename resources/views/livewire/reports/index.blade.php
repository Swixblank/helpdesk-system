<div>
    <!-- Header Halaman Laporan -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Laporan &amp; Rekapitulasi Tiket</h1>
            <p class="text-slate-500 text-sm mt-1">Pusat laporan terpadu sistem IT Helpdesk PT. Kumala Central Partindo — Saring, analisis, dan cetak dokumen resmi.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Dropdown Pilihan Cetak PDF (Potret / Lanskap) -->
            <div class="relative inline-flex rounded-lg shadow-2xs" x-data="{ open: false }">
                <a 
                    href="{{ route('reports.print', [
                        'type' => $reportType,
                        'status' => $statusFilter,
                        'category_id' => $categoryFilter,
                        'priority' => $priorityFilter,
                        'assigned_to' => $assignedToFilter,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'orientation' => 'portrait',
                    ]) }}" 
                    target="_blank"
                    class="inline-flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-4 py-2.5 rounded-l-lg transition cursor-pointer"
                    title="Cetak Laporan Format Potret (A4 Tegak)"
                >
                    <svg class="w-4 h-4 text-indigo-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Cetak Laporan (PDF)</span>
                </a>

                <button 
                    type="button" 
                    @click="open = !open" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-2.5 py-2.5 rounded-r-lg border-l border-indigo-500/50 transition cursor-pointer flex items-center justify-center"
                    title="Pilih Tata Letak (Potret / Lanskap)"
                >
                    <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Menu Dropdown Tata Letak -->
                <div 
                    x-show="open" 
                    @click.away="open = false" 
                    x-cloak
                    class="absolute right-0 top-full mt-2 w-72 bg-white rounded-xl shadow-xl border border-slate-200 py-1.5 z-50 divide-y divide-slate-100"
                >
                    <div class="px-3 py-1.5 text-2xs font-bold text-slate-400 uppercase tracking-wider">
                        Pilih Tata Letak Dokumen
                    </div>
                    <a 
                        href="{{ route('reports.print', [
                            'type' => $reportType,
                            'status' => $statusFilter,
                            'category_id' => $categoryFilter,
                            'priority' => $priorityFilter,
                            'assigned_to' => $assignedToFilter,
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                            'orientation' => 'portrait',
                        ]) }}" 
                        target="_blank"
                        @click="open = false"
                        class="flex items-start px-3.5 py-2.5 text-xs text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition group"
                    >
                        <span class="text-lg mr-3">📄</span>
                        <div>
                            <div class="font-semibold text-slate-900 group-hover:text-indigo-700 flex items-center gap-1.5">
                                <span>Format Potret (Tegak)</span>
                                <span class="bg-indigo-100 text-indigo-700 text-2xs px-1.5 py-0.5 rounded font-medium">Direkomendasikan</span>
                            </div>
                            <div class="text-2xs text-slate-500 mt-0.5 leading-relaxed">Format vertikal Word / PDF, rapi untuk pengesahan & arsip laporan dinas.</div>
                        </div>
                    </a>
                    <a 
                        href="{{ route('reports.print', [
                            'type' => $reportType,
                            'status' => $statusFilter,
                            'category_id' => $categoryFilter,
                            'priority' => $priorityFilter,
                            'assigned_to' => $assignedToFilter,
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                            'orientation' => 'landscape',
                        ]) }}" 
                        target="_blank"
                        @click="open = false"
                        class="flex items-start px-3.5 py-2.5 text-xs text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition group"
                    >
                        <span class="text-lg mr-3">📜</span>
                        <div>
                            <div class="font-semibold text-slate-900 group-hover:text-indigo-700">Format Lanskap (Mendatar)</div>
                            <div class="text-2xs text-slate-500 mt-0.5 leading-relaxed">Format horisontal melebar, cocok untuk pemantauan seluruh kolom tabel.</div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Tombol Export Excel / CSV -->
            <button 
                wire:click="exportExcel" 
                wire:loading.attr="disabled"
                class="inline-flex items-center space-x-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm px-4 py-2.5 rounded-lg shadow-2xs transition cursor-pointer disabled:opacity-50"
            >
                <svg class="w-4 h-4 text-emerald-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span wire:loading.remove wire:target="exportExcel">Export ke Excel (.CSV)</span>
                <span wire:loading wire:target="exportExcel">Menyiapkan Berkas...</span>
            </button>
        </div>
    </div>

    <!-- KATALOG 6 PILIHAN MENU LAPORAN (Sesuai Arahan Dosen: Minimal 5 Laporan) -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Pilih Jenis Menu Laporan (6 Jenis Laporan Tersedia):</span>
            </h2>
            <span class="text-2xs text-slate-400 font-medium">Klik salah satu laporan untuk memuat pratinjau & mencetak</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <!-- 1. Rekapitulasi Semua Tiket -->
            <button 
                type="button" 
                wire:click="setReportType('all')"
                class="text-left p-3.5 rounded-xl border transition-all cursor-pointer relative overflow-hidden flex flex-col justify-between {{ $reportType === 'all' ? 'bg-indigo-50/70 border-indigo-600 shadow-sm ring-1 ring-indigo-500' : 'bg-white border-slate-200 hover:border-indigo-300 hover:bg-slate-50/60 shadow-2xs' }}"
            >
                <div>
                    <div class="text-xl mb-1.5">📑</div>
                    <div class="font-bold text-xs text-slate-800 leading-snug {{ $reportType === 'all' ? 'text-indigo-900 font-extrabold' : '' }}">
                        1. Rekapitulasi Semua Tiket
                    </div>
                    <div class="text-[10px] text-slate-500 mt-1 line-clamp-2">
                        Seluruh riwayat kendala & audit trail lengkap.
                    </div>
                </div>
                @if ($reportType === 'all')
                    <span class="absolute top-2 right-2 flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-600"></span>
                    </span>
                @endif
            </button>

            <!-- 2. Laporan Status Penanganan -->
            <button 
                type="button" 
                wire:click="setReportType('status')"
                class="text-left p-3.5 rounded-xl border transition-all cursor-pointer relative overflow-hidden flex flex-col justify-between {{ $reportType === 'status' ? 'bg-indigo-50/70 border-indigo-600 shadow-sm ring-1 ring-indigo-500' : 'bg-white border-slate-200 hover:border-indigo-300 hover:bg-slate-50/60 shadow-2xs' }}"
            >
                <div>
                    <div class="text-xl mb-1.5">⏱️</div>
                    <div class="font-bold text-xs text-slate-800 leading-snug {{ $reportType === 'status' ? 'text-indigo-900 font-extrabold' : '' }}">
                        2. Status Penanganan
                    </div>
                    <div class="text-[10px] text-slate-500 mt-1 line-clamp-2">
                        Evaluasi tiket selesai vs tiket tertunda/berjalan.
                    </div>
                </div>
                @if ($reportType === 'status')
                    <span class="absolute top-2 right-2 flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-600"></span>
                    </span>
                @endif
            </button>

            <!-- 3. Kinerja Petugas IT Support -->
            <button 
                type="button" 
                wire:click="setReportType('technician')"
                class="text-left p-3.5 rounded-xl border transition-all cursor-pointer relative overflow-hidden flex flex-col justify-between {{ $reportType === 'technician' ? 'bg-indigo-50/70 border-indigo-600 shadow-sm ring-1 ring-indigo-500' : 'bg-white border-slate-200 hover:border-indigo-300 hover:bg-slate-50/60 shadow-2xs' }}"
            >
                <div>
                    <div class="text-xl mb-1.5">👨‍💻</div>
                    <div class="font-bold text-xs text-slate-800 leading-snug {{ $reportType === 'technician' ? 'text-indigo-900 font-extrabold' : '' }}">
                        3. Kinerja Petugas IT
                    </div>
                    <div class="text-[10px] text-slate-500 mt-1 line-clamp-2">
                        Beban tugas & produktivitas teknisi IT Support.
                    </div>
                </div>
                @if ($reportType === 'technician')
                    <span class="absolute top-2 right-2 flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-600"></span>
                    </span>
                @endif
            </button>

            <!-- 4. Distribusi Kategori Masalah -->
            <button 
                type="button" 
                wire:click="setReportType('category')"
                class="text-left p-3.5 rounded-xl border transition-all cursor-pointer relative overflow-hidden flex flex-col justify-between {{ $reportType === 'category' ? 'bg-indigo-50/70 border-indigo-600 shadow-sm ring-1 ring-indigo-500' : 'bg-white border-slate-200 hover:border-indigo-300 hover:bg-slate-50/60 shadow-2xs' }}"
            >
                <div>
                    <div class="text-xl mb-1.5">🏷️</div>
                    <div class="font-bold text-xs text-slate-800 leading-snug {{ $reportType === 'category' ? 'text-indigo-900 font-extrabold' : '' }}">
                        4. Kategori Masalah
                    </div>
                    <div class="text-[10px] text-slate-500 mt-1 line-clamp-2">
                        Analisis kerusakan perangkat & frekuensi aset.
                    </div>
                </div>
                @if ($reportType === 'category')
                    <span class="absolute top-2 right-2 flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-600"></span>
                    </span>
                @endif
            </button>

            <!-- 5. Skala Prioritas & Urgensi -->
            <button 
                type="button" 
                wire:click="setReportType('priority')"
                class="text-left p-3.5 rounded-xl border transition-all cursor-pointer relative overflow-hidden flex flex-col justify-between {{ $reportType === 'priority' ? 'bg-indigo-50/70 border-indigo-600 shadow-sm ring-1 ring-indigo-500' : 'bg-white border-slate-200 hover:border-indigo-300 hover:bg-slate-50/60 shadow-2xs' }}"
            >
                <div>
                    <div class="text-xl mb-1.5">🚨</div>
                    <div class="font-bold text-xs text-slate-800 leading-snug {{ $reportType === 'priority' ? 'text-indigo-900 font-extrabold' : '' }}">
                        5. Skala Prioritas
                    </div>
                    <div class="text-[10px] text-slate-500 mt-1 line-clamp-2">
                        Insiden kritis (Critical/High) & kedaruratan.
                    </div>
                </div>
                @if ($reportType === 'priority')
                    <span class="absolute top-2 right-2 flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-600"></span>
                    </span>
                @endif
            </button>

            <!-- 6. Data Pengguna & Staf IT -->
            <button 
                type="button" 
                wire:click="setReportType('users')"
                class="text-left p-3.5 rounded-xl border transition-all cursor-pointer relative overflow-hidden flex flex-col justify-between {{ $reportType === 'users' ? 'bg-indigo-50/70 border-indigo-600 shadow-sm ring-1 ring-indigo-500' : 'bg-white border-slate-200 hover:border-indigo-300 hover:bg-slate-50/60 shadow-2xs' }}"
            >
                <div>
                    <div class="text-xl mb-1.5">👥</div>
                    <div class="font-bold text-xs text-slate-800 leading-snug {{ $reportType === 'users' ? 'text-indigo-900 font-extrabold' : '' }}">
                        6. Pengguna & Staf
                    </div>
                    <div class="text-[10px] text-slate-500 mt-1 line-clamp-2">
                        Master data akun, karyawan, & hak akses staf.
                    </div>
                </div>
                @if ($reportType === 'users')
                    <span class="absolute top-2 right-2 flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-600"></span>
                    </span>
                @endif
            </button>
        </div>
    </div>

    <!-- BANNER INFORMASI LAPORAN AKTIF & KARTU METRIK RINGKASAN -->
    @php
        $activeReportMeta = match($reportType) {
            'status' => [
                'title' => 'Laporan Kendala IT Berdasarkan Status Penanganan',
                'desc' => 'Menyajikan evaluasi perbandingan antara kendala yang telah tuntas ditangani (Resolved / Closed) dengan kendala yang masih dalam proses atau tertunda penanganannya.',
                'icon' => '⏱️',
            ],
            'technician' => [
                'title' => 'Laporan Kinerja & Beban Kerja Petugas IT Support',
                'desc' => 'Menyajikan rekapitulasi produktivitas masing-masing teknisi IT: jumlah tiket yang ditugaskan, diselesaikan, sedang diproses, serta persentase rasio resolusi kendala.',
                'icon' => '👨‍💻',
            ],
            'category' => [
                'title' => 'Laporan Distribusi Kendala IT Berdasarkan Kategori Masalah',
                'desc' => 'Menyajikan pemetaan frekuensi insiden kerusakan berdasarkan kelompok aset teknologi (Hardware, Software, Jaringan, Printer, dll.) untuk keperluan pemeliharaan preventif.',
                'icon' => '🏷️',
            ],
            'priority' => [
                'title' => 'Laporan Insiden Kendala IT Berdasarkan Skala Prioritas',
                'desc' => 'Menyajikan rincian insiden berdasarkan tingkat urgensi operasional, terutama mendeteksi kendala berskala Critical dan High yang berdampak fatal bagi kantor/cabang.',
                'icon' => '🚨',
            ],
            'users' => [
                'title' => 'Laporan Master Data Pengguna & Staf Sistem IT Helpdesk',
                'desc' => 'Menyajikan master data seluruh akun pengguna yang terdaftar di sistem PT. Kumala Central Partindo, mencakup administrator sistem, teknisi penanggung jawab IT Support, dan staf karyawan pelapor kendala.',
                'icon' => '👥',
            ],
            default => [
                'title' => 'Laporan Rekapitulasi Penanganan Kendala IT (IT Helpdesk)',
                'desc' => 'Menyajikan seluruh rekapitulasi riwayat laporan kendala TI, kategori, tingkat prioritas, pelapor, teknisi penanggung jawab, hingga tanggal penyelesaian tiket.',
                'icon' => '📑',
            ],
        };
    @endphp

    <div class="bg-gradient-to-r from-indigo-900 to-indigo-800 text-white rounded-xl p-5 mb-6 shadow-md border border-indigo-700/50">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 bg-indigo-700/60 px-2.5 py-1 rounded-md text-2xs font-bold uppercase tracking-wider text-indigo-200 border border-indigo-500/30 mb-2">
                    <span>{{ $activeReportMeta['icon'] }} Laporan Aktif</span>
                    <span>•</span>
                    <span>Tipe: {{ strtoupper($reportType) }}</span>
                </div>
                <h3 class="text-lg font-bold tracking-tight text-white">{{ $activeReportMeta['title'] }}</h3>
                <p class="text-xs text-indigo-200 mt-1 max-w-3xl leading-relaxed">{{ $activeReportMeta['desc'] }}</p>
            </div>
            
            <div class="text-right shrink-0 bg-indigo-950/40 p-3 rounded-lg border border-indigo-600/30">
                <div class="text-2xs text-indigo-300 font-medium">Total Data Terfilter</div>
                @if ($reportType === 'users')
                    <div class="text-2xl font-black text-white font-mono mt-0.5">{{ $usersSummary['total'] }} <span class="text-xs font-normal text-indigo-200">Pengguna</span></div>
                @else
                    <div class="text-2xl font-black text-white font-mono mt-0.5">{{ $totalCount }} <span class="text-xs font-normal text-indigo-200">Tiket</span></div>
                @endif
            </div>
        </div>

        <!-- 4 KARTU METRIK CEPAT SESUAI TIPE LAPORAN -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-4 pt-4 border-t border-indigo-700/50 text-xs">
            @if ($reportType === 'technician')
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-indigo-200 uppercase font-semibold">Total Petugas IT</div>
                    <div class="text-lg font-bold text-white font-mono mt-0.5">{{ $techniciansSummary->count() }} Orang</div>
                </div>
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-indigo-200 uppercase font-semibold">Tiket Ditugaskan</div>
                    <div class="text-lg font-bold text-emerald-300 font-mono mt-0.5">{{ $techniciansSummary->sum('total_assigned') }} Tiket</div>
                </div>
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-indigo-200 uppercase font-semibold">Berhasil Selesai</div>
                    <div class="text-lg font-bold text-emerald-400 font-mono mt-0.5">{{ $techniciansSummary->sum('total_resolved') }} Tiket</div>
                </div>
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-indigo-200 uppercase font-semibold">Sedang Ditangani</div>
                    <div class="text-lg font-bold text-amber-300 font-mono mt-0.5">{{ $techniciansSummary->sum('total_in_progress') }} Tiket</div>
                </div>
            @elseif ($reportType === 'category')
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-indigo-200 uppercase font-semibold">Total Kategori Aset</div>
                    <div class="text-lg font-bold text-white font-mono mt-0.5">{{ $categoriesSummary->count() }} Kelompok</div>
                </div>
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-indigo-200 uppercase font-semibold">Kasus Terbanyak</div>
                    <div class="text-xs font-bold text-amber-300 truncate mt-1">{{ $categoriesSummary->first()?->name ?? '-' }} ({{ $categoriesSummary->first()?->total_tickets ?? 0 }})</div>
                </div>
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-indigo-200 uppercase font-semibold">Tiket Selesai</div>
                    <div class="text-lg font-bold text-emerald-300 font-mono mt-0.5">{{ $categoriesSummary->sum('resolved_tickets') }} Tiket</div>
                </div>
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-indigo-200 uppercase font-semibold">Tiket Pending</div>
                    <div class="text-lg font-bold text-rose-300 font-mono mt-0.5">{{ $categoriesSummary->sum('pending_tickets') }} Tiket</div>
                </div>
            @elseif ($reportType === 'priority')
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-rose-300 uppercase font-semibold">Insiden Kritis (Critical)</div>
                    <div class="text-lg font-bold text-rose-300 font-mono mt-0.5">{{ $prioritySummary['critical'] }} Kasus</div>
                </div>
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-orange-300 uppercase font-semibold">Insiden Tinggi (High)</div>
                    <div class="text-lg font-bold text-orange-300 font-mono mt-0.5">{{ $prioritySummary['high'] }} Kasus</div>
                </div>
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-blue-200 uppercase font-semibold">Insiden Sedang (Medium)</div>
                    <div class="text-lg font-bold text-blue-200 font-mono mt-0.5">{{ $prioritySummary['medium'] }} Kasus</div>
                </div>
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-slate-300 uppercase font-semibold">Insiden Ringan (Low)</div>
                    <div class="text-lg font-bold text-slate-300 font-mono mt-0.5">{{ $prioritySummary['low'] }} Kasus</div>
                </div>
            @elseif ($reportType === 'users')
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-indigo-200 uppercase font-semibold">Total Pengguna</div>
                    <div class="text-lg font-bold text-white font-mono mt-0.5">{{ $usersSummary['total'] }} Akun</div>
                </div>
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-emerald-300 uppercase font-semibold">Karyawan (Pelapor)</div>
                    <div class="text-lg font-bold text-emerald-300 font-mono mt-0.5">{{ $usersSummary['employees'] }} Orang</div>
                </div>
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-amber-300 uppercase font-semibold">Petugas IT Support</div>
                    <div class="text-lg font-bold text-amber-300 font-mono mt-0.5">{{ $usersSummary['it_support'] }} Teknisi</div>
                </div>
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-purple-300 uppercase font-semibold">Administrator</div>
                    <div class="text-lg font-bold text-purple-300 font-mono mt-0.5">{{ $usersSummary['admins'] }} Admin</div>
                </div>
            @elseif ($reportType === 'status')
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-emerald-300 uppercase font-semibold">Selesai (Resolved/Closed)</div>
                    <div class="text-lg font-bold text-emerald-300 font-mono mt-0.5">{{ $statusSummary['completed'] }} Tiket</div>
                </div>
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-blue-300 uppercase font-semibold">Sedang Diproses</div>
                    <div class="text-lg font-bold text-blue-300 font-mono mt-0.5">{{ $statusSummary['in_progress'] + $statusSummary['assigned'] }} Tiket</div>
                </div>
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-amber-300 uppercase font-semibold">Tiket Baru (Open)</div>
                    <div class="text-lg font-bold text-amber-300 font-mono mt-0.5">{{ $statusSummary['open'] }} Tiket</div>
                </div>
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-rose-300 uppercase font-semibold">Tiket Kambuh (Reopened)</div>
                    <div class="text-lg font-bold text-rose-300 font-mono mt-0.5">{{ $statusSummary['reopened'] }} Tiket</div>
                </div>
            @else
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-indigo-200 uppercase font-semibold">Total Semua Kendala</div>
                    <div class="text-lg font-bold text-white font-mono mt-0.5">{{ $statusSummary['total'] }} Tiket</div>
                </div>
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-emerald-300 uppercase font-semibold">Telah Selesai</div>
                    <div class="text-lg font-bold text-emerald-300 font-mono mt-0.5">{{ $statusSummary['completed'] }} Tiket</div>
                </div>
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-blue-300 uppercase font-semibold">Dalam Penanganan</div>
                    <div class="text-lg font-bold text-blue-300 font-mono mt-0.5">{{ $statusSummary['in_progress'] }} Tiket</div>
                </div>
                <div class="bg-white/10 rounded-lg p-2.5">
                    <div class="text-[10px] text-amber-300 uppercase font-semibold">Antrean Baru</div>
                    <div class="text-lg font-bold text-amber-300 font-mono mt-0.5">{{ $statusSummary['open'] }} Tiket</div>
                </div>
            @endif
        </div>
    </div>

    @if ($reportType === 'users')
        <!-- TABEL KHUSUS MASTER PENGGUNA & STAF (Laporan 6) -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden mb-6">
            <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <span>👥 Master Data Akun Pengguna & Staf IT ({{ $usersList->count() }} Pengguna)</span>
                    </h4>
                    <p class="text-2xs text-slate-500 mt-0.5">Daftar akun berhak akses ke sistem Helpdesk PT. Kumala Central Partindo.</p>
                </div>
                <div class="flex items-center">
                    <a 
                        href="{{ route('users.index') }}" 
                        class="inline-flex items-center gap-1 text-xs font-semibold text-slate-600 hover:text-indigo-600 px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 transition"
                    >
                        <span>Buka Manajemen Pengguna &rarr;</span>
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-100/70 text-[11px] uppercase text-slate-500 font-bold border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Nama Pengguna</th>
                            <th class="px-4 py-3">Email Kantor</th>
                            <th class="px-4 py-3">Peran / Hak Akses</th>
                            <th class="px-4 py-3 text-center">Status Akun</th>
                            <th class="px-4 py-3 text-center">Aktivitas Tiket</th>
                            <th class="px-4 py-3">Terdaftar Sejak</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach ($usersList as $idx => $user)
                            @php
                                $roleName = $user->roles->first()?->name ?? 'user';
                                $roleBadge = match($roleName) {
                                    'administrator' => 'bg-purple-100 text-purple-700 ring-1 ring-purple-200',
                                    'it_support' => 'bg-indigo-100 text-indigo-700 ring-1 ring-indigo-200',
                                    'employee' => 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200',
                                    default => 'bg-slate-100 text-slate-600',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3 text-slate-400 font-mono">{{ $idx + 1 }}</td>
                                <td class="px-4 py-3 font-bold text-slate-900 flex items-center gap-2">
                                    <span>{{ $user->name }}</span>
                                    @if ($user->id === auth()->id())
                                        <span class="text-2xs bg-indigo-50 text-indigo-700 px-1.5 py-0.5 rounded font-bold">Anda</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-mono text-slate-600">{{ $user->email }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $roleBadge }}">
                                        {{ str_replace('_', ' ', $roleName) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($user->is_active)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center text-slate-700">
                                    @if ($roleName === 'employee')
                                        <span class="font-semibold text-emerald-700 font-mono">{{ $user->tickets_count }}</span> dibuat
                                    @elseif ($roleName === 'it_support')
                                        <span class="font-semibold text-indigo-700 font-mono">{{ $user->assignments_count }}</span> ditangani
                                    @else
                                        <span class="text-slate-400 text-2xs">Admin</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-400 font-mono">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-slate-200 bg-slate-50 flex items-center justify-between text-xs text-slate-600">
                <span class="font-semibold text-slate-700">Jumlah Data : <span class="text-indigo-600 font-bold">{{ $usersList->count() }}</span> Pengguna</span>
                <span class="text-slate-400 text-2xs">Data tersinkronisasi otomatis dengan master tabel pengguna</span>
            </div>
        </div>
    @else
    <!-- FILTER BAR LANJUTAN -->
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
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Dari Tanggal (Periode Awal)</label>
                <input type="date" wire:model.live="startDate" class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-1.5 px-3 border bg-white">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Sampai Tanggal (Periode Akhir)</label>
                <input type="date" wire:model.live="endDate" class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-1.5 px-3 border bg-white">
            </div>

            <div class="sm:col-span-2 flex items-center text-xs text-slate-500 pt-5">
                Menemukan <span class="font-bold text-slate-800 mx-1 font-mono text-sm">{{ $totalCount }}</span> data tiket sesuai kriteria filter.
            </div>
        </div>
    </div>

    <!-- TABEL REKAPITULASI KHUSUS JIKA MEMILIH LAPORAN KINERJA TEKNISI -->
    @if ($reportType === 'technician')
        <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden mb-6">
            <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                    <span>👨‍💻 Matriks Evaluasi Kinerja & Beban Kerja Petugas IT Support</span>
                </h4>
                <span class="text-2xs text-slate-500 font-medium">Berdasarkan data penugasan sistem</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-100/70 text-[11px] uppercase text-slate-500 font-bold border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Nama Petugas IT Support</th>
                            <th class="px-4 py-3">Email Kantor</th>
                            <th class="px-4 py-3 text-center">Total Tiket Ditugaskan</th>
                            <th class="px-4 py-3 text-center text-emerald-700">Tiket Selesai</th>
                            <th class="px-4 py-3 text-center text-blue-700">Sedang Dikerjakan</th>
                            <th class="px-4 py-3 text-center">Tingkat Penyelesaian (%)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse ($techniciansSummary as $idx => $tech)
                            @php
                                $rate = $tech->total_assigned > 0 ? round(($tech->total_resolved / $tech->total_assigned) * 100, 1) : 0;
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3 text-slate-400">{{ $idx + 1 }}</td>
                                <td class="px-4 py-3 font-bold text-slate-900">{{ $tech->name }}</td>
                                <td class="px-4 py-3 text-slate-500 font-mono">{{ $tech->email }}</td>
                                <td class="px-4 py-3 text-center font-bold font-mono">{{ $tech->total_assigned }}</td>
                                <td class="px-4 py-3 text-center font-bold font-mono text-emerald-600">{{ $tech->total_resolved }}</td>
                                <td class="px-4 py-3 text-center font-bold font-mono text-blue-600">{{ $tech->total_in_progress }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <span class="font-bold font-mono {{ $rate >= 75 ? 'text-emerald-600' : ($rate >= 50 ? 'text-amber-600' : 'text-slate-600') }}">{{ $rate }}%</span>
                                        <div class="w-16 bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $rate }}%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-slate-400">Tidak ada data staf IT Support terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- TABEL REKAPITULASI KHUSUS JIKA MEMILIH LAPORAN DISTRIBUSI KATEGORI -->
    @if ($reportType === 'category')
        <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden mb-6">
            <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                    <span>🏷️ Matriks Distribusi Frekuensi Kendala per Kategori Aset</span>
                </h4>
                <span class="text-2xs text-slate-500 font-medium">Berdasarkan laporan karyawan</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-100/70 text-[11px] uppercase text-slate-500 font-bold border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Nama Kategori Masalah</th>
                            <th class="px-4 py-3">Deskripsi Lingkup</th>
                            <th class="px-4 py-3 text-center">Total Kasus</th>
                            <th class="px-4 py-3 text-center text-emerald-700">Selesai</th>
                            <th class="px-4 py-3 text-center text-amber-700">Belum Selesai</th>
                            <th class="px-4 py-3 text-center">Proporsi Kasus (%)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @php $allTotal = max(1, $categoriesSummary->sum('total_tickets')); @endphp
                        @forelse ($categoriesSummary as $idx => $cat)
                            @php $pct = round(($cat->total_tickets / $allTotal) * 100, 1); @endphp
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3 text-slate-400">{{ $idx + 1 }}</td>
                                <td class="px-4 py-3 font-bold text-slate-900">{{ $cat->name }}</td>
                                <td class="px-4 py-3 text-slate-500 max-w-xs truncate">{{ $cat->description ?: 'Kendala operasional ' . $cat->name }}</td>
                                <td class="px-4 py-3 text-center font-bold font-mono">{{ $cat->total_tickets }}</td>
                                <td class="px-4 py-3 text-center font-bold font-mono text-emerald-600">{{ $cat->resolved_tickets }}</td>
                                <td class="px-4 py-3 text-center font-bold font-mono text-amber-600">{{ $cat->pending_tickets }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="font-bold font-mono text-indigo-700">{{ $pct }}%</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-slate-400">Tidak ada data kategori terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- TABEL RINCIAN TIKET LAPORAN (Detail Table) -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                <span>📋 Rincian Transaksi Tiket Kendala ({{ $totalCount }} Data)</span>
            </h4>
            <span class="text-2xs text-slate-500 font-medium">Klik nomor tiket untuk melihat detail riwayat aktivitas</span>
        </div>

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
                                Tidak ada data tiket yang memenuhi kriteria filter laporan saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- FOOTER PAGINATION DENGAN JARAK PAS -->
        <div class="p-4 border-t border-slate-200 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-6 text-xs text-slate-600" style="gap: 24px;">
            <div class="flex items-center space-x-3 shrink-0">
                <span class="font-semibold text-slate-700">Jumlah Data : <span class="text-indigo-600 font-bold">{{ $tickets->total() }}</span> Tiket</span>
                <span class="text-slate-300">|</span>
                <span>Halaman ke : <span class="font-bold text-slate-800">{{ $tickets->currentPage() }}</span> dari {{ $tickets->lastPage() }}</span>
            </div>
            <div>
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
