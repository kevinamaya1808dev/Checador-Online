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

   <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none bg-white dark:bg-[#05060a] transition-colors duration-700">
    <canvas id="aurora-stars" class="absolute inset-0 w-full h-full"></canvas>
    <div class="aurora-band aurora-purple"></div>
    <div class="aurora-band aurora-cyan"></div>
    <div class="aurora-band aurora-green"></div>
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

<style>
    .aurora-band {
    position: absolute;
    left: -20%;
    width: 140%;
    height: 60vh;
    filter: blur(70px);
    border-radius: 50%;
    will-change: transform, opacity;
    transition: opacity 0.7s ease;
}

/* ---- TEMA CLARO (por defecto) ---- */
.aurora-band {
    opacity: 0.22;
    mix-blend-mode: multiply;
}

.aurora-purple {
    top: -10%;
    background: radial-gradient(ellipse at center, #c084fc 0%, transparent 70%);
    animation: aurora-drift-1 22s ease-in-out infinite alternate;
}

.aurora-cyan {
    top: 15%;
    background: radial-gradient(ellipse at center, #67e8f9 0%, transparent 70%);
    animation: aurora-drift-2 28s ease-in-out infinite alternate;
}

.aurora-green {
    top: 35%;
    background: radial-gradient(ellipse at center, #6ee7b7 0%, transparent 70%);
    animation: aurora-drift-3 34s ease-in-out infinite alternate;
}

/* ---- TEMA OSCURO ---- */
.dark .aurora-band {
    opacity: 0.35;
    mix-blend-mode: screen;
}

.dark .aurora-purple {
    background: radial-gradient(ellipse at center, #a855f7 0%, transparent 70%);
}

.dark .aurora-cyan {
    background: radial-gradient(ellipse at center, #22d3ee 0%, transparent 70%);
}

.dark .aurora-green {
    background: radial-gradient(ellipse at center, #34d399 0%, transparent 70%);
}

@keyframes aurora-drift-1 {
    0%   { transform: translateX(-5%) translateY(0) rotate(-6deg) scaleY(1); }
    50%  { transform: translateX(8%) translateY(4%) rotate(3deg) scaleY(1.15); }
    100% { transform: translateX(-8%) translateY(-3%) rotate(-3deg) scaleY(1); }
}

@keyframes aurora-drift-2 {
    0%   { transform: translateX(6%) translateY(2%) rotate(4deg) scaleY(1); }
    50%  { transform: translateX(-10%) translateY(-4%) rotate(-5deg) scaleY(1.2); }
    100% { transform: translateX(5%) translateY(3%) rotate(2deg) scaleY(1); }
}

@keyframes aurora-drift-3 {
    0%   { transform: translateX(-8%) translateY(-2%) rotate(-4deg) scaleY(1); }
    50%  { transform: translateX(10%) translateY(5%) rotate(6deg) scaleY(1.1); }
    100% { transform: translateX(-6%) translateY(2%) rotate(-2deg) scaleY(1); }
}
</style>