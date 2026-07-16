<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Checador') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>[x-cloak] { display: none !important; }</style>
</head>

<body x-data="{ 
    isDark: localStorage.theme === 'dark' || (!('theme' in localStorage && window.matchMedia('(prefers-color-scheme: dark)').matches)),
    toggleTheme() {
        this.isDark = !this.isDark;
        if (this.isDark) { document.documentElement.classList.add('dark'); localStorage.theme = 'dark'; }
        else { document.documentElement.classList.remove('dark'); localStorage.theme = 'light'; }
    }
}" 
x-init="if (isDark) { document.documentElement.classList.add('dark'); } else { document.documentElement.classList.remove('dark'); }"
class="bg-[#F9F6EE] dark:bg-slate-950 text-gray-800 dark:text-gray-300 [font-family:'Segoe_UI',sans-serif] transition-colors duration-300">

    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        
        <div class="absolute -top-[30%] -left-[10%] w-[120%] h-[70vh] 
                    bg-gradient-to-b from-white via-white/50 to-transparent 
                    dark:from-blue-800/20 dark:via-blue-900/5 
                    rounded-[100%] blur-[60px] transform -rotate-12 transition-colors duration-700">
        </div>

        <div class="absolute bottom-[-20%] -right-[10%] w-[130%] h-[80vh] 
                    bg-gradient-to-t from-[#EAE4D8] via-[#F4F0E6]/50 to-transparent 
                    dark:from-purple-900/20 dark:via-slate-900/5 
                    rounded-[100%] blur-[80px] transform rotate-12 transition-colors duration-700">
        </div>
        
    </div>

    <button @click="toggleTheme()" 
            class="fixed top-4 right-4 z-[1070] p-2 rounded-lg bg-[#EAE4D8] dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-[#DFD8C8] dark:hover:bg-gray-700 transition-all shadow-sm">
        <i class="bi text-lg" :class="isDark ? 'bi-sun-fill' : 'bi-moon-fill'"></i>
    </button>

    <div id="app" class="flex min-h-screen">
        @auth
            @if(auth()->user()->role === 'admin')
                @include('layouts.sidebar')
            @endif
        @endauth

        <main class="flex-grow min-w-0 {{ auth()->check() && auth()->user()->role === 'admin' ? 'p-[1.5rem] md:ml-[260px] pt-[4.5rem] md:pt-[1.5rem]' : 'p-0' }}">
            @yield('content')
        </main>
    </div>
</body>
</html>