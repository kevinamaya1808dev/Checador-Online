@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class=" fw-bold">Panel de Control: Asistencias</h2>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalRegistrarBecario">
                <i class="bi bi-person-plus"></i> + Nuevo Becario
            </button>
            
            <a href="{{ route('admin.exportar') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-file-earmark-arrow-down"></i> Exportar Reporte
            </a>
        </div>
    </div>
<div class="container-fluid mb-3 text-end">
    <button class="btn btn-outline-secondary btn-sm" onclick="toggleTheme()">
        <i class="bi bi-circle-half"></i> Cambiar Modo
    </button>
</div>
    <div class="card border-0 shadow-sm ">
    <div class="card-body p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" style="min-width: 600px;">
                    <thead class="table-active">
                        <tr>
                            <th class="ps-4">Becario</th>
                            <th>Fecha</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Estado</th>
                            </tr>
                    </thead>
                    <tbody>
    @foreach($asistencias as $a)
    <tr>
        <td class="ps-4 fw-bold">{{ $a->user->name }}</td>
        <td class>
    {{ $a->fecha ? \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') : 'N/A' }}
</td>
        <td>{{ $a->hora_entrada ? \Carbon\Carbon::parse($a->hora_entrada)->format('h:i A') : '--:--' }}</td>
        <td>{{ $a->hora_salida ? \Carbon\Carbon::parse($a->hora_salida)->format('h:i A') : '---' }}</td>
        
        <td>
            @if($a->hora_salida)
                <span class="badge bg-secondary">Turno terminado</span>
            @elseif($a->user->pausas->isNotEmpty())
                <span class="badge bg-info text-dark">En descanso</span>
            @else
                <span class="badge bg-success">Activo</span>
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