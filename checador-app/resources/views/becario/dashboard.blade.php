@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #020617; /* slate-950 */
    }

    .glass-card {
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
    }

    .glass-soft {
        background: rgba(30, 41, 59, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 16px;
    }

    /* --- Reloj analógico --- */
    .clock-wrap { width: 250px; height: 250px; margin: 0 auto; }
    .clock-face {
        width: 100%; height: 100%; border-radius: 50%;
        border: 8px solid #001f3f;
        background: linear-gradient(180deg, #00172e, #000d1a);
        box-shadow: inset 0 0 30px #000, 0 0 40px rgba(0, 31, 63, 0.9);
        position: relative;
    }
    .clock-face .num {
        position: absolute; font-family: monospace; font-size: .75rem;
        font-weight: 700; color: rgba(96, 165, 250, .6);
    }
    .num-12 { top: 12px; left: 50%; transform: translateX(-50%); }
    .num-6  { bottom: 12px; left: 50%; transform: translateX(-50%); }
    .num-9  { left: 12px; top: 50%; transform: translateY(-50%); }
    .num-3  { right: 12px; top: 50%; transform: translateY(-50%); }
    .clock-center {
        position: absolute; top: 50%; left: 50%; width: 14px; height: 14px;
        margin: -7px; background: #3b82f6; border-radius: 50%; z-index: 5;
        box-shadow: 0 0 15px #3b82f6;
    }
    .hand { position: absolute; bottom: 50%; left: 50%; transform-origin: bottom center; border-radius: 4px; }
    .hand-hour { width: 6px; height: 60px; margin-left: -3px; background: linear-gradient(#2563eb, #93c5fd); z-index: 2; }
    .hand-min  { width: 4px; height: 88px; margin-left: -2px; background: linear-gradient(#22d3ee, #fff); z-index: 3; }

    /* --- Botones de acción --- */
    .btn-reset {
        background: none; border: none; padding: 0; margin: 0;
        text-align: left; width: 100%; color: inherit; font: inherit; cursor: pointer;
    }
    .accion-item {
        display: flex; align-items: center; gap: 1rem;
        border-radius: 14px; padding: 1rem;
        border: 1px solid rgba(255,255,255,.08);
        background: rgba(15, 23, 42, 0.6);
        transition: transform .2s ease, background .2s ease, opacity .2s ease;
    }
    .accion-item:hover { transform: translateY(-3px); }
    .accion-item:disabled,
    .accion-item[disabled] {
        opacity: .3;
        cursor: not-allowed;
        pointer-events: none;
        transform: none;
    }
    .accion-icon {
        width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: #fff;
    }
    .accion-blue  { border-color: rgba(59,130,246,.4); }
    .accion-blue .accion-icon { background: #2563eb; box-shadow: 0 0 15px rgba(37,99,235,.5); }
    .accion-blue .accion-title { color: #93c5fd; }

    .accion-amber { border-color: rgba(245,158,11,.4); }
    .accion-amber .accion-icon { background: #d97706; box-shadow: 0 0 15px rgba(217,119,6,.5); }
    .accion-amber .accion-title { color: #fcd34d; }

    .accion-red   { border-color: rgba(239,68,68,.4); }
    .accion-red .accion-icon { background: #dc2626; box-shadow: 0 0 15px rgba(185,28,28,.5); }
    .accion-red .accion-title { color: #fca5a5; }

    /* --- Notificaciones toast --- */
    .toast-container { z-index: 1090; }
    .toast {
        border-radius: 14px;
        overflow: hidden;
        min-width: 280px;
    }
</style>

@php
    // $estado esperado desde el controlador: 'inactivo' | 'trabajando' | 'pausado' | 'terminado'
    $estadoRaw = $estado ?? null;
    $estado    = $estadoRaw ?? 'inactivo';
    $gating    = ! is_null($estadoRaw); // solo bloqueamos botones cuando el backend ya manda el estado real

    $estadoInfo = [
        'inactivo'   => ['label' => 'Esperando turno',  'desc' => 'Aún no has registrado tu entrada.',   'color' => 'secondary', 'icon' => 'bi-hourglass-split'],
        'trabajando' => ['label' => 'Trabajando',        'desc' => 'Tu turno está activo.',                'color' => 'success',   'icon' => 'bi-briefcase-fill'],
        'pausado'    => ['label' => 'En pausa',          'desc' => 'Descanso en curso.',                   'color' => 'warning',   'icon' => 'bi-cup-hot'],
        'terminado'  => ['label' => 'Turno finalizado',  'desc' => 'Registro guardado correctamente.',     'color' => 'danger',    'icon' => 'bi-flag-fill'],
    ][$estado] ?? ['label' => ucfirst($estado), 'desc' => '', 'color' => 'secondary', 'icon' => 'bi-question-circle'];

    $puedeEntrada  = ! $gating || $estado === 'inactivo';
    $puedePausar   = ! $gating || $estado === 'trabajando';
    $puedeReanudar = ! $gating || $estado === 'pausado';
    $puedeSalir = ! $gating || $estado === 'trabajando';
@endphp

{{-- NOTIFICACIONES: aparecen a un costado y se autodestruyen a los 3s --}}
<div class="toast-container position-fixed top-0 end-0 p-3">
    @if (session('success'))
        <div class="toast text-white glass-card border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex align-items-center">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-3" data-bs-dismiss="toast" aria-label="Cerrar"></button>
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="toast text-white glass-card border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex align-items-center">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-3" data-bs-dismiss="toast" aria-label="Cerrar"></button>
            </div>
        </div>
    @endif
</div>

<div class="container-xxl py-4 px-3 px-md-4 text-white">

    {{-- HEADER --}}
    <div class="glass-card d-flex justify-content-between align-items-center px-4 py-3 mb-4">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-clock-history fs-4 text-primary"></i>
            <h1 class="h5 fw-bold text-uppercase mb-0" style="letter-spacing:2px;">
                Checador <span class="text-primary">Online</span>
            </h1>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="d-none d-sm-flex align-items-center gap-2 small fw-semibold">
                <i class="bi bi-person-circle text-primary fs-5"></i>
                {{ auth()->check() ? auth()->user()->name : 'Usuario' }}
            </span>
            <form action="{{ route('logout') }}" method="POST" class="mb-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                    <i class="bi bi-power"></i> Salir
                </button>
            </form>
        </div>
    </div>

    <div class="row g-4">

        {{-- IZQUIERDA: RELOJ + ESTADO --}}
        <div class="col-lg-7">
            <div class="glass-card p-4 h-100">

                <div class="clock-wrap mt-2">
                    <div class="clock-face">
                        <span class="num num-12">12</span>
                        <span class="num num-6">6</span>
                        <span class="num num-9">9</span>
                        <span class="num num-3">3</span>
                        <div class="clock-center"></div>
                        <div id="reloj-hora" class="hand hand-hour"></div>
                        <div id="reloj-min" class="hand hand-min"></div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <div class="display-6 fw-bold font-monospace" id="reloj-digital">--:--:--</div>
                    <p class="text-secondary small mb-0 text-capitalize" id="reloj-fecha"></p>
                </div>

                <div class="glass-soft mt-4 p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-uppercase text-secondary mb-1" style="letter-spacing:1px; font-size:.7rem;">Estado actual</p>
                        <p class="fw-bold fs-5 mb-0 text-{{ $estadoInfo['color'] }}">
                            <i class="bi bi-circle-fill me-1" style="font-size:.55rem;"></i>{{ $estadoInfo['label'] }}
                        </p>
                        <p class="text-secondary small mb-0">{{ $estadoInfo['desc'] }}</p>
                    </div>
                    <i class="bi {{ $estadoInfo['icon'] }} fs-2 text-{{ $estadoInfo['color'] }} opacity-75"></i>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-sm-6">
                        <div class="glass-soft p-3 d-flex align-items-center gap-3">
                            <i class="bi bi-clock fs-4 text-success"></i>
                            <div>
                                <p class="text-uppercase text-secondary mb-0" style="font-size:.65rem; letter-spacing:1px;">Tiempo trabajado</p>
                                <p class="fw-bold font-monospace fs-5 mb-0" id="tiempoTrabajado">00:00:00</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="glass-soft p-3 d-flex align-items-center gap-3">
                            <i class="bi bi-cup-hot fs-4 text-warning"></i>
                            <div>
                                <p class="text-uppercase text-secondary mb-0" style="font-size:.65rem; letter-spacing:1px;">Tiempo en pausa</p>
                                <p class="fw-bold font-monospace fs-5 mb-0" id="tiempoPausa">00:00:00</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- DERECHA: ACCIONES --}}
        <div class="col-lg-5">
            <div class="glass-card p-4 h-100 d-flex flex-column">
                <p class="text-uppercase text-secondary fw-bold mb-3" style="letter-spacing:2px; font-size:.75rem;">Acciones</p>

                <div class="d-flex flex-column gap-3">

                    <form action="{{ route('becario.checar') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn-reset accion-item accion-blue" @disabled(!$puedeEntrada)>
                            <span class="accion-icon"><i class="bi bi-box-arrow-in-right"></i></span>
                            <span>
                                <span class="d-block fw-bold accion-title">Registrar entrada</span>
                                <span class="d-block small text-secondary">Inicia el registro de tu turno</span>
                            </span>
                        </button>
                    </form>

                    <div>
                        <button type="button" class="btn-reset accion-item accion-amber" data-bs-toggle="collapse" data-bs-target="#pausaMenu" @disabled(!$puedePausar)>
                            <span class="accion-icon"><i class="bi bi-cup-hot"></i></span>
                            <span>
                                <span class="d-block fw-bold accion-title">Gestionar pausa</span>
                                <span class="d-block small text-secondary">Justificación obligatoria</span>
                            </span>
                        </button>

                        <div class="collapse mt-2" id="pausaMenu">
                            <form action="{{ route('becario.iniciarPausa') }}" method="POST"
                                  class="glass-soft p-3" style="border-color: rgba(245,158,11,.3);">
                                @csrf
                                <label class="small text-warning fw-bold text-uppercase mb-2 d-block" style="font-size:.7rem;">
                                    Motivo de pausa
                                </label>
                                <select name="motivo" class="form-select form-select-sm bg-dark text-white border-secondary mb-3">
                                    <option value="Almuerzo">Almuerzo</option>
                                    <option value="Personal">Personal</option>
                                </select>
                                <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold text-uppercase" @disabled(!$puedePausar)>
                                    Iniciar ahora
                                </button>
                            </form>
                        </div>
                    </div>

                    <form
    id="formFinalizarPausa"
    action="{{ route('becario.finalizarPausa') }}"
    method="POST"
    class="m-0">

    @csrf

    <button
        type="button"
        class="btn-reset accion-item accion-blue"
        data-bs-toggle="modal"
        data-bs-target="#modalFinalizarPausa"
        @disabled(!$puedeReanudar)>

        <span class="accion-icon">
            <i class="bi bi-play-fill"></i>
        </span>

        <span>

            <span class="d-block fw-bold accion-title">

                Finalizar pausa

            </span>

            <span class="d-block small text-secondary">

                Reanuda tus actividades

            </span>

        </span>

    </button>

</form>

                    <form
    id="formSalida"
    action="{{ route('becario.salida') }}"
    method="POST"
    class="m-0">

    @csrf

    <button
        type="button"
        class="btn-reset accion-item accion-red"
        data-bs-toggle="modal"
        data-bs-target="#modalSalida"
        @disabled(!$puedeSalir)>

        <span class="accion-icon">

            <i class="bi bi-box-arrow-left"></i>

        </span>

        <span>

            <span class="d-block fw-bold accion-title">

                Registrar salida

            </span>

            <span class="d-block small text-secondary">

                Finaliza tu turno

            </span>

        </span>

    </button>

</form>

                </div>

                <div class="glass-soft mt-auto p-3 d-flex gap-2 align-items-start">
                    <i class="bi bi-info-circle-fill text-primary mt-1"></i>
                    <div>
                        <p class="fw-bold small mb-0">Recuerda registrar tus pausas</p>
                        <p class="text-secondary mb-0" style="font-size:.75rem;">Para llevar un control correcto de tu tiempo laboral.</p>
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
    // Si tu controlador aún no los envía, todo cae a valores por defecto sin romper nada.
    window.checadorConfig = {
        estado: @json($estado),
        horaEntrada: @json($horaEntrada ?? null),
        pausaInicio: @json($pausaInicio ?? null),
        horaSalida: @json($horaSalida ?? null),
        segundosPausaAcumulados: {{ (int) ($segundosPausaAcumulados ?? 0) }}
    };

    document.addEventListener('DOMContentLoaded', () => {

        // --- Notificaciones: se muestran solas y desaparecen a los 3s ---
        document.querySelectorAll('.toast').forEach((toastEl) => {
            const toast = new bootstrap.Toast(toastEl, { delay: 3000, autohide: true });
            toast.show();
        });

        // --- Reloj analógico + digital, formato 12h con AM/PM ---
        function actualizarReloj() {
            const ahora = new Date();
            const horas24 = ahora.getHours();
            const minutos = ahora.getMinutes();
            const segundos = ahora.getSeconds();

            const gradosHora = (horas24 % 12) * 30 + minutos * 0.5;
            const gradosMin = minutos * 6;

            const manecillaHora = document.getElementById('reloj-hora');
            const manecillaMin = document.getElementById('reloj-min');
            if (manecillaHora) manecillaHora.style.transform = `rotate(${gradosHora}deg)`;
            if (manecillaMin) manecillaMin.style.transform = `rotate(${gradosMin}deg)`;

            let horas12 = horas24 % 12;
            horas12 = horas12 === 0 ? 12 : horas12;
            const meridiano = horas24 >= 12 ? 'PM' : 'AM';

            const digital = document.getElementById('reloj-digital');
            if (digital) {
                digital.innerHTML = `${String(horas12).padStart(2, '0')}:${String(minutos).padStart(2, '0')}:${String(segundos).padStart(2, '0')} <span class="fs-6 text-primary">${meridiano}</span>`;
            }

            const fecha = document.getElementById('reloj-fecha');
            if (fecha) {
                fecha.textContent = ahora.toLocaleDateString('es-ES', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            }
        }

        // --- Tiempos de trabajo / pausa en vivo, a partir de timestamps reales ---
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

        // Confirmar finalizar pausa
const btnConfirmarPausa = document.getElementById('confirmarFinalizarPausa');

if (btnConfirmarPausa) {

    btnConfirmarPausa.addEventListener('click', () => {

        document.getElementById('formFinalizarPausa').submit();

    });

}

// Confirmar salida
const btnConfirmarSalida = document.getElementById('confirmarSalida');

if (btnConfirmarSalida) {

    btnConfirmarSalida.addEventListener('click', () => {

        document.getElementById('formSalida').submit();

    });

}
    });
</script>

@endsection