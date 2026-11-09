<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Buat Tiket Kendala Baru</h1>
            <p class="text-slate-500 text-sm mt-1">Sampaikan keluhan teknis Anda dengan jelas agar tim IT Support dapat segera menindaklanjuti.</p>
        </div>
        <a href="{{ route('tickets.index') }}" class="text-slate-600 hover:text-slate-900 text-sm font-medium">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <form wire:submit="save" class="p-6 sm:p-8 space-y-6">
            <!-- Subjek -->
            <div>
                <label for="subject" class="block text-sm font-semibold text-slate-700 mb-1">
                    Subjek Masalah <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="subject" 
                    wire:model="subject" 
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3 border" 
                    placeholder="Contoh: Layar monitor berkedip garis hitam"
                    autofocus
                >
                <div class="text-xs text-slate-400 mt-1">Tuliskan ringkasan inti kendala (maksimal 150 karakter).</div>
                @error('subject') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Kategori & Prioritas Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Kategori -->
                <div>
                    <label for="category_id" class="block text-sm font-semibold text-slate-700 mb-1">
                        Kategori Masalah <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        id="category_id" 
                        wire:model="category_id" 
                        class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3 border bg-white"
                    >
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Prioritas -->
                <div>
                    <label for="priority" class="block text-sm font-semibold text-slate-700 mb-1">
                        Tingkat Prioritas <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        id="priority" 
                        wire:model="priority" 
                        class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3 border bg-white"
                    >
                        <option value="low">Low (Kendala ringan, pekerjaan tidak terganggu)</option>
                        <option value="medium">Medium (Kendala operasional normal)</option>
                        <option value="high">High (Menghambat pekerjaan mendesak)</option>
                        <option value="critical">Critical (Sistem mati total, berdampak luas)</option>
                    </select>
                    @error('priority') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700 mb-1">
                    Deskripsi Detail Kendala <span class="text-rose-500">*</span>
                </label>
                <textarea 
                    id="description" 
                    wire:model="description" 
                    rows="5" 
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3 border" 
                    placeholder="Ceritakan kronologi, pesan error yang muncul, atau langkah-langkah yang sudah dicoba..."
                ></textarea>
                @error('description') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Lampiran Berkas -->
            <div>
                <label for="attachment" class="block text-sm font-semibold text-slate-700 mb-1">
                    Lampiran Berkas Pendukung (Opsional)
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-lg hover:border-indigo-400 transition bg-slate-50/50">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-10 w-10 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-slate-600 justify-center">
                            <label for="attachment" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                <span>Pilih file dari perangkat</span>
                                <input id="attachment" wire:model="attachment" type="file" class="sr-only">
                            </label>
                        </div>
                        <p class="text-xs text-slate-500">Mendukung format JPG, PNG, atau PDF (Maksimal 5 MB)</p>
                    </div>
                </div>

                <!-- Preview file yang dipilih -->
                <div wire:loading wire:target="attachment" class="text-xs text-indigo-600 mt-2 font-medium">
                    Sedang mengunggah file...
                </div>

                @if ($attachment)
                    <div class="mt-2 flex items-center justify-between p-2.5 bg-indigo-50 border border-indigo-200 rounded-lg text-xs text-indigo-900">
                        <span class="truncate font-mono">{{ $attachment->getClientOriginalName() }}</span>
                        <button type="button" wire:click="$set('attachment', null)" class="text-rose-600 hover:text-rose-800 font-semibold cursor-pointer">
                            Hapus
                        </button>
                    </div>
                @endif

                @error('attachment') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('tickets.index') }}" class="px-5 py-2.5 border border-slate-300 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-50 transition cursor-pointer">
                    Batal
                </a>
                <button 
                    type="submit" 
                    wire:loading.attr="disabled"
                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold shadow-sm transition flex items-center space-x-2 cursor-pointer disabled:opacity-50"
                >
                    <span wire:loading.remove>Kirim Tiket Kendala</span>
                    <span wire:loading class="flex items-center space-x-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span>Menyimpan Tiket...</span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
