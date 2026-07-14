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
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-dark border-secondary shadow rounded-4">
                <div class="card-body">
                    <div class="text-secondary small">Becarios Activos</div>
                    <h2 id="card-activos" class="text-success fw-bold mb-0">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-dark border-secondary shadow rounded-4">
                <div class="card-body">
                    <div class="text-secondary small">En Descanso</div>
                    <h2 id="card-descanso" class="text-info fw-bold mb-0">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-dark border-secondary shadow rounded-4">
                <div class="card-body">
                    <div class="text-secondary small">Turnos Finalizados</div>
                    <h2 id="card-finalizados" class="text-warning fw-bold mb-0">0</h2>
                </div>
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
                                <th class="py-3 fw-semibold text-uppercase small">Horas Extras</th>
                                <th class="py-3 fw-semibold text-uppercase small">Estado</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-asistencias">
                            <tr id="tabla-vacia">
                                <td colspan="8" class="text-center text-secondary py-5">
                                    <i class="bi bi-clock-history fs-3 d-block mb-2"></i>
                                    No existen asistencias activas actualmente
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.modals.registrar-becario')

{{-- Solo configuración: las rutas Blade no pueden vivir en el .js externo,
     así que se exponen aquí como datos globales antes de cargar el script. --}}
<script>
    window.RUTAS = {
        tiempos: "{{ route('admin.tiempos') }}"
    };
</script>
<script src="{{ asset('js/admin/dashboard.js') }}" defer></script>
@endsection