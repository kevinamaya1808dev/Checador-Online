@extends('layouts.app')

@section('content')

@php
    // $estado esperado desde el controlador: 'inactivo' | 'trabajando' | 'pausado' | 'terminado'
    $estadoRaw = $estado ?? null;
    $estado    = $estadoRaw ?? 'inactivo';
    $gating    = ! is_null($estadoRaw); // solo bloqueamos botones cuando el backend ya manda el estado real

    $estadoInfo = [
        'inactivo'   => ['label' => 'Esperando turno',  'desc' => 'Aún no has registrado tu entrada.',   'texto' => 'text-slate-300',  'icon' => 'bi-hourglass-split'],
        'trabajando' => ['label' => 'Trabajando',        'desc' => 'Tu turno está activo.',                'texto' => 'text-green-400',  'icon' => 'bi-briefcase-fill'],
        'pausado'    => ['label' => 'En pausa',          'desc' => 'Descanso en curso.',                   'texto' => 'text-amber-400',  'icon' => 'bi-cup-hot'],
        'terminado'  => ['label' => 'Turno finalizado',  'desc' => 'Registro guardado correctamente.',     'texto' => 'text-red-400',    'icon' => 'bi-flag-fill'],
    ][$estado] ?? ['label' => ucfirst($estado), 'desc' => '', 'texto' => 'text-slate-300', 'icon' => 'bi-question-circle'];

    // Estilos del banner de estado — clases Tailwind completas y literales para que el JIT las detecte.
    $estadoBanner = [
        'inactivo'   => [
            'wash'    => 'from-slate-500/10',
            'border'  => 'border-slate-500/20',
            'iconBg'  => 'bg-gradient-to-br from-slate-500 to-slate-700',
            'iconSh'  => 'shadow-[0_8px_20px_-4px_rgba(100,116,139,0.5)]',
            'dot'     => 'bg-slate-400',
            'pulse'   => false,
        ],
        'trabajando' => [
            'wash'    => 'from-green-500/10',
            'border'  => 'border-green-500/25',
            'iconBg'  => 'bg-gradient-to-br from-green-500 to-green-700',
            'iconSh'  => 'shadow-[0_8px_20px_-4px_rgba(34,197,94,0.55)]',
            'dot'     => 'bg-green-400',
            'pulse'   => true,
        ],
        'pausado'    => [
            'wash'    => 'from-amber-500/10',
            'border'  => 'border-amber-500/25',
            'iconBg'  => 'bg-gradient-to-br from-amber-500 to-amber-700',
            'iconSh'  => 'shadow-[0_8px_20px_-4px_rgba(217,119,6,0.55)]',
            'dot'     => 'bg-amber-400',
            'pulse'   => true,
        ],
        'terminado'  => [
            'wash'    => 'from-red-500/10',
            'border'  => 'border-red-500/25',
            'iconBg'  => 'bg-gradient-to-br from-red-500 to-red-700',
            'iconSh'  => 'shadow-[0_8px_20px_-4px_rgba(220,38,38,0.5)]',
            'dot'     => 'bg-red-400',
            'pulse'   => false,
        ],
    ][$estado] ?? [
        'wash' => 'from-slate-500/10', 'border' => 'border-slate-500/20',
        'iconBg' => 'bg-gradient-to-br from-slate-500 to-slate-700',
        'iconSh' => 'shadow-[0_8px_20px_-4px_rgba(100,116,139,0.5)]',
        'dot' => 'bg-slate-400', 'pulse' => false,
    ];

    $puedeEntrada  = ! $gating || $estado === 'inactivo';
    $puedePausar   = ! $gating || $estado === 'trabajando';
    $puedeReanudar = ! $gating || $estado === 'pausado';
    $puedeSalir    = ! $gating || $estado === 'trabajando';

    // "Destacar" = es la acción que realmente corresponde hacer ahora mismo.
    $destacarEntrada  = $gating && $puedeEntrada;
    $destacarPausar   = $gating && $puedePausar;
    $destacarReanudar = $gating && $puedeReanudar;
    $destacarSalir    = $gating && $puedeSalir;

    $inicial = auth()->check() ? mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) : 'U';
