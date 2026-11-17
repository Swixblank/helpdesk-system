<div>
    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Pengguna & Staf</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola akun pengguna, staf teknisi IT Support, dan peran hak akses sistem PT. Kumala Central Partindo.</p>
        </div>
        <div class="flex items-center gap-3.5" style="gap: 14px;">
            <a 
                href="{{ route('users.create') }}" 
                class="inline-flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-4 py-2.5 rounded-lg shadow-2xs transition cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                <span>+ Tambah Pengguna Baru</span>
            </a>
            <a 
                href="{{ route('users.print', ['role' => $roleFilter, 'status' => $statusFilter]) }}" 
                target="_blank"
                class="inline-flex items-center space-x-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 font-semibold text-sm px-4 py-2.5 rounded-lg shadow-2xs transition cursor-pointer"
                title="Cetak Laporan Data Pengguna & Staf (PDF)"
            >
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Cetak Laporan Staf (PDF)</span>
            </a>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
        <!-- Pencarian -->
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Cari Nama / Email</label>
            <div class="relative">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Ketik nama atau email..." 
                    class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 pl-3 pr-8 border bg-white"
                >
                @if ($search)
                    <button 
                        type="button" 
                        wire:click="$set('search', '')" 
                        class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-sm font-bold cursor-pointer"
                        title="Hapus pencarian"
                    >
                        &times;
                    </button>
                @endif
            </div>
        </div>

        <!-- Filter Role -->
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Peran / Hak Akses</label>
            <select wire:model.live="roleFilter" class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border bg-white">
                <option value="">Semua Peran</option>
                <option value="administrator">Administrator</option>
                <option value="it_support">IT Support</option>
                <option value="employee">Employee (Karyawan)</option>
            </select>
        </div>

        <!-- Filter Status -->
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Status Akun</label>
            <select wire:model.live="statusFilter" class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border bg-white">
                <option value="">Semua Status</option>
                <option value="1">Aktif</option>
                <option value="0">Nonaktif</option>
            </select>
        </div>

        <!-- Tombol Reset Pencarian & Filter -->
        <div>
            <button 
                type="button" 
                wire:click="resetFilters" 
                class="w-full py-2 px-3 text-xs font-semibold text-slate-600 hover:text-rose-600 border border-slate-300 hover:border-rose-300 rounded-lg bg-slate-50 hover:bg-rose-50 transition cursor-pointer flex items-center justify-center space-x-1.5 h-[38px]"
                title="Reset pencarian dan kembalikan seluruh filter ke kondisi semula"
            >
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>Reset Pencarian</span>
            </button>
        </div>
    </div>

    <!-- Tabel Data Pengguna -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3.5 text-center" style="width: 50px;">No</th>
                        <th class="px-5 py-3.5">Nama Pengguna</th>
                        <th class="px-5 py-3.5">Email Kantor</th>
                        <th class="px-5 py-3.5 text-center">Hak Akses (Role)</th>
                        <th class="px-5 py-3.5 text-center">Status Akun</th>
                        <th class="px-5 py-3.5 text-center">Aktivitas Tiket</th>
                        <th class="px-5 py-3.5">Terdaftar</th>
                        <th class="px-5 py-3.5 text-center">Tools</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $index => $u)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-3.5 text-center font-medium text-slate-500">
                                {{ $users->firstItem() + $index }}
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-slate-800">
                                {{ $u->name }}
                                @if ($u->id === auth()->id())
                                    <span class="ml-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700">Anda</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-slate-600 font-mono text-xs">
                                {{ $u->email }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @php
                                    $roleName = $u->getRoleNames()->first() ?? 'Tidak ada';
                                    $rBadge = match($roleName) {
                                        'administrator' => 'bg-purple-100 text-purple-700 border-purple-200',
                                        'it_support' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                        'employee' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        default => 'bg-slate-100 text-slate-600 border-slate-200',
                                    };
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $rBadge }} uppercase">
                                    {{ str_replace('_', ' ', $roleName) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if ($u->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-rose-100 text-rose-700">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center text-xs text-slate-500">
                                @if ($u->hasRole('employee'))
                                    <span>{{ $u->tickets->count() }} dibuat</span>
                                @elseif ($u->hasRole('it_support'))
                                    <span>{{ $u->assignments->count() }} ditangani</span>
                                @else
                                    <span class="text-slate-400">Admin</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-400">
                                {{ $u->created_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                <div class="inline-flex items-center space-x-2">
                                    <button 
                                        wire:click="openEditModal({{ $u->id }})" 
                                        class="text-indigo-600 hover:text-indigo-800 text-xs font-semibold cursor-pointer"
                                    >
                                        Edit
                                    </button>
                                    @if ($u->id !== auth()->id())
                                        <span class="text-slate-300">|</span>
                                        <button 
                                            wire:click="toggleStatus({{ $u->id }})" 
                                            wire:confirm="Ubah status keaktifan akun {{ $u->name }}?"
                                            class="text-xs font-semibold cursor-pointer {{ $u->is_active ? 'text-amber-600 hover:text-amber-800' : 'text-emerald-600 hover:text-emerald-800' }}"
                                        >
                                            {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-10 text-center text-slate-400 text-sm">
                                Tidak ada data pengguna yang sesuai dengan kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Indikator Ringkasan Data Bawah Tabel -->
        <div class="p-4 border-t border-slate-200 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-6 text-xs text-slate-600" style="gap: 24px;">
            <div class="flex items-center space-x-3 shrink-0">
                <span class="font-semibold text-slate-700">Jumlah Data : <span class="text-indigo-600 font-bold">{{ $users->total() }}</span> Pengguna</span>
                <span class="text-slate-300">|</span>
                <span>Halaman ke : <span class="font-bold text-slate-800">{{ $users->currentPage() }}</span> dari {{ $users->lastPage() }}</span>
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Form (Tambah / Edit Pengguna) -->
    @if ($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-lg w-full overflow-hidden border border-slate-200 animate-in fade-in zoom-in-95 duration-100">
                <div class="px-6 py-4 bg-indigo-700 text-white flex items-center justify-between">
                    <h2 class="font-bold text-lg">
                        {{ $editingUserId ? 'Edit Data Pengguna' : 'Form Tambah Pengguna Baru' }}
                    </h2>
                    <button wire:click="closeModal" class="text-indigo-200 hover:text-white cursor-pointer text-xl font-bold">&times;</button>
                </div>

                <form wire:submit="save" class="p-6 space-y-4">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            wire:model="name" 
                            class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border" 
                            placeholder="Contoh: Budi Santoso"
                        >
                        @error('name') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email Kantor -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">
                            Email Kantor <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            wire:model="email" 
                            class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border" 
                            placeholder="budi@kumalacentral.co.id"
                        >
                        @error('email') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">
                            Kata Sandi (Password) {{ $editingUserId ? '(Kosongkan jika tidak diubah)' : '*' }}
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            wire:model="password" 
                            class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border" 
                            placeholder="{{ $editingUserId ? 'Biarkan kosong untuk mempertahankan kata sandi lama' : 'Minimal 6 karakter' }}"
                        >
                        @error('password') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Role Hak Akses -->
                    <div>
                        <label for="role" class="block text-sm font-semibold text-slate-700 mb-1">
                            Peran / Hak Akses Sistem <span class="text-rose-500">*</span>
                        </label>
                        <select id="role" wire:model="role" class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border bg-white">
                            <option value="employee">Employee (Karyawan Pelapor Kendala)</option>
                            <option value="it_support">IT Support (Teknisi Penangan Kendala)</option>
                            <option value="administrator">Administrator (Pengelola Sistem Penuh)</option>
                        </select>
                        @error('role') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status Aktif -->
                    <div class="pt-2">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-slate-700">Akun Aktif (Dapat Login ke Sistem)</span>
                        </label>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button 
                            type="button" 
                            wire:click="closeModal" 
                            class="px-4 py-2 border border-slate-300 rounded-lg text-sm text-slate-600 hover:bg-slate-50 transition cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold shadow-2xs transition cursor-pointer"
                        >
                            {{ $editingUserId ? 'Simpan Perubahan' : 'Tambahkan Pengguna' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
