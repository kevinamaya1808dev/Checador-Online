@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card bg-dark text-white border border-secondary shadow-lg">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Control de Jornada</h4>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-box-arrow-right"></i> Salir
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <div class="d-grid gap-3 mb-4">
                        <form action="{{ route('becario.checar') }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-primary btn-lg w-100">REGISTRAR ENTRADA</button>
                        </form>
                        <form action="{{ route('becario.salida') }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-warning btn-lg w-100">REGISTRAR SALIDA</button>
                        </form>
                    </div>

                    <hr class="border-secondary">

                    <div class="d-grid gap-2">
                        <button class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#pausaMenu">INICIAR PAUSA</button>
                        
                        <div class="collapse" id="pausaMenu">
                            <form action="{{ route('becario.iniciarPausa') }}" method="POST" class="mt-2">
                                @csrf
                                <select name="motivo" class="form-select bg-dark text-white mb-2">
                                    <option value="Almuerzo">Almuerzo</option>
                                    <option value="Personal">Personal</option>
                                </select>
                                <button class="btn btn-primary w-100">CONFIRMAR PAUSA</button>
                            </form>
                        </div>

                        <form action="{{ route('becario.finalizarPausa') }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-success w-100">FINALIZAR PAUSA</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection