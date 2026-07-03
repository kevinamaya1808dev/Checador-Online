@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white fw-bold">Panel de Control: Asistencias</h2>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalRegistrarBecario">
                <i class="bi bi-person-plus"></i> + Nuevo Becario
            </button>
            <a href="#" class="btn btn-primary shadow-sm">
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
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($asistencias as $a)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $a->user->name }}</td>
                            <td class="text-muted">{{ $a->fecha }}</td>
                            <td>{{ $a->hora_entrada ? \Carbon\Carbon::parse($a->hora_entrada)->format('H:i') : '--:--' }}</td>
                            <td>{{ $a->hora_salida ? \Carbon\Carbon::parse($a->hora_salida)->format('H:i') : 'Pendiente' }}</td>
                            <td>
                                @if($a->hora_salida)
                                    <span class="badge bg-success">Completado</span>
                                @else
                                    <span class="badge bg-warning text-dark">Activo</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-info me-2">Editar</button>
                                <button class="btn btn-sm btn-outline-danger">Eliminar</button>
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