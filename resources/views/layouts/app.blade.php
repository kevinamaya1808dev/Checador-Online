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

    <!-- Contenedor fijo para las notificaciones flotantes -->
<div id="toast-container" class="fixed top-5 right-5 z-[100] flex flex-col gap-3 pointer-events-none"></div>

<!-- Lógica de intercepción de sesiones de Laravel -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        @if(session('success'))
            mostrarToast('success', '¡Operación Exitosa!', '{{ session('success') }}');
        @endif

        @if(session('error'))
            mostrarToast('error', 'Error en el proceso', '{{ session('error') }}');
        @endif

        @if(session('warning'))
            mostrarToast('warning', 'Atención', '{{ session('warning') }}');
        @endif
        
        // Atrapa errores generales de validación del backend y los muestra como toast
        @if($errors->any())
            mostrarToast('error', 'Error de Validación', 'Por favor, revisa los datos ingresados e inténtalo de nuevo.');
        @endif
    });
</script>
</body>
</html>
<script>
window.mostrarToast = function(tipo, titulo, mensaje) {
    const contenedor = document.getElementById('toast-container');
    
    // Configuración de estilos y colores según el tipo de alerta
    const config = {
        success: {
            icono: 'bi-check-circle-fill',
            colorIcono: 'text-green-500 dark:text-green-400',
            bgIcono: 'bg-green-100 dark:bg-green-500/20',
            borde: 'border-green-200 dark:border-green-500/20',
            fondo: 'bg-white dark:bg-[#1a1d23]'
        },
        error: {
            icono: 'bi-x-circle-fill',
            colorIcono: 'text-red-500 dark:text-red-400',
            bgIcono: 'bg-red-100 dark:bg-red-500/20',
            borde: 'border-red-200 dark:border-red-500/20',
            fondo: 'bg-white dark:bg-[#1a1d23]'
        },
        warning: {
            icono: 'bi-exclamation-triangle-fill',
            colorIcono: 'text-yellow-500 dark:text-yellow-400',
            bgIcono: 'bg-yellow-100 dark:bg-yellow-500/20',
            borde: 'border-yellow-200 dark:border-yellow-500/20',
            fondo: 'bg-white dark:bg-[#1a1d23]'
        },
        info: {
            icono: 'bi-info-circle-fill',
            colorIcono: 'text-blue-500 dark:text-blue-400',
            bgIcono: 'bg-blue-100 dark:bg-blue-500/20',
            borde: 'border-blue-200 dark:border-blue-500/20',
            fondo: 'bg-white dark:bg-[#1a1d23]'
        }
    };

    const estilo = config[tipo] || config.info;

    // Crear el elemento DOM del Toast
    const toast = document.createElement('div');
    // Clases iniciales: opacidad 0 y desplazado hacia la derecha para la animación de entrada
    toast.className = `flex items-start gap-3 w-80 p-4 border ${estilo.borde} ${estilo.fondo} rounded-xl shadow-lg dark:shadow-[0_10px_40px_-10px_rgba(0,0,0,0.5)] transform translate-x-full opacity-0 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] pointer-events-auto`;

    toast.innerHTML = `
        <div class="flex items-center justify-center shrink-0 w-8 h-8 rounded-lg ${estilo.bgIcono} ${estilo.colorIcono}">
            <i class="bi ${estilo.icono} text-lg"></i>
        </div>
        <div class="flex-1 pt-0.5">
            <h6 class="text-sm font-bold text-gray-900 dark:text-white mb-0.5">${titulo}</h6>
            <p class="text-xs text-gray-500 dark:text-gray-400 m-0">${mensaje}</p>
        </div>
        <button type="button" class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors p-1" onclick="cerrarToast(this.parentElement)">
            <i class="bi bi-x-lg"></i>
        </button>
    `;

    contenedor.appendChild(toast);

    // Activar animación de entrada (quitar translate-x-full y poner opacity-100)
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
    }, 10);

    // Auto-destrucción después de 5 segundos
    setTimeout(() => {
        cerrarToast(toast);
    }, 5000);
};

// Función para animar la salida y eliminar el elemento del DOM
window.cerrarToast = function(elemento) {
    // Animación de salida (deslizar hacia la derecha y desvanecer)
    elemento.classList.remove('translate-x-0', 'opacity-100');
    elemento.classList.add('translate-x-full', 'opacity-0');
    
    // Esperar a que termine la animación para eliminar el nodo
    setTimeout(() => {
        if(elemento.parentNode) {
            elemento.remove();
        }
    }, 300);
};
</script>
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

