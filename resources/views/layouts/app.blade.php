<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">

    {{-- Script bloqueante: decide el tema ANTES de que el navegador pinte nada.
         Debe ir aquí, sin defer/async, para evitar el flash de tema incorrecto. --}}
    <script>
        (function () {
            var guardado = localStorage.getItem('theme');
            var prefiereOscuro = window.matchMedia('(prefers-color-scheme: dark)').matches;
            var esOscuro = guardado ? guardado === 'dark' : prefiereOscuro;
            if (esOscuro) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

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
        class="theme-toggle fixed top-4 right-4 z-[1070]"
        :class="isDark ? 'theme-toggle--dark' : 'theme-toggle--light'"
        aria-label="Cambiar tema">
    <span class="theme-toggle__track">
        <span class="theme-toggle__icon theme-toggle__icon--sun">
            <i class="bi bi-sun-fill"></i>
        </span>
        <span class="theme-toggle__icon theme-toggle__icon--moon">
            <i class="bi bi-moon-stars-fill"></i>
        </span>
        <span class="theme-toggle__thumb"></span>
    </span>
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

.theme-toggle {
    -webkit-appearance: none;
    appearance: none;
    background: transparent;
    border: none;
    padding: 0;
    cursor: pointer;
}

.theme-toggle__track {
    position: relative;
    display: flex;
    align-items: center;
    width: 68px;
    height: 34px;
    border-radius: 999px;
    padding: 3px;
    background: rgba(234, 228, 216, 0.9);
    border: 1px solid rgba(0, 0, 0, 0.06);
    box-shadow:
        inset 0 1px 3px rgba(0, 0, 0, 0.08),
        0 4px 12px rgba(0, 0, 0, 0.06);
    backdrop-filter: blur(10px);
    transition: background 0.4s ease, border-color 0.4s ease, box-shadow 0.4s ease;
}

.theme-toggle--dark .theme-toggle__track {
    background: rgba(15, 23, 42, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.08);
    box-shadow:
        inset 0 1px 3px rgba(0, 0, 0, 0.4),
        0 4px 16px rgba(0, 0, 0, 0.35),
        0 0 0 1px rgba(59, 130, 246, 0.08);
}

.theme-toggle__thumb {
    position: absolute;
    top: 3px;
    left: 3px;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: linear-gradient(145deg, #ffffff, #f0ece0);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    transform: translateX(0);
    transition: transform 0.4s cubic-bezier(0.68, -0.4, 0.32, 1.4), background 0.4s ease;
}

.theme-toggle--dark .theme-toggle__thumb {
    transform: translateX(34px);
    background: linear-gradient(145deg, #3b4256, #1e2536);
    box-shadow:
        0 2px 8px rgba(0, 0, 0, 0.5),
        0 0 12px rgba(59, 130, 246, 0.35);
}

.theme-toggle__icon {
    position: relative;
    z-index: 1;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    transition: color 0.4s ease, opacity 0.3s ease, transform 0.4s ease;
}

.theme-toggle__icon--sun {
    color: #d97706;
    opacity: 1;
    transform: scale(1) rotate(0deg);
}

.theme-toggle__icon--moon {
    color: #64748b;
    opacity: 0.4;
    transform: scale(0.85) rotate(-20deg);
}

.theme-toggle--dark .theme-toggle__icon--sun {
    opacity: 0.35;
    transform: scale(0.85) rotate(20deg);
}

.theme-toggle--dark .theme-toggle__icon--moon {
    color: #93c5fd;
    opacity: 1;
    transform: scale(1) rotate(0deg);
}

.theme-toggle:hover .theme-toggle__track {
    box-shadow:
        inset 0 1px 3px rgba(0, 0, 0, 0.08),
        0 6px 16px rgba(0, 0, 0, 0.1);
}

.theme-toggle--dark:hover .theme-toggle__track {
    box-shadow:
        inset 0 1px 3px rgba(0, 0, 0, 0.4),
        0 6px 20px rgba(0, 0, 0, 0.4),
        0 0 0 1px rgba(59, 130, 246, 0.15);
}

.theme-toggle:active .theme-toggle__thumb {
    transform: translateX(0) scale(0.92);
}

.theme-toggle--dark:active .theme-toggle__thumb {
    transform: translateX(34px) scale(0.92);
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