<div>
    <!-- Breadcrumb & Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-500 mb-1">
                <a href="{{ route('tickets.index') }}" class="hover:text-indigo-600">Daftar Tiket</a>
                <span>/</span>
                <span class="font-mono text-indigo-600">{{ $ticket->ticket_number }}</span>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl font-bold text-slate-800">{{ $ticket->subject }}</h1>
                @php
                    $sClass = match($ticket->status) {
                        'open' => 'bg-amber-100 text-amber-800 border-amber-200',
                        'assigned' => 'bg-purple-100 text-purple-800 border-purple-200',
                        'in_progress' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'resolved' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'closed' => 'bg-slate-100 text-slate-700 border-slate-200',
                        'reopened' => 'bg-rose-100 text-rose-800 border-rose-200',
                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                    };
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $sClass }}">
                    {{ str_replace('_', ' ', $ticket->status) }}
                </span>
            </div>
        </div>

        <!-- Action Buttons Bar -->
        <div class="flex flex-wrap items-center gap-2">
            <!-- IT Support: Klaim Tiket Mandiri -->
            @if ($currentUser->hasRole('it_support') && $ticket->status === 'open' && !$ticket->activeAssignment)
                <button 
                    wire:click="claimTicket" 
                    wire:confirm="Apakah Anda yakin ingin mengambil dan menangani tiket kendala ini?"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-4 py-2 rounded-lg shadow-sm transition cursor-pointer flex items-center space-x-1.5"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Ambil Tiket Ini</span>
                </button>
            @endif

            <!-- Admin: Tugaskan Teknisi -->
            @if ($currentUser->hasRole('administrator') && !in_array($ticket->status, ['closed']))
                <button 
                    wire:click="openAssignModal" 
                    class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-semibold px-3 py-2 rounded-lg shadow-sm transition cursor-pointer flex items-center space-x-1.5"
                >
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>{{ $ticket->activeAssignment ? 'Ubah Petugas' : 'Tugaskan Teknisi' }}</span>
                </button>
            @endif

            <!-- IT Support / Admin: Mulai Pengerjaan -->
            @if (in_array($ticket->status, ['assigned', 'reopened']))
                @if ($currentUser->hasRole('administrator') || ($currentUser->hasRole('it_support') && $ticket->activeAssignment && $ticket->activeAssignment->assigned_to === $currentUser->id))
                    <button 
                        wire:click="startProgress" 
                        class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-lg shadow-sm transition cursor-pointer flex items-center space-x-1.5"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Mulai Pengerjaan</span>
                    </button>
                @endif
            @endif

            <!-- IT Support / Admin: Selesaikan Tiket -->
            @if ($ticket->status === 'in_progress')
                @if ($currentUser->hasRole('administrator') || ($currentUser->hasRole('it_support') && $ticket->activeAssignment && $ticket->activeAssignment->assigned_to === $currentUser->id))
                    <button 
                        wire:click="openResolveModal" 
                        class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-lg shadow-sm transition cursor-pointer flex items-center space-x-1.5"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Tandai Selesai (Resolve)</span>
                    </button>
                @endif
            @endif

            <!-- Employee / Admin: Konfirmasi Closed atau Reopen -->
            @if ($ticket->status === 'resolved')
                @if ($currentUser->hasRole('administrator') || ($currentUser->id === $ticket->created_by))
                    <button 
                        wire:click="closeTicket" 
                        wire:confirm="Konfirmasi bahwa masalah IT Anda telah selesai sepenuhnya dan tiket dapat ditutup?"
                        class="bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold px-3.5 py-2 rounded-lg shadow-sm transition cursor-pointer"
                    >
                        Konfirmasi Tutup (Closed)
                    </button>
                    <button 
                        wire:click="openReopenModal" 
                        class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold px-3.5 py-2 rounded-lg shadow-sm transition cursor-pointer"
                    >
                        Buka Kembali (Reopen)
                    </button>
                @endif
            @endif
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Kolom Kiri: Rincian Kendala & Solusi (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Box Deskripsi Tiket -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">
                    Uraian Kendala
                </h2>
                <div class="text-slate-700 text-sm leading-relaxed whitespace-pre-line">
                    {{ $ticket->description }}
                </div>
            </div>

            <!-- Box Lampiran Berkas Awal -->
            @if ($ticket->ticketAttachments->count() > 0)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">
                        Lampiran Berkas Tiket ({{ $ticket->ticketAttachments->count() }})
                    </h2>
                    <ul class="divide-y divide-slate-100">
                        @foreach ($ticket->ticketAttachments as $att)
                            <li class="py-3 flex items-center justify-between text-sm">
                                <div class="flex items-center space-x-3 overflow-hidden">
                                    <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                    </svg>
                                    <div class="truncate">
                                        <div class="font-medium text-slate-800 truncate">{{ $att->original_name }}</div>
                                        <div class="text-xs text-slate-400">
                                            {{ round($att->file_size / 1024, 1) }} KB &bull; Diunggah oleh {{ $att->uploader->name ?? 'User' }}
                                        </div>
                                    </div>
                                </div>
                                <button 
                                    wire:click="downloadAttachment({{ $att->id }})" 
                                    class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded transition cursor-pointer"
                                >
                                    Unduh
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Box Catatan Solusi Penyelesaian (Jika Resolved / Closed) -->
            @if (in_array($ticket->status, ['resolved', 'closed']))
                @php
                    $latestResolveLog = $ticket->statusLogs->where('new_status', 'resolved')->last();
                @endphp
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6">
                    <div class="flex items-center space-x-2 text-emerald-800 font-bold mb-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Catatan Solusi Perbaikan</span>
                    </div>
                    <div class="text-emerald-900 text-sm whitespace-pre-line leading-relaxed">
                        {{ $latestResolveLog ? $latestResolveLog->notes : 'Tiket telah diselesaikan oleh teknisi.' }}
                    </div>
                    @if ($ticket->resolved_at)
                        <div class="text-xs text-emerald-700 mt-3 pt-3 border-t border-emerald-200">
                            Waktu Selesai: {{ $ticket->resolved_at->format('d M Y H:i') }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Box Diskusi & Komentar Tiket (Hari 11 & 12) -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-6">
                <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">
                        Aktivitas & Diskusi Tiket ({{ $ticket->comments->count() }})
                    </h2>
                </div>

                <!-- Daftar Komentar Kronologis -->
                <div class="space-y-4">
                    @forelse ($ticket->comments as $comment)
                        <div class="p-4 rounded-xl border {{ $comment->user_id === $ticket->created_by ? 'bg-slate-50/80 border-slate-200' : 'bg-indigo-50/50 border-indigo-100' }}">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    <div class="w-7 h-7 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs font-bold">
                                        {{ substr($comment->user->name, 0, 1) }}
                                    </div>
                                    <span class="font-semibold text-sm text-slate-800">{{ $comment->user->name }}</span>
                                    <span class="text-[11px] font-mono px-2 py-0.5 rounded capitalize {{ $comment->user->hasRole('it_support') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'bg-slate-200 text-slate-700' }}">
                                        {{ $comment->user->getRoleNames()->first() ?? 'User' }}
                                    </span>
                                </div>
                                <span class="text-xs text-slate-400">
                                    {{ $comment->created_at->format('d M Y H:i') }}
                                </span>
                            </div>
                            <div class="text-sm text-slate-700 whitespace-pre-line leading-relaxed pl-9">
                                {{ $comment->body }}
                            </div>

                            <!-- Lampiran pada Komentar -->
                            @if ($comment->attachments->count() > 0)
                                <div class="mt-3 pl-9 flex flex-wrap gap-2">
                                    @foreach ($comment->attachments as $cAtt)
                                        <button 
                                            type="button"
                                            wire:click="downloadAttachment({{ $cAtt->id }})"
                                            class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded bg-white border border-slate-200 text-xs text-indigo-600 hover:text-indigo-800 hover:border-indigo-300 transition cursor-pointer shadow-2xs"
                                        >
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                            </svg>
                                            <span class="font-medium truncate max-w-xs">{{ $cAtt->original_name }}</span>
                                            <span class="text-[10px] text-slate-400">({{ round($cAtt->file_size / 1024, 1) }} KB)</span>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-400 text-xs">
                            Belum ada komentar pada tiket ini. Sampaikan pesan atau klarifikasi kendala di bawah.
                        </div>
                    @endforelse
                </div>

                <!-- Form Tambah Komentar -->
                @if ($ticket->status !== 'closed')
                    <form wire:submit="postComment" class="pt-4 border-t border-slate-100 space-y-3">
                        <div>
                            <label for="commentBody" class="block text-xs font-semibold text-slate-700 uppercase mb-1">
                                Tambah Komentar / Tanggapan
                            </label>
                            <textarea 
                                id="commentBody"
                                wire:model="commentBody" 
                                rows="3" 
                                class="w-full rounded-lg border-slate-300 shadow-2xs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3 border"
                                placeholder="Tulis pesan atau pertanyaan terkait penanganan tiket ini..."
                            ></textarea>
                            @error('commentBody') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Upload Lampiran pada Komentar -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-1">
                            <div class="flex items-center space-x-2">
                                <label for="commentAttachment" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-medium text-slate-700 bg-white hover:bg-slate-50 cursor-pointer transition">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                    </svg>
                                    <span>Lampirkan File (Maks 5MB)</span>
                                    <input id="commentAttachment" wire:model="commentAttachment" type="file" class="sr-only">
                                </label>

                                @if ($commentAttachment)
                                    <span class="text-xs text-indigo-700 font-mono truncate max-w-xs">
                                        {{ $commentAttachment->getClientOriginalName() }}
                                    </span>
                                    <button type="button" wire:click="$set('commentAttachment', null)" class="text-rose-600 hover:text-rose-800 text-xs font-bold">
                                        &times;
                                    </button>
                                @endif
                            </div>

                            <button 
                                type="submit" 
                                wire:loading.attr="disabled"
                                class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-2xs transition flex items-center justify-center space-x-1.5 cursor-pointer disabled:opacity-50"
                            >
                                <span wire:loading.remove wire:target="postComment">Kirim Komentar</span>
                                <span wire:loading wire:target="postComment">Mengirim...</span>
                            </button>
                        </div>
                        @error('commentAttachment') <span class="text-rose-600 text-xs block">{{ $message }}</span> @enderror
                    </form>
                @else
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-500 text-center">
                        Tiket ini telah berstatus <span class="font-bold text-slate-700">CLOSED</span>. Fitur komentar telah dinonaktifkan secara otomatis.
                    </div>
                @endif
            </div>
        </div>

        <!-- Kolom Kanan: Meta, Petugas & Linimasa Audit (1 Col) -->
        <div class="space-y-6">
            <!-- Card Informasi Tiket -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-4 text-sm">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider border-b border-slate-100 pb-2">
                    Informasi Tiket
                </h3>
                
                <div>
                    <span class="text-xs text-slate-400 block">Kategori</span>
                    <span class="font-medium text-slate-800">{{ $ticket->category->name ?? '-' }}</span>
                </div>

                <div>
                    <span class="text-xs text-slate-400 block">Tingkat Prioritas</span>
                    @php
                        $pClass = match($ticket->priority) {
                            'critical' => 'text-rose-600 font-bold',
                            'high' => 'text-orange-600 font-bold',
                            'medium' => 'text-blue-600 font-semibold',
                            default => 'text-slate-600 font-semibold',
                        };
                    @endphp
                    <span class="capitalize {{ $pClass }}">{{ $ticket->priority }}</span>
                </div>

                <div>
                    <span class="text-xs text-slate-400 block">Pelapor</span>
                    <span class="font-medium text-slate-800">{{ $ticket->creator->name }}</span>
                    <span class="text-xs text-slate-500 block">{{ $ticket->creator->email }}</span>
                </div>

                <div>
                    <span class="text-xs text-slate-400 block">Waktu Dibuat</span>
                    <span class="text-slate-700">{{ $ticket->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>

            <!-- Card Petugas Penanganan (Assignment) -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 text-sm">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider border-b border-slate-100 pb-2 mb-3">
                    Petugas IT Support
                </h3>
                @if ($ticket->activeAssignment && $ticket->activeAssignment->assignedTo)
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">
                            {{ substr($ticket->activeAssignment->assignedTo->name, 0, 2) }}
                        </div>
                        <div>
                            <div class="font-semibold text-slate-800">{{ $ticket->activeAssignment->assignedTo->name }}</div>
                            <div class="text-xs text-slate-400">
                                Ditugaskan: {{ $ticket->activeAssignment->assigned_at->format('d M Y H:i') }}
                            </div>
                        </div>
                    </div>
                    @if ($ticket->activeAssignment->notes)
                        <div class="mt-3 text-xs bg-slate-50 p-2.5 rounded border border-slate-200 text-slate-600 italic">
                            "{{ $ticket->activeAssignment->notes }}"
                        </div>
                    @endif
                @else
                    <div class="text-xs text-slate-400 py-2 italic text-center">
                        Tiket belum ditugaskan kepada staf IT Support.
                    </div>
                @endif
            </div>

            <!-- Card Linimasa Audit Status Logs -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 text-sm">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider border-b border-slate-100 pb-2 mb-4">
                    Riwayat Aktivitas & Status
                </h3>
                <div class="relative pl-6 space-y-4 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                    @forelse ($ticket->statusLogs as $log)
                        <div class="relative">
                            <div class="absolute -left-6 top-1 w-2.5 h-2.5 rounded-full bg-indigo-600 ring-4 ring-white"></div>
                            <div class="text-xs">
                                <span class="font-bold text-slate-800 uppercase tracking-tight">
                                    {{ str_replace('_', ' ', $log->new_status) }}
                                </span>
                                <span class="text-slate-400">&bull; {{ $log->created_at->format('d/m H:i') }}</span>
                            </div>
                            <div class="text-xs text-slate-600 mt-0.5">
                                Oleh: <span class="font-medium text-slate-700">{{ $log->actor->name ?? 'Sistem' }}</span>
                            </div>
                            @if ($log->notes)
                                <div class="text-[11px] text-slate-500 bg-slate-50 p-1.5 rounded mt-1 border border-slate-100">
                                    {{ $log->notes }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 text-center">Belum ada riwayat status.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 1: Penugasan Teknisi (Admin) -->
    @if ($showAssignModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full overflow-hidden border border-slate-200 animate-in fade-in zoom-in-95">
                <div class="px-6 py-4 bg-indigo-700 text-white flex items-center justify-between">
                    <h2 class="font-bold text-base">Tugaskan Petugas IT Support</h2>
                    <button wire:click="$set('showAssignModal', false)" class="text-indigo-200 hover:text-white cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form wire:submit="assignTicket" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Pilih Staf IT Support</label>
                        <select wire:model="assignToUserId" class="w-full text-sm rounded-lg border-slate-300 py-2 px-3 border bg-white focus:border-indigo-500">
                            <option value="">-- Pilih Staf --</option>
                            @foreach ($itSupportStaff as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->email }})</option>
                            @endforeach
                        </select>
                        @error('assignToUserId') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Catatan Penugasan (Opsional)</label>
                        <textarea wire:model="assignmentNotes" rows="3" class="w-full text-sm rounded-lg border-slate-300 py-2 px-3 border focus:border-indigo-500" placeholder="Instruksi khusus atau catatan untuk staf..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showAssignModal', false)" class="px-4 py-2 border rounded-lg text-sm text-slate-700 cursor-pointer">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 cursor-pointer">Simpan Penugasan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL 2: Selesaikan Tiket (Resolve) -->
    @if ($showResolveModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-lg w-full overflow-hidden border border-slate-200 animate-in fade-in zoom-in-95">
                <div class="px-6 py-4 bg-emerald-700 text-white flex items-center justify-between">
                    <h2 class="font-bold text-base">Catatan Solusi Penyelesaian</h2>
                    <button wire:click="$set('showResolveModal', false)" class="text-emerald-200 hover:text-white cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form wire:submit="resolveTicket" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            Tindakan / Solusi yang Dilakukan <span class="text-rose-500">*</span>
                        </label>
                        <textarea 
                            wire:model="resolutionNotes" 
                            rows="4" 
                            class="w-full text-sm rounded-lg border-slate-300 py-2 px-3 border focus:border-emerald-500" 
                            placeholder="Jelaskan secara jelas perbaikan yang telah diterapkan agar pelapor dapat memverifikasi..."
                        ></textarea>
                        @error('resolutionNotes') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showResolveModal', false)" class="px-4 py-2 border rounded-lg text-sm text-slate-700 cursor-pointer">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 cursor-pointer">Tandai Resolved</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL 3: Buka Kembali Tiket (Reopen) -->
    @if ($showReopenModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-lg w-full overflow-hidden border border-slate-200 animate-in fade-in zoom-in-95">
                <div class="px-6 py-4 bg-rose-700 text-white flex items-center justify-between">
                    <h2 class="font-bold text-base">Ajukan Pembukaan Ulang Tiket</h2>
                    <button wire:click="$set('showReopenModal', false)" class="text-rose-200 hover:text-white cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form wire:submit="reopenTicket" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            Alasan / Kendala yang Masih Berlanjut <span class="text-rose-500">*</span>
                        </label>
                        <textarea 
                            wire:model="reopenNotes" 
                            rows="4" 
                            class="w-full text-sm rounded-lg border-slate-300 py-2 px-3 border focus:border-rose-500" 
                            placeholder="Jelaskan mengapa masalah belum tuntas diselesaikan..."
                        ></textarea>
                        @error('reopenNotes') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showReopenModal', false)" class="px-4 py-2 border rounded-lg text-sm text-slate-700 cursor-pointer">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-rose-600 text-white rounded-lg text-sm font-semibold hover:bg-rose-700 cursor-pointer">Kirim Permintaan Reopen</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
