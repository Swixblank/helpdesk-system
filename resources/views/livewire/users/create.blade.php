<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tambah Pengguna Baru</h1>
            <p class="text-slate-500 text-sm mt-1">Daftarkan akun pengguna baru, staf teknisi IT Support, atau administrator sistem.</p>
        </div>
        <a href="{{ route('users.index') }}" class="text-slate-600 hover:text-slate-900 text-sm font-medium">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <form wire:submit="save" class="p-6 sm:p-8 space-y-6">
            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    wire:model="name" 
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3 border" 
                    placeholder="Contoh: Budi Santoso"
                    autofocus
                >
                <div class="text-xs text-slate-400 mt-1">Nama lengkap karyawan atau staf teknisi IT.</div>
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
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3 border" 
                    placeholder="nama@helpdesk.test"
                >
                <div class="text-xs text-slate-400 mt-1">Digunakan untuk autentikasi login ke sistem IT Helpdesk.</div>
                @error('email') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Grid Peran & Status Akun -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Role / Hak Akses -->
                <div>
                    <label for="role" class="block text-sm font-semibold text-slate-700 mb-1">
                        Peran / Hak Akses <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        id="role" 
                        wire:model="role" 
                        class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3 border bg-white"
                    >
                        <option value="employee">Employee (Karyawan Pelapor)</option>
                        <option value="it_support">IT Support (Petugas Penanganan Kendala)</option>
                        <option value="administrator">Administrator (Pengelola Sistem Penuh)</option>
                    </select>
                    @error('role') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Status Keaktifan -->
                <div>
                    <label for="is_active" class="block text-sm font-semibold text-slate-700 mb-1">
                        Status Akun <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        id="is_active" 
                        wire:model="is_active" 
                        class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3 border bg-white"
                    >
                        <option value="1">Aktif (Dapat Login ke Sistem)</option>
                        <option value="0">Nonaktif (Akses Ditangguhkan)</option>
                    </select>
                    @error('is_active') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Kata Sandi -->
            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">
                    Kata Sandi Akun <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="password" 
                    id="password" 
                    wire:model="password" 
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3 border" 
                    placeholder="Minimal 8 karakter..."
                >
                <div class="text-xs text-slate-400 mt-1">Kombinasi minimal 8 karakter untuk keamanan akun.</div>
                @error('password') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-4 border-t border-slate-200 flex items-center justify-end space-x-3">
                <a 
                    href="{{ route('users.index') }}" 
                    class="px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 font-medium text-sm transition"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    wire:loading.attr="disabled" 
                    class="px-5 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm transition shadow-sm inline-flex items-center space-x-2 cursor-pointer disabled:opacity-50"
                >
                    <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Simpan Pengguna Baru</span>
                </button>
            </div>
        </form>
    </div>
</div>