@endphp

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .entrada {
        opacity: 0;
        animation: fadeInUp .6s cubic-bezier(.16,.84,.44,1) forwards;
    }

    @keyframes anilloPulso {
        0%   { box-shadow: 0 0 0 0 rgba(255,255,255,.18); }
        70%  { box-shadow: 0 0 0 10px rgba(255,255,255,0); }
        100% { box-shadow: 0 0 0 0 rgba(255,255,255,0); }
    }
    .anillo-vivo { animation: anilloPulso 2.2s ease-out infinite; }
</style>

{{-- NOTIFICACIONES: aparecen a un costado y se autodestruyen a los 3s (JS plano, sin Bootstrap) --}}
<div class="fixed top-0 right-0 p-3 z-[1090] flex flex-col gap-2">
    @if (session('success'))
        <div data-toast class="text-white bg-slate-900/90 backdrop-blur-[15px] border border-white/[0.08] rounded-2xl overflow-hidden min-w-[280px] shadow-2xl" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="flex items-center">
                <div class="flex items-center gap-2 p-3">
                    <i class="bi bi-check-circle-fill text-green-500 text-xl"></i>
                    {{ session('success') }}
                </div>
                <button type="button" data-toast-close class="ml-auto mr-3 text-white/70 hover:text-white transition-colors" aria-label="Cerrar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    @endif
    @if (session('error'))
        <div data-toast class="text-white bg-slate-900/90 backdrop-blur-[15px] border border-white/[0.08] rounded-2xl overflow-hidden min-w-[280px] shadow-2xl" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="flex items-center">
                <div class="flex items-center gap-2 p-3">
                    <i class="bi bi-exclamation-triangle-fill text-red-500 text-xl"></i>
                    {{ session('error') }}
                </div>
                <button type="button" data-toast-close class="ml-auto mr-3 text-white/70 hover:text-white transition-colors" aria-label="Cerrar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    @endif
</div>

