@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card bg-dark text-light border border-secondary shadow-lg">
                <div class="card-header border-bottom border-secondary text-center py-4">
                    <h4 class="mb-0 text-uppercase fw-bold">Panel de Asistencia</h4>
                    <small class="text-secondary">{{ now()->format('d/m/Y') }}</small>
                </div>

                <div class="card-body p-4">
                    <div class="d-grid gap-3 mb-4">
                        <form action="{{ route('becario.checar') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-info w-100 btn-lg">Registrar Entrada</button>
                        </form>
                        
                        <form action="{{ route('becario.checar') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning w-100 btn-lg">Registrar Salida</button>
                        </form>
                    </div>

                    <hr class="border-secondary">

                    <div class="mt-4">
                        <button class="btn btn-outline-secondary w-100" type="button" data-bs-toggle="collapse" data-bs-target="#pausaCollapse">
                            <i class="bi bi-cup-hot"></i> Pausa de Descanso
                        </button>

                        <div class="collapse mt-3" id="pausaCollapse">
                            <div class="card card-body bg-secondary text-white border-0">
                                <form action="#" method="POST">
                                    <label class="mb-2">Motivo de la pausa:</label>
                                    <select class="form-select bg-dark text-white border-0 mb-3" name="motivo">
                                        <option value="almuerzo">Almuerzo</option>
                                        <option value="personal">Asuntos Personales</option>
                                        <option value="reunion">Reunión de Emergencia</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary w-100">Iniciar Pausa</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection