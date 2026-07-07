@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-clock-history text-info me-2"></i>Panel de Control: Asistencias
        </h2>
        <div class="d-flex gap-2">
            <!-- Botón para abrir el modal -->
            <button type="button" class="btn btn-outline-primary shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#modalRegistrarBecario">
                <i class="bi bi-person-plus"></i> + Nuevo Becario
            </button>
            <!-- Botón de Exportar (Asegúrate de tener esta ruta en web.php) -->
            <a href="{{ route('admin.exportar') }}" class="btn btn-primary shadow-sm rounded-3">
                <i class="bi bi-file-earmark-arrow-down"></i> Exportar Reporte
            </a>
        </div>
    </div>

    <div class="card bg-dark border-secondary shadow-lg rounded-4">
        <div class="card-body p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle" style="min-width: 800px;">
                    <thead>
                        <tr class="text-secondary" style="background-color: rgba(255,255,255,0.03);">
                            <th class="ps-4 py-3 fw-semibold text-uppercase small">Becario</th>
                            <th class="py-3 fw-semibold text-uppercase small">Fecha</th>
                            <th class="py-3 fw-semibold text-uppercase small">Entrada</th>
                            <th class="py-3 fw-semibold text-uppercase small">Salida</th>
                            <th class="py-3 fw-semibold text-uppercase small">Pausas</th>
                            <th class="py-3 fw-semibold text-uppercase small">Tiempo Total</th>
                            <th class="py-3 fw-semibold text-uppercase small">Estado</th>
                            <!-- Acciones eliminadas de aquí -->
                        </tr>
                    </thead>
                    <tbody>
    @foreach($asistencias as $a)
    <tr>
        <td class="ps-4 py-3">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-secondary bg-opacity-25 border border-secondary d-flex align-items-center justify-content-center text-info fw-bold"
                     style="width:36px;height:36px;font-size:0.9rem;">
                    {{ strtoupper(substr($a->user->name, 0, 1)) }}
                </div>
                <span class="text-white fw-bold">{{ $a->user->name }}</span>
            </div>
        </td>
        <td class="py-3">
            {{ $a->fecha ? \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') : 'N/A' }}
        </td>
        <td class="py-3">
            <span class="badge rounded-pill text-bg-success bg-opacity-25 text-success px-3 py-2">
                <i class="bi bi-box-arrow-in-right me-1"></i>{{ $a->hora_entrada ? \Carbon\Carbon::parse($a->hora_entrada)->format('h:i A') : '--:--' }}
            </span>
        </td>
        <td class="py-3">
            <span class="badge rounded-pill text-bg-danger bg-opacity-25 text-danger px-3 py-2">
                <i class="bi bi-box-arrow-left me-1"></i>{{ $a->hora_salida ? \Carbon\Carbon::parse($a->hora_salida)->format('h:i A') : '---' }}
            </span>
        </td>
        <td class="py-3">
    <span class="badge rounded-pill text-bg-warning bg-opacity-25 text-warning px-3 py-2">
        <i class="bi bi-cup-hot me-1"></i>

        {{ $a->tiempoPausas() }}

    </span>
</td>
        <td class="py-3">
    <span class="badge rounded-pill text-bg-info bg-opacity-25 text-info px-3 py-2">

        <i class="bi bi-stopwatch me-1"></i>

        {{ $a->formatoTiempo($a->tiempoTrabajado()) }}

    </span>
</td>
        <td class="py-3">
            @if($a->hora_salida)
                <span class="badge rounded-pill text-bg-secondary px-3 py-2">Turno terminado</span>
            @elseif($a->user->pausas->isNotEmpty())
                <span class="badge rounded-pill text-bg-info text-dark px-3 py-2">En descanso</span>
            @else
                <span class="badge rounded-pill text-bg-success px-3 py-2">Activo</span>
            @endif
        </td>
    </tr>
    @endforeach
</tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('admin.modals.registrar-becario')
@endsection