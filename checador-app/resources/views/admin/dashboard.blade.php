@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white fw-bold">Panel de Control: Asistencias</h2>
        <div class="d-flex gap-2">
            <!-- Botón para abrir el modal -->
            <button type="button" class="btn btn-outline-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalRegistrarBecario">
                <i class="bi bi-person-plus"></i> + Nuevo Becario
            </button>
            
            <!-- Botón de Exportar (Asegúrate de tener esta ruta en web.php) -->
            <a href="{{ route('admin.exportar') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-file-earmark-arrow-down"></i> Exportar Reporte
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm bg-dark">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead class="table-active">
                        <tr>
                            <th class="ps-4">Becario</th>
                            <th>Fecha</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Estado</th>
                            <!-- Acciones eliminadas de aquí -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($asistencias as $a)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $a->user->name }}</td>
                            <td class="text-muted">{{ $a->fecha }}</td>
                            <td>{{ \Carbon\Carbon::parse($a->hora_entrada)->format('h:i A') }}</td>
                            <td>
                                {{ $a->hora_salida ? \Carbon\Carbon::parse($a->hora_salida)->format('h:i A') : 'No ha salido' }}
                            </td>
                            <td>
                                @if($a->hora_salida)
                                    <span class="badge bg-success">Completado</span>
                                @else
                                    <span class="badge bg-warning text-dark">Activo</span>
                                @endif
                            </td>
                            <!-- Celda de acciones eliminada para mantener diseño limpio -->
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