<div class="relative min-h-screen bg-slate-950 text-white overflow-hidden">

    {{-- Ambiente decorativo de fondo: manchas de color muy sutiles, dan profundidad sin distraer --}}
    <div class="pointer-events-none absolute inset-0 -z-0 overflow-hidden">
        <div class="absolute -top-24 -left-24 w-[420px] h-[420px] rounded-full bg-blue-600/10 blur-[110px]"></div>
        <div class="absolute top-1/3 -right-32 w-[420px] h-[420px] rounded-full bg-amber-500/10 blur-[120px]"></div>
        <div class="absolute bottom-0 left-1/4 w-[360px] h-[360px] rounded-full bg-red-600/5 blur-[110px]"></div>
    </div>

    <div class="relative max-w-[1320px] mx-auto py-5 px-3 md:px-4">

        {{-- HEADER --}}
        <div class="entrada bg-slate-900/85 backdrop-blur-[15px] border border-white/[0.08] rounded-3xl flex justify-between items-center px-4 sm:px-5 py-3.5 mb-5 shadow-lg">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl flex items-center justify-center bg-gradient-to-br from-blue-500 to-blue-700 shadow-[0_8px_20px_-4px_rgba(37,99,235,0.6)]">
                    <i class="bi bi-clock-history text-lg text-white"></i>
                </span>
                <div>
                    <h1 class="text-lg font-bold uppercase mb-0 tracking-[2px] leading-none">
                        OLLIN<span class="text-blue-500">CHECK</span>
                    </h1>
                    <p class="text-[0.7rem] text-slate-400 mt-0.5 flex items-center gap-1.5">
                        <span class="relative flex h-1.5 w-1.5">
                            <span class="{{ $estadoBanner['pulse'] ? 'animate-ping' : '' }} absolute inline-flex h-full w-full rounded-full {{ $estadoBanner['dot'] }} opacity-60"></span>
                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 {{ $estadoBanner['dot'] }}"></span>
                        </span>
                        {{ $estadoInfo['label'] }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden sm:flex items-center gap-2.5 text-sm font-semibold">
                    <span class="w-8 h-8 rounded-full bg-blue-500/15 border border-blue-400/30 text-blue-300 text-xs font-bold flex items-center justify-center">
                        {{ $inicial }}
                    </span>
                    {{ auth()->check() ? auth()->user()->name : 'Usuario' }}
                </span>
                <form action="{{ route('logout') }}" method="POST" class="mb-0">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 text-sm border border-red-500/60 text-red-400 hover:bg-red-500 hover:text-white hover:border-red-500 rounded-full px-3.5 py-1.5 transition-colors duration-200">
                        <i class="bi bi-power"></i> Salir
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

            {{-- IZQUIERDA: RELOJ + ESTADO --}}
            <div class="lg:col-span-7">
                <div class="entrada bg-slate-900/85 backdrop-blur-[15px] border border-white/[0.08] rounded-3xl p-5 sm:p-6 h-full shadow-lg" style="animation-delay:.08s">

                    <p class="uppercase text-slate-500 font-bold tracking-[2px] text-[0.7rem] mb-1">Tu turno en tiempo real</p>

                    <div class="relative w-[250px] h-[250px] mx-auto mt-3">
                        <div class="absolute inset-0 rounded-full bg-blue-500/20 blur-3xl scale-90"></div>

                        <div class="relative w-full h-full rounded-full border-8 border-[#001f3f] bg-gradient-to-b from-[#00172e] to-[#000d1a] shadow-[inset_0_0_30px_#000,0_0_40px_rgba(0,31,63,0.9)]">
                            <span class="absolute font-mono text-xs font-bold text-blue-400/60 top-3 left-1/2 -translate-x-1/2">12</span>
                            <span class="absolute font-mono text-xs font-bold text-blue-400/60 bottom-3 left-1/2 -translate-x-1/2">6</span>
                            <span class="absolute font-mono text-xs font-bold text-blue-400/60 left-3 top-1/2 -translate-y-1/2">9</span>
                            <span class="absolute font-mono text-xs font-bold text-blue-400/60 right-3 top-1/2 -translate-y-1/2">3</span>

                            <div class="absolute top-1/2 left-1/2 w-3.5 h-3.5 -mt-[7px] -ml-[7px] bg-blue-500 rounded-full z-[6] shadow-[0_0_15px_#3b82f6]"></div>

                            <div id="reloj-hora" class="absolute bottom-1/2 left-1/2 origin-bottom rounded w-1.5 h-[58px] -ml-[3px] bg-gradient-to-b from-blue-600 to-blue-300 z-[2]"></div>
                            <div id="reloj-min" class="absolute bottom-1/2 left-1/2 origin-bottom rounded w-1 h-[85px] -ml-[2px] bg-gradient-to-b from-cyan-400 to-white z-[3]"></div>
                            <div id="reloj-seg" class="absolute bottom-1/2 left-1/2 origin-bottom rounded-full w-[2px] h-[95px] -ml-px bg-red-400/80 z-[4]"></div>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <div class="text-4xl sm:text-5xl font-bold font-mono tracking-tight bg-gradient-to-b from-white to-slate-300 bg-clip-text text-transparent" id="reloj-digital">--:--:--</div>
                        <p class="inline-block text-slate-400 text-xs mt-1.5 mb-0 capitalize border border-white/10 rounded-full px-3 py-1" id="reloj-fecha"></p>
                    </div>

                    {{-- Banner de estado --}}
                    <div class="relative overflow-hidden rounded-2xl border {{ $estadoBanner['border'] }} bg-gradient-to-r {{ $estadoBanner['wash'] }} to-transparent mt-5 p-4 sm:p-5 flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <span class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center text-xl sm:text-2xl text-white {{ $estadoBanner['iconBg'] }} {{ $estadoBanner['iconSh'] }} {{ $estadoBanner['pulse'] ? 'anillo-vivo' : '' }} shrink-0">
                                <i class="bi {{ $estadoInfo['icon'] }}"></i>
                            </span>
                            <div>
                                <p class="uppercase text-slate-400 mb-1 tracking-[1px] text-[0.65rem]">Estado actual</p>
                                <p class="font-bold text-lg sm:text-xl mb-0 leading-none {{ $estadoInfo['texto'] }}">{{ $estadoInfo['label'] }}</p>
                                <p class="text-slate-400 text-sm mb-0 mt-1">{{ $estadoInfo['desc'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3">
                        <div class="group relative overflow-hidden bg-slate-800/50 border border-white/[0.06] rounded-2xl p-4 flex items-center gap-3 transition-all duration-300 hover:border-green-500/30 hover:-translate-y-0.5">
                            <span class="absolute left-0 top-0 h-full w-1 bg-green-500/70"></span>
                            <span class="w-11 h-11 rounded-xl flex items-center justify-center text-lg text-white bg-gradient-to-br from-green-500 to-green-700 shadow-[0_8px_18px_-4px_rgba(34,197,94,0.5)] shrink-0">
                                <i class="bi bi-clock"></i>
                            </span>
                            <div class="min-w-0">
                                <p class="uppercase text-slate-400 mb-0 text-[0.65rem] tracking-[1px]">Tiempo trabajado</p>
                                <p class="font-bold font-mono text-xl mb-0 text-white" id="tiempoTrabajado">00:00:00</p>
                            </div>
                        </div>
                        <div class="group relative overflow-hidden bg-slate-800/50 border border-white/[0.06] rounded-2xl p-4 flex items-center gap-3 transition-all duration-300 hover:border-amber-500/30 hover:-translate-y-0.5">
                            <span class="absolute left-0 top-0 h-full w-1 bg-amber-500/70"></span>
                            <span class="w-11 h-11 rounded-xl flex items-center justify-center text-lg text-white bg-gradient-to-br from-amber-500 to-amber-700 shadow-[0_8px_18px_-4px_rgba(217,119,6,0.5)] shrink-0">
                                <i class="bi bi-cup-hot"></i>
                            </span>
                            <div class="min-w-0">
                                <p class="uppercase text-slate-400 mb-0 text-[0.65rem] tracking-[1px]">Tiempo en pausa</p>
                                <p class="font-bold font-mono text-xl mb-0 text-white" id="tiempoPausa">00:00:00</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- DERECHA: ACCIONES --}}
            <div class="lg:col-span-5">
                <div class="entrada bg-slate-900/85 backdrop-blur-[15px] border border-white/[0.08] rounded-3xl p-5 sm:p-6 h-full flex flex-col shadow-lg" style="animation-delay:.16s">

                    <div class="flex items-center justify-between mb-4">
                        <p class="uppercase text-slate-500 font-bold tracking-[2px] text-[0.7rem] mb-0">Acciones</p>
                        <span class="h-px flex-1 ml-3 bg-gradient-to-r from-white/10 to-transparent"></span>
                    </div>

                    <div class="flex flex-col gap-3.5">

                        {{-- Registrar entrada --}}
                        <form action="{{ route('becario.checar') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit"
                                    class="group relative isolate w-full text-left overflow-hidden rounded-2xl border p-4 sm:p-5 flex items-center gap-4
                                           bg-slate-900/60 border-blue-500/40
                                           transition-all duration-300 ease-out
                                           hover:-translate-y-1 hover:border-blue-400/70 hover:shadow-[0_18px_35px_-15px_rgba(37,99,235,0.55)]
                                           active:translate-y-0 active:scale-[0.98]
                                           disabled:opacity-30 disabled:cursor-not-allowed disabled:pointer-events-none disabled:hover:translate-y-0 disabled:hover:shadow-none
                                           {{ $destacarEntrada ? 'ring-1 ring-blue-400/40 shadow-[0_0_30px_-8px_rgba(59,130,246,0.5)]' : '' }}"
                                    @disabled(!$puedeEntrada)>

                                <span class="absolute left-0 top-0 h-full w-1 bg-blue-500 origin-center scale-y-0 group-hover:scale-y-100 transition-transform duration-300 rounded-r"></span>
                                <span class="pointer-events-none absolute -right-8 -top-8 w-28 h-28 rounded-full bg-blue-500/10 blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></span>

                                @if($destacarEntrada)
                                    <span class="absolute top-3 right-3 flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-blue-300 bg-blue-500/10 border border-blue-400/30 rounded-full px-2 py-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                                        Disponible
                                    </span>
                                @endif

                                <span class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex-shrink-0 flex items-center justify-center text-xl text-white
                                             bg-gradient-to-br from-blue-500 to-blue-700 shadow-[0_8px_20px_-4px_rgba(37,99,235,0.65)]
                                             transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-3">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                </span>

                                <span class="relative flex-1 min-w-0">
                                    <span class="block font-bold text-base sm:text-lg text-blue-300 tracking-tight">Registrar entrada</span>
                                    <span class="block text-sm text-slate-400 mt-0.5">Inicia el registro de tu turno</span>
                                </span>

                                <i class="bi bi-chevron-right relative text-white/0 group-hover:text-white/50 transition-all duration-300 group-hover:translate-x-1"></i>
                            </button>
                        </form>

                        {{-- Gestionar pausa --}}
                        <div>
                            <button type="button"
                                    class="group relative isolate w-full text-left overflow-hidden rounded-2xl border p-4 sm:p-5 flex items-center gap-4
                                           bg-slate-900/60 border-amber-500/40
                                           transition-all duration-300 ease-out
                                           hover:-translate-y-1 hover:border-amber-400/70 hover:shadow-[0_18px_35px_-15px_rgba(217,119,6,0.55)]
                                           active:translate-y-0 active:scale-[0.98]
                                           disabled:opacity-30 disabled:cursor-not-allowed disabled:pointer-events-none disabled:hover:translate-y-0 disabled:hover:shadow-none
                                           {{ $destacarPausar ? 'ring-1 ring-amber-400/40 shadow-[0_0_30px_-8px_rgba(245,158,11,0.5)]' : '' }}"
                                    onclick="togglePausaMenu()"
                                    @disabled(!$puedePausar)>

                                <span class="absolute left-0 top-0 h-full w-1 bg-amber-500 origin-center scale-y-0 group-hover:scale-y-100 transition-transform duration-300 rounded-r"></span>
                                <span class="pointer-events-none absolute -right-8 -top-8 w-28 h-28 rounded-full bg-amber-500/10 blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></span>

                                @if($destacarPausar)
                                    <span class="absolute top-3 right-3 flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-amber-300 bg-amber-500/10 border border-amber-400/30 rounded-full px-2 py-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                        Disponible
                                    </span>
                                @endif

                                <span class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex-shrink-0 flex items-center justify-center text-xl text-white
                                             bg-gradient-to-br from-amber-500 to-amber-700 shadow-[0_8px_20px_-4px_rgba(217,119,6,0.65)]
                                             transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                                    <i class="bi bi-cup-hot"></i>
                                </span>

                                <span class="relative flex-1 min-w-0">
                                    <span class="block font-bold text-base sm:text-lg text-amber-300 tracking-tight">Gestionar pausa</span>
                                    <span class="block text-sm text-slate-400 mt-0.5">Justificación obligatoria</span>
                                </span>

                                <i id="pausaChevron" class="bi bi-chevron-down relative text-white/40 transition-transform duration-300"></i>
                            </button>

                            <div id="pausaMenu" class="max-h-0 opacity-0 overflow-hidden transition-all duration-300 ease-out">
                                <form action="{{ route('becario.iniciarPausa') }}" method="POST"
                                      class="bg-slate-800/50 border border-amber-500/30 rounded-2xl p-3 mt-2">
                                    @csrf
                                    <label class="block text-sm text-amber-400 font-bold uppercase mb-2 text-[0.7rem]">
                                        Motivo de pausa
                                    </label>
                                    <select name="motivo" class="w-full bg-[#0f1724] border border-white/10 text-white text-sm rounded-lg px-3 py-2 mb-3 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/25 focus:outline-none">
                                        <option value="Almuerzo">Almuerzo</option>
                                        <option value="Personal">Personal</option>
                                    </select>
                                    <button type="submit"
                                            class="w-full bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold uppercase text-sm rounded-lg px-4 py-2.5 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                            @disabled(!$puedePausar)>
                                        Iniciar ahora
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Finalizar pausa --}}
                        <form id="formFinalizarPausa" action="{{ route('becario.finalizarPausa') }}" method="POST" class="m-0">
                            @csrf
                            <button type="button"
                                    class="group relative isolate w-full text-left overflow-hidden rounded-2xl border p-4 sm:p-5 flex items-center gap-4
                                           bg-slate-900/60 border-blue-500/40
                                           transition-all duration-300 ease-out
                                           hover:-translate-y-1 hover:border-blue-400/70 hover:shadow-[0_18px_35px_-15px_rgba(37,99,235,0.55)]
                                           active:translate-y-0 active:scale-[0.98]
                                           disabled:opacity-30 disabled:cursor-not-allowed disabled:pointer-events-none disabled:hover:translate-y-0 disabled:hover:shadow-none
                                           {{ $destacarReanudar ? 'ring-1 ring-blue-400/40 shadow-[0_0_30px_-8px_rgba(59,130,246,0.5)]' : '' }}"
                                    onclick="openModal('modalFinalizarPausa')"
                                    @disabled(!$puedeReanudar)>

                                <span class="absolute left-0 top-0 h-full w-1 bg-blue-500 origin-center scale-y-0 group-hover:scale-y-100 transition-transform duration-300 rounded-r"></span>
                                <span class="pointer-events-none absolute -right-8 -top-8 w-28 h-28 rounded-full bg-blue-500/10 blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></span>

                                @if($destacarReanudar)
                                    <span class="absolute top-3 right-3 flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-blue-300 bg-blue-500/10 border border-blue-400/30 rounded-full px-2 py-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                                        Disponible
                                    </span>
                                @endif

                                <span class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex-shrink-0 flex items-center justify-center text-xl text-white
                                             bg-gradient-to-br from-blue-500 to-blue-700 shadow-[0_8px_20px_-4px_rgba(37,99,235,0.65)]
                                             transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-3">
                                    <i class="bi bi-play-fill"></i>
                                </span>

                                <span class="relative flex-1 min-w-0">
                                    <span class="block font-bold text-base sm:text-lg text-blue-300 tracking-tight">Finalizar pausa</span>
                                    <span class="block text-sm text-slate-400 mt-0.5">Reanuda tus actividades</span>
                                </span>

                                <i class="bi bi-chevron-right relative text-white/0 group-hover:text-white/50 transition-all duration-300 group-hover:translate-x-1"></i>
                            </button>
                        </form>

                        {{-- Registrar salida --}}
                        <form id="formSalida" action="{{ route('becario.salida') }}" method="POST" class="m-0">
                            @csrf
                            <button type="button"
                                    class="group relative isolate w-full text-left overflow-hidden rounded-2xl border p-4 sm:p-5 flex items-center gap-4
                                           bg-slate-900/60 border-red-500/40
                                           transition-all duration-300 ease-out
                                           hover:-translate-y-1 hover:border-red-400/70 hover:shadow-[0_18px_35px_-15px_rgba(220,38,38,0.55)]
                                           active:translate-y-0 active:scale-[0.98]
                                           disabled:opacity-30 disabled:cursor-not-allowed disabled:pointer-events-none disabled:hover:translate-y-0 disabled:hover:shadow-none
                                           {{ $destacarSalir ? 'ring-1 ring-red-400/40 shadow-[0_0_30px_-8px_rgba(239,68,68,0.5)]' : '' }}"
                                    onclick="openModal('modalSalida')"
                                    @disabled(!$puedeSalir)>

                                <span class="absolute left-0 top-0 h-full w-1 bg-red-500 origin-center scale-y-0 group-hover:scale-y-100 transition-transform duration-300 rounded-r"></span>
                                <span class="pointer-events-none absolute -right-8 -top-8 w-28 h-28 rounded-full bg-red-500/10 blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></span>

                                @if($destacarSalir)
                                    <span class="absolute top-3 right-3 flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-red-300 bg-red-500/10 border border-red-400/30 rounded-full px-2 py-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-400 animate-pulse"></span>
                                        Disponible
                                    </span>
                                @endif

                                <span class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex-shrink-0 flex items-center justify-center text-xl text-white
                                             bg-gradient-to-br from-red-500 to-red-700 shadow-[0_8px_20px_-4px_rgba(185,28,28,0.65)]
                                             transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                                    <i class="bi bi-box-arrow-left"></i>
                                </span>

                                <span class="relative flex-1 min-w-0">
                                    <span class="block font-bold text-base sm:text-lg text-red-300 tracking-tight">Registrar salida</span>
                                    <span class="block text-sm text-slate-400 mt-0.5">Finaliza tu turno</span>
                                </span>

                                <i class="bi bi-chevron-right relative text-white/0 group-hover:text-white/50 transition-all duration-300 group-hover:translate-x-1"></i>
                            </button>
                        </form>

                    </div>

                    <div class="bg-slate-800/50 border border-white/[0.06] rounded-2xl mt-auto p-4 flex gap-3 items-start">
                        <span class="w-8 h-8 rounded-lg bg-blue-500/15 border border-blue-400/25 flex items-center justify-center shrink-0">
                            <i class="bi bi-info-circle-fill text-blue-400 text-sm"></i>
                        </span>
                        <div>
                            <p class="font-bold text-sm mb-0">Recuerda registrar tus pausas</p>
                            <p class="text-slate-400 mb-0 text-[0.75rem]">Para llevar un control correcto de tu tiempo laboral.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Modals --}}
@include('becario.modals.confirmar_descanso')
@include('becario.modals.finalizar_turno')

<script>
    // Datos reales del backend para calcular los tiempos en vivo.
    window.checadorConfig = {
        estado: @json($estado),
        horaEntrada: @json($horaEntrada ?? null),
        pausaInicio: @json($pausaInicio ?? null),
        horaSalida: @json($horaSalida ?? null),
        segundosPausaAcumulados: {{ (int) ($segundosPausaAcumulados ?? 0) }}
    };

    // --- Helpers genéricos de modal ---
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        const dialog = modal.querySelector('.modal-dialog');

        modal.classList.remove('opacity-0', 'pointer-events-none');

        setTimeout(() => {
            if (dialog) {
                dialog.classList.remove('scale-95');
                dialog.classList.add('scale-100');
            }
        }, 10);
    };

    window.closeModal = function(modalId) {
        const modal = typeof modalId === 'string' ? document.getElementById(modalId) : modalId;
        if (!modal) return;

        const dialog = modal.querySelector('.modal-dialog');

        if (dialog) {
            dialog.classList.remove('scale-100');
            dialog.classList.add('scale-95');
        }

        modal.classList.add('opacity-0');

        setTimeout(() => {
            modal.classList.add('pointer-events-none');
        }, 300);
    };

    // --- Collapse animado del menú de pausa (JS plano, con altura + opacidad) ---
    window.togglePausaMenu = function() {
        const el = document.getElementById('pausaMenu');
        const chevron = document.getElementById('pausaChevron');
        if (!el) return;

        const cerrado = el.classList.contains('max-h-0');

        if (cerrado) {
            el.classList.remove('max-h-0', 'opacity-0');
            el.classList.add('max-h-[320px]', 'opacity-100');
            if (chevron) chevron.classList.add('rotate-180');
        } else {
            el.classList.remove('max-h-[320px]', 'opacity-100');
            el.classList.add('max-h-0', 'opacity-0');
            if (chevron) chevron.classList.remove('rotate-180');
        }
    };

    document.addEventListener('DOMContentLoaded', () => {

        // --- Entrada escalonada de las tarjetas principales ---
        document.querySelectorAll('.entrada').forEach((el, i) => {
            if (!el.style.animationDelay) {
                el.style.animationDelay = `${i * 0.08}s`;
            }
        });

        // --- Notificaciones: se muestran solas y desaparecen a los 3s ---
        document.querySelectorAll('[data-toast]').forEach((toastEl) => {
            const cerrarToast = () => {
                toastEl.style.transition = 'opacity 0.3s ease';
                toastEl.style.opacity = '0';
                setTimeout(() => toastEl.remove(), 300);
            };

            const btnCerrar = toastEl.querySelector('[data-toast-close]');
            if (btnCerrar) btnCerrar.addEventListener('click', cerrarToast);

            setTimeout(cerrarToast, 3000);
        });

        // --- Reloj analógico + digital, formato 12h con AM/PM ---
        function actualizarReloj() {
            const ahora = new Date();
            const horas24 = ahora.getHours();
            const minutos = ahora.getMinutes();
            const segundos = ahora.getSeconds();

            const gradosHora = (horas24 % 12) * 30 + minutos * 0.5;
            const gradosMin = minutos * 6;
            const gradosSeg = segundos * 6;

            const manecillaHora = document.getElementById('reloj-hora');
            const manecillaMin = document.getElementById('reloj-min');
            const manecillaSeg = document.getElementById('reloj-seg');
            if (manecillaHora) manecillaHora.style.transform = `rotate(${gradosHora}deg)`;
            if (manecillaMin) manecillaMin.style.transform = `rotate(${gradosMin}deg)`;
            if (manecillaSeg) manecillaSeg.style.transform = `rotate(${gradosSeg}deg)`;

            let horas12 = horas24 % 12;
            horas12 = horas12 === 0 ? 12 : horas12;
            const meridiano = horas24 >= 12 ? 'PM' : 'AM';

            const digital = document.getElementById('reloj-digital');
            if (digital) {
                digital.innerHTML = `${String(horas12).padStart(2, '0')}:${String(minutos).padStart(2, '0')}:${String(segundos).padStart(2, '0')} <span class="text-lg sm:text-xl text-blue-500">${meridiano}</span>`;
            }

            const fecha = document.getElementById('reloj-fecha');
            if (fecha) {
                fecha.textContent = ahora.toLocaleDateString('es-ES', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            }
        }

        // --- Tiempos de trabajo / pausa en vivo ---
        function formatearDuracion(totalSegundos) {
            const h = String(Math.floor(totalSegundos / 3600)).padStart(2, '0');
            const m = String(Math.floor((totalSegundos % 3600) / 60)).padStart(2, '0');
            const s = String(Math.floor(totalSegundos % 60)).padStart(2, '0');
            return `${h}:${m}:${s}`;
        }

        function calcularTiempos() {
            const cfg = window.checadorConfig || {};
            const ahora = Date.now();
            let segundosTrabajados = 0;
            let segundosPausados = Number(cfg.segundosPausaAcumulados) || 0;

            const inicio = cfg.horaEntrada ? new Date(cfg.horaEntrada).getTime() : null;

            if (inicio && !isNaN(inicio)) {
                if (cfg.estado === 'pausado' && cfg.pausaInicio) {
                    const pausaInicio = new Date(cfg.pausaInicio).getTime();
                    if (!isNaN(pausaInicio)) {
                        segundosTrabajados = Math.floor((pausaInicio - inicio) / 1000) - segundosPausados;
                        segundosPausados += Math.floor((ahora - pausaInicio) / 1000);
                    }
                } else if (cfg.estado === 'terminado') {
                    const finRaw = cfg.horaSalida ? new Date(cfg.horaSalida).getTime() : ahora;
                    const fin = isNaN(finRaw) ? ahora : finRaw;
                    segundosTrabajados = Math.floor((fin - inicio) / 1000) - segundosPausados;
                } else if (cfg.estado === 'trabajando') {
                    segundosTrabajados = Math.floor((ahora - inicio) / 1000) - segundosPausados;
                }
            }

            segundosTrabajados = Math.max(0, segundosTrabajados || 0);
            segundosPausados = Math.max(0, segundosPausados || 0);

            const elTrabajado = document.getElementById('tiempoTrabajado');
            const elPausa = document.getElementById('tiempoPausa');
            if (elTrabajado) elTrabajado.textContent = formatearDuracion(segundosTrabajados);
            if (elPausa) elPausa.textContent = formatearDuracion(segundosPausados);
        }

        actualizarReloj();
        calcularTiempos();
        setInterval(() => {
            actualizarReloj();
            calcularTiempos();
        }, 1000);


        // ==========================================
        // EVENTOS DE LOS MODALES (ACCIONES)
        // ==========================================

        const btnConfirmarPausa = document.getElementById('confirmarFinalizarPausa');
        if (btnConfirmarPausa) {
            btnConfirmarPausa.addEventListener('click', () => {
                document.getElementById('formFinalizarPausa').submit();
            });
        }

        const btnConfirmarSalida = document.getElementById('confirmarSalida');
        if (btnConfirmarSalida) {
            btnConfirmarSalida.addEventListener('click', () => {
                document.getElementById('formSalida').submit();
            });
        }


        // ==========================================
        // EVENTOS PARA CERRAR MODALES
        // ==========================================

        document.querySelectorAll('.btn-close-modal').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const modal = e.target.closest('[role="dialog"]');
                if (modal) window.closeModal(modal);
            });
        });

        document.querySelectorAll('[role="dialog"]').forEach(modal => {
            modal.addEventListener('mousedown', (e) => {
                if (e.target === modal) {
                    window.closeModal(modal);
                }
            });
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const modalesAbiertos = document.querySelectorAll('[role="dialog"]:not(.opacity-0)');
                modalesAbiertos.forEach(modal => window.closeModal(modal));
            }
        });

    });
</script>
@endsection