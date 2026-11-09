<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Kategori Kendala</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola master kategori masalah IT yang dapat dipilih oleh karyawan saat membuat tiket.</p>
        </div>
        <div>
            <button 
                wire:click="openCreateModal" 
                class="inline-flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg shadow-sm transition cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Kategori</span>
            </button>
        </div>
    </div>

    <!-- Category Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5">Nama Kategori</th>
                        <th class="px-6 py-3.5">Deskripsi</th>
                        <th class="px-6 py-3.5 text-center">Jumlah Tiket</th>
                        <th class="px-6 py-3.5 text-center">Status</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $category->name }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 max-w-md">
                                {{ $category->description ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-slate-100 text-slate-700 font-mono text-xs px-2.5 py-1 rounded-full font-medium">
                                    {{ $category->tickets_count }} tiket
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($category->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button 
                                    wire:click="openEditModal({{ $category->id }})" 
                                    class="text-indigo-600 hover:text-indigo-800 text-xs font-medium cursor-pointer"
                                >
                                    Edit
                                </button>
                                <span class="text-slate-300">|</span>
                                <button 
                                    wire:click="toggleStatus({{ $category->id }})" 
                                    class="text-xs font-medium cursor-pointer {{ $category->is_active ? 'text-amber-600 hover:text-amber-800' : 'text-emerald-600 hover:text-emerald-800' }}"
                                >
                                    {{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                Belum ada kategori yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Form (Create / Edit) -->
    @if ($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-lg w-full overflow-hidden border border-slate-200 animate-in fade-in zoom-in-95 duration-100">
                <div class="px-6 py-4 bg-indigo-700 text-white flex items-center justify-between">
                    <h2 class="font-bold text-lg">
                        {{ $editingCategoryId ? 'Edit Kategori Kendala' : 'Tambah Kategori Baru' }}
                    </h2>
                    <button wire:click="closeModal" class="text-indigo-200 hover:text-white cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form wire:submit="save" class="p-6 space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Kategori <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            id="name" 
                            wire:model="name" 
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3 border"
                            placeholder="Contoh: Database & Backup"
                            autofocus
                        >
                        @error('name') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Lingkup Masalah</label>
                        <textarea 
                            id="description" 
                            wire:model="description" 
                            rows="3" 
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3 border"
                            placeholder="Deskripsi singkat jenis kendala yang tercakup..."
                        ></textarea>
                        @error('description') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-2">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input 
                                type="checkbox" 
                                wire:model="is_active" 
                                class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4"
                            >
                            <span class="text-sm font-medium text-slate-700">Status Aktif (dapat dipilih untuk tiket baru)</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                        <button 
                            type="button" 
                            wire:click="closeModal" 
                            class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-50 cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium shadow-sm transition cursor-pointer"
                        >
                            {{ $editingCategoryId ? 'Simpan Perubahan' : 'Tambah Kategori' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
