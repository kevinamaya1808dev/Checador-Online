{{-- resources/views/becario/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')

@php
    $presenter = $presenter ?? new \App\Support\EstadoTurnoPresenter($estado ?? null);
    $inicial = auth()->check() ? mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) : 'U';
@endphp

@vite(['resources/css/becario-dashboard.css'])

<x-becario.toasts />

{{-- Fondo principal: Transición entre blanco y oscuro --}}
<div class="relative min-h-screen bg-white dark:bg-slate-950 text-gray-900 dark:text-white overflow-hidden transition-colors duration-300">
    
    {{-- Blobs decorativos: Solo visibles en modo oscuro para no ensuciar el diseño claro --}}
    <div class="pointer-events-none absolute inset-0 -z-0 overflow-hidden hidden dark:block">
        <div class="absolute -top-24 -left-24 w-[420px] h-[420px] rounded-full bg-blue-600/10 blur-[110px]"></div>
        <div class="absolute top-1/3 -right-32 w-[420px] h-[420px] rounded-full bg-amber-500/10 blur-[120px]"></div>
        <div class="absolute bottom-0 left-1/4 w-[360px] h-[360px] rounded-full bg-red-600/5 blur-[110px]"></div>
    </div>

    <div class="relative max-w-[1320px] mx-auto py-5 px-3 md:px-4">

        <x-becario.header :estado-info="$presenter->info" :estado-banner="$presenter->banner" :inicial="$inicial" />

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            <div class="lg:col-span-7">
                <x-becario.panel-reloj :estado-info="$presenter->info" :estado-banner="$presenter->banner" />
            </div>
            
            <div class="lg:col-span-5">
                {{-- Panel de Acciones: Adaptado a claro/oscuro --}}
                <div class="entrada bg-white dark:bg-slate-900/85 backdrop-blur-[15px] border border-[#EAE4D8] dark:border-white/[0.08] rounded-3xl p-5 sm:p-6 h-full flex flex-col shadow-sm dark:shadow-lg transition-all" style="animation-delay:.16s">
                    
                    <div class="flex items-center justify-between mb-4">
                        <p class="uppercase text-gray-500 dark:text-slate-500 font-bold tracking-[2px] text-[0.7rem] mb-0">Acciones</p>
                        <span class="h-px flex-1 ml-3 bg-gradient-to-r from-gray-200 dark:from-white/10 to-transparent"></span>
                    </div>

                    <x-becario.panel-acciones :presenter="$presenter" />
                    
                </div>
            </div>
        </div>
    </div>
</div>

@include('becario.modals.confirmar_descanso')
@include('becario.modals.finalizar_turno')

<script>
    window.checadorConfig = {
        estado: @json($presenter->estado),
        horaEntrada: @json($horaEntrada ?? null),
        pausaInicio: @json($pausaInicio ?? null),
        horaSalida: @json($horaSalida ?? null),
        segundosPausaAcumulados: {{ (int) ($segundosPausaAcumulados ?? 0) }}
    };
</script>
@vite('resources/js/becario-dashboard.js')

@endsection