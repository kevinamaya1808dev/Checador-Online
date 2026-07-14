@extends('layouts.app')
@section('content')
<div class="container-fluid px-3 px-md-4">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center text-center text-sm-start mb-4 gap-2">
        <h2 class="fw-bold mb-0 dashboard-title">
            <i class="bi bi-clock-history text-info me-2"></i>Panel de Control: Asistencias
        </h2>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-4">
            <div class="card bg-dark border-secondary shadow rounded-4 h-100">
                <div class="card-body px-2 px-sm-3 py-2 py-sm-3">
                    <div class="text-secondary small">Becarios Activos</div>
                    <h2 id="card-activos" class="text-success fw-bold mb-0 dashboard-stat">0</h2>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card bg-dark border-secondary shadow rounded-4 h-100">
                <div class="card-body px-2 px-sm-3 py-2 py-sm-3">
                    <div class="text-secondary small">En Descanso</div>
                    <h2 id="card-descanso" class="text-info fw-bold mb-0 dashboard-stat">0</h2>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card bg-dark border-secondary shadow rounded-4 h-100">
                <div class="card-body px-2 px-sm-3 py-2 py-sm-3">
                    <div class="text-secondary small">Turnos Finalizados</div>
                    <h2 id="card-finalizados" class="text-warning fw-bold mb-0 dashboard-stat">0</h2>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card bg-dark border-secondary shadow-lg rounded-4">
                <div class="card-body p-0 overflow-hidden">

                    {{-- Tabla (tablet / desktop) --}}
                    <div class="table-responsive dashboard-table-wrap d-none d-md-block">
                        <table class="table table-dark table-hover mb-0 align-middle dashboard-table" style="min-width: 800px;">
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

                    {{-- Tarjetas (móvil) --}}
                    <div id="tarjetas-asistencias" class="d-block d-md-none p-3">
                        <p id="tarjetas-vacio" class="text-center text-secondary py-4 mb-0">
                            <i class="bi bi-clock-history fs-3 d-block mb-2"></i>
                            No existen asistencias activas actualmente
                        </p>
                    </div>

                    <div class="dashboard-scroll-hint d-none d-md-block d-lg-none text-center text-secondary small py-2 border-top border-secondary">
                        <i class="bi bi-arrow-left-right me-1"></i>Desliza para ver toda la tabla
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Sistema responsivo del dashboard de asistencias */

    @media (max-width: 991.98px) {
        .dashboard-title {
            font-size: 1.4rem;
        }
    }

    @media (max-width: 575.98px) {
        .dashboard-title {
            font-size: 1.15rem;
        }

        .dashboard-stat {
            font-size: 1.1rem;
        }

        .card-body .small {
            font-size: .68rem;
        }
    }

    .dashboard-scroll-hint {
        background: rgba(255,255,255,0.02);
    }

    /* ---- Tarjetas por usuario (móvil) ---- */
    .becario-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 0.75rem;
        overflow: hidden;
    }

    .becario-card + .becario-card {
        margin-top: 0.75rem;
    }

    .becario-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .becario-card-user {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        min-width: 0;
    }

    .becario-card-user span {
        color: #fff;
        font-weight: 600;
        font-size: 0.9rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .becario-card-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .becario-card-item {
        padding: 0.6rem 1rem;
        border-right: 1px solid rgba(255, 255, 255, 0.08);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .becario-card-item:nth-child(2n) {
        border-right: none;
    }

    .becario-card-item:nth-last-child(-n+2) {
        border-bottom: none;
    }

    .becario-card-label {
        margin: 0;
        font-size: 0.72rem;
        text-transform: uppercase;
        color: #adb5bd;
    }

    .becario-card-value {
        margin: 2px 0 0;
        font-size: 0.85rem;
    }

    .becario-card-extras {
        padding: 0.6rem 1rem;
        font-size: 0.8rem;
        color: #adb5bd;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }
</style>

{{-- Solo configuración: las rutas Blade no pueden vivir en el .js externo,
     así que se exponen aquí como datos globales antes de cargar el script. --}}
<script>
    window.RUTAS = {
        tiempos: "{{ route('admin.tiempos') }}"
    };
</script>
<script src="{{ asset('js/admin/dashboard.js') }}" defer></script>
@endsection