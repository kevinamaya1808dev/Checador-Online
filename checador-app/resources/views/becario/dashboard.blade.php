@extends('layouts.app')

@section('content')
<style>
    .glass-card {
        background: rgba(30, 30, 40, 0.85);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        transition: transform 0.3s ease;
    }
    .glass-card:hover { transform: translateY(-5px); }
    .btn-action { transition: all 0.3s; border-radius: 12px; font-weight: 600; letter-spacing: 0.5px; }
    .btn-action:hover { transform: scale(1.02); }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card glass-card text-white shadow-lg p-3">              
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-3">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i> Control de Jornada</h4>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                            <i class="bi bi-power"></i> Salir
                        </button>
                    </form>
                </div>
                
                <div class="card-body">
                    <div class="d-grid gap-3 mb-4">
                        <form action="{{ route('becario.checar') }}" method="POST">

                            <div class="bg-white dark:bg-slate-900 text-black dark:text-white">
                            @csrf
                            <button class="btn btn-primary btn-lg w-100 btn-action shadow-sm">
                                <i class="bi bi-play-circle me-2"></i> REGISTRAR ENTRADA
                            </button>
                        </form>
                        <form action="{{ route('becario.salida') }}" method="POST">
                            @csrf
                            <button class="btn btn-warning btn-lg w-100 btn-action shadow-sm text-dark">
                                <i class="bi bi-stop-circle me-2"></i> REGISTRAR SALIDA
                            </button>
                        </form>
                    </div>

                    <div class="position-relative my-4">
                        <hr class="border-secondary opacity-25">
                        <span class="position-absolute top-50 start-50 translate-middle bg-dark px-2 text-secondary small">PAUSAS</span>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-secondary btn-action" data-bs-toggle="collapse" data-bs-target="#pausaMenu">
                            <i class="bi bi-cup-hot"></i> GESTIONAR PAUSA
                        </button>
                        
                        <div class="collapse" id="pausaMenu">
                            <form action="{{ route('becario.iniciarPausa') }}" method="POST" class="mt-2 bg-secondary bg-opacity-10 p-3 rounded-3">
                                @csrf
                                <label class="small text-muted mb-1">Motivo de pausa</label>
                                <select name="motivo" class="form-select bg-dark text-white border-0 mb-2">
                                    <option value="Almuerzo">Almuerzo</option>
                                    <option value="Personal">Personal</option>
                                </select>
                                <button class="btn btn-primary btn-sm w-100">INICIAR AHORA</button>
                            </form>
                        </div>

                        <form action="{{ route('becario.finalizarPausa') }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-success btn-action">
                                <i class="bi bi-check-circle"></i> FINALIZAR PAUSA
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection