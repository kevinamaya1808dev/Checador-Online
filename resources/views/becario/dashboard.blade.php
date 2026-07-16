@extends('layouts.app')

@section('content')

@php
    // Estado esperado desde el controlador:
    // 'inactivo' | 'trabajando' | 'pausado' | 'terminado'

    $estadoRaw = $estado ?? null;
    $estado    = $estadoRaw ?? 'inactivo';

    // Solo bloqueamos botones cuando el backend ya manda un estado real
    $gating = !is_null($estadoRaw);

    $estadoInfo = [
        'inactivo' => [
            'label' => 'Esperando turno',
            'desc'  => 'Aún no has registrado tu entrada.',
            'texto' => 'text-slate-300',
            'icon'  => 'bi-hourglass-split',
        ],

        'trabajando' => [
            'label' => 'Trabajando',
            'desc'  => 'Tu turno está activo.',
            'texto' => 'text-green-400',
            'icon'  => 'bi-briefcase-fill',
        ],

        'pausado' => [
            'label' => 'En pausa',
            'desc'  => 'Descanso en curso.',
            'texto' => 'text-amber-400',
            'icon'  => 'bi-cup-hot',
        ],

        'terminado' => [
            'label' => 'Turno finalizado',
            'desc'  => 'Registro guardado correctamente.',
            'texto' => 'text-red-400',
            'icon'  => 'bi-flag-fill',
        ],

    ][$estado] ?? [
        'label' => ucfirst($estado),
        'desc'  => '',
        'texto' => 'text-slate-300',
        'icon'  => 'bi-question-circle',
    ];


    $estadoBanner = [

        'inactivo' => [
            'wash'   => 'from-slate-500/10',
            'border' => 'border-slate-500/20',
            'iconBg' => 'bg-gradient-to-br from-slate-500 to-slate-700',
            'iconSh' => 'shadow-[0_8px_20px_-4px_rgba(100,116,139,0.5)]',
            'dot'    => 'bg-slate-400',
            'pulse'  => false,
        ],

        'trabajando' => [
            'wash'   => 'from-green-500/10',
            'border' => 'border-green-500/25',
            'iconBg' => 'bg-gradient-to-br from-green-500 to-green-700',
            'iconSh' => 'shadow-[0_8px_20px_-4px_rgba(34,197,94,0.55)]',
            'dot'    => 'bg-green-400',
            'pulse'  => true,
        ],

        'pausado' => [
            'wash'   => 'from-amber-500/10',
            'border' => 'border-amber-500/25',
            'iconBg' => 'bg-gradient-to-br from-amber-500 to-amber-700',
            'iconSh' => 'shadow-[0_8px_20px_-4px_rgba(217,119,6,0.55)]',
            'dot'    => 'bg-amber-400',
            'pulse'  => true,
        ],

        'terminado' => [
            'wash'   => 'from-red-500/10',
            'border' => 'border-red-500/25',
            'iconBg' => 'bg-gradient-to-br from-red-500 to-red-700',
            'iconSh' => 'shadow-[0_8px_20px_-4px_rgba(220,38,38,0.5)]',
            'dot'    => 'bg-red-400',
            'pulse'  => false,
        ],

    ][$estado] ?? [

        'wash'   => 'from-slate-500/10',
        'border' => 'border-slate-500/20',
        'iconBg' => 'bg-gradient-to-br from-slate-500 to-slate-700',
        'iconSh' => 'shadow-[0_8px_20px_-4px_rgba(100,116,139,0.5)]',
        'dot'    => 'bg-slate-400',
        'pulse'  => false,

    ];

    $puedeEntrada  = !$gating || $estado === 'inactivo';
    $puedePausar   = !$gating || $estado === 'trabajando';
    $puedeReanudar = !$gating || $estado === 'pausado';
    $puedeSalir    = !$gating || $estado === 'trabajando';

    $destacarEntrada  = $gating && $puedeEntrada;
    $destacarPausar   = $gating && $puedePausar;
    $destacarReanudar = $gating && $puedeReanudar;
    $destacarSalir    = $gating && $puedeSalir;

    $inicial = auth()->check()
        ? mb_strtoupper(mb_substr(auth()->user()->name, 0, 1))
        : 'U';

@endphp


{{-- Estilos propios del dashboard --}}
@include('becario.components.dashboard.styles')


{{-- Notificaciones --}}
@include('becario.components.dashboard.notificaciones')


<div class="relative min-h-screen bg-slate-950 text-white overflow-hidden">

    {{-- Fondo decorativo --}}
    <div class="pointer-events-none absolute inset-0 -z-0 overflow-hidden">

        <div class="absolute -top-24 -left-24 w-[420px] h-[420px] rounded-full bg-blue-600/10 blur-[110px]"></div>

        <div class="absolute top-1/3 -right-32 w-[420px] h-[420px] rounded-full bg-amber-500/10 blur-[120px]"></div>

        <div class="absolute bottom-0 left-1/4 w-[360px] h-[360px] rounded-full bg-red-600/5 blur-[110px]"></div>

    </div>


    <div class="relative max-w-[1320px] mx-auto py-5 px-3 md:px-4">

        {{-- Header --}}
        @include('becario.components.dashboard.header')


        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

            {{-- Columna Izquierda --}}
            <div class="lg:col-span-7">

                @include('becario.components.dashboard.reloj')

                @include('becario.components.dashboard.estado-banner')

                @include('becario.components.dashboard.tiempos')

            </div>


            {{-- Columna Derecha --}}
            <div class="lg:col-span-5">

                @include('becario.components.dashboard.acciones')

            </div>

        </div>

    </div>

</div>


{{-- Modales --}}
@include('becario.modals.confirmar_descanso')
@include('becario.modals.finalizar_turno')


{{-- Config real del backend para el reloj y los contadores de tiempo. --}}
{{-- IMPORTANTE: debe ir ANTES del script externo, para que --}}
{{-- window.checadorConfig ya tenga los datos reales cuando corra dashboard-becario.js --}}
<script>
    window.checadorConfig = {
        estado: @json($estado),
        horaEntrada: @json($horaEntrada ?? null),
        pausaInicio: @json($pausaInicio ?? null),
        horaSalida: @json($horaSalida ?? null),
        segundosPausaAcumulados: {{ (int) ($segundosPausaAcumulados ?? 0) }}
    };
</script>

{{-- Script del Dashboard --}}
<script src="{{ asset('js/dashboard-becario.js') }}"></script>

@endsection