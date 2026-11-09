<div class="max-w-md mx-auto my-12">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-indigo-700 px-6 py-6 text-center text-white">
            <h1 class="text-2xl font-bold">Masuk ke Helpdesk</h1>
            <p class="text-indigo-200 text-sm mt-1">Sistem Pengelolaan Kendala IT Perusahaan</p>
        </div>

        <form wire:submit="login" class="p-6 space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Perusahaan</label>
                <input 
                    type="email" 
                    id="email" 
                    wire:model="email" 
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3 border" 
                    placeholder="nama@helpdesk.test"
                    autofocus
                >
                @error('email') 
                    <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> 
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Kata Sandi</label>
                <input 
                    type="password" 
                    id="password" 
                    wire:model="password" 
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3 border" 
                    placeholder="••••••••"
                >
                @error('password') 
                    <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> 
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center text-sm text-slate-600 cursor-pointer">
                    <input type="checkbox" wire:model="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                    <span class="ml-2">Ingat saya</span>
                </label>
            </div>

            <button 
                type="submit" 
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-4 rounded-lg shadow-sm transition text-sm cursor-pointer flex items-center justify-center space-x-2"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>Masuk</span>
                <span wire:loading class="flex items-center space-x-2">
                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Memverifikasi...</span>
                </span>
            </button>
        </form>

        <div class="bg-slate-50 border-t border-slate-200 p-4 text-xs text-slate-600">
            <div class="font-semibold text-slate-700 mb-1">Akun Uji Coba Tersedia:</div>
            <ul class="space-y-1 font-mono text-[11px]">
                <li>• Admin: <span class="text-indigo-600">admin@helpdesk.test</span> (pass: password)</li>
                <li>• IT Support: <span class="text-indigo-600">support@helpdesk.test</span> (pass: password)</li>
                <li>• Employee: <span class="text-indigo-600">employee@helpdesk.test</span> (pass: password)</li>
            </ul>
        </div>
    </div>
</div>
