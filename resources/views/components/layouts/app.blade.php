<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'IT Helpdesk System' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full flex flex-col font-sans text-slate-800 antialiased">
    <header class="bg-indigo-700 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 font-bold text-xl tracking-tight text-white hover:text-indigo-100">
                        <svg class="w-7 h-7 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>Helpdesk<span class="text-indigo-200">IT</span></span>
                    </a>
                    @auth
                    <nav class="hidden md:flex space-x-2">
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('tickets.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('tickets.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600' }}">
                            Daftar Tiket
                        </a>
                        @if (auth()->user()->hasRole('administrator'))
                            <a href="{{ route('categories.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('categories.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600' }}">
                                Kategori
                            </a>
                            <a href="{{ route('reports.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('reports.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600' }}">
                                Laporan
                            </a>
                        @endif
                    </nav>
                    @endauth
                </div>

                @auth
                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-medium leading-none">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-indigo-200 mt-1 capitalize">
                            Role: <span class="font-semibold">{{ auth()->user()->getRoleNames()->first() ?? 'User' }}</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-indigo-800 hover:bg-indigo-900 text-white text-xs px-3 py-2 rounded border border-indigo-500 font-medium transition cursor-pointer">
                            Logout
                        </button>
                    </form>
                </div>
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-1 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session()->has('status'))
                <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
                    <span>{{ session('status') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold">&times;</button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-6 p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 font-bold">&times;</button>
                </div>
            @endif

            {{ $slot }}
        </div>
    </main>

    <footer class="bg-white border-t border-slate-200 py-4 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} IT Helpdesk Management System — Versi 1.0
        </div>
    </footer>

    @livewireScripts
</body>
</html>
