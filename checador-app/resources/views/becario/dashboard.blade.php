@extends('layouts.app')

@section('content')
<style>
    body { background-color: #050505; color: #e2e8f0; }
    .glass-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.5);
    }
    .btn-action { 
        border-radius: 14px; 
        font-weight: 600; 
        text-transform: uppercase; 
        letter-spacing: 1px;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        border: none;
    }
    .btn-primary { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .btn-primary:hover { box-shadow: 0 0 20px rgba(59, 130, 246, 0.4); transform: translateY(-2px); }
    .btn-warning { background: #f59e0b; color: #000; }
    
    #live-clock { font-family: 'Courier New', monospace; font-weight: bold; color: #3b82f6; }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card glass-card text-white p-4">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="mb-0 fw-bold">Jornada</h4>
                        <span id="live-clock" class="small">00:00:00</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill">
                            <i class="bi bi-power"></i>
                        </button>
                    </form>
                </div>
                
                <div class="card-body p-0">
                    <div class="d-grid gap-3 mb-4">
                        <form action="{{ route('becario.checar') }}" method="POST">
                            @csrf
                            <button class="btn btn-primary btn-lg w-100 btn-action">
                                <i class="bi bi-play-fill me-2"></i> Registrar Entrada
                            </button>
                        </form>
                        <form action="{{ route('becario.salida') }}" method="POST">
                            @csrf
                            <button class="btn btn-warning btn-lg w-100 btn-action">
                                <i class="bi bi-stop-fill me-2"></i> Registrar Salida
                            </button>
                        </form>
                    </div>

                    <div class="position-relative my-4">
                        <hr class="border-secondary opacity-25">
                        <span class="position-absolute top-50 start-50 translate-middle bg-dark px-3 text-uppercase small" style="color: #64748b;">Pausas</span>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-light btn-action" data-bs-toggle="collapse" data-bs-target="#pausaMenu" style="border: 1px solid #334155;">
                            <i class="bi bi-cup-hot"></i> Gestionar Pausa
                        </button>
                        
                        <div class="collapse" id="pausaMenu">
                            <form action="{{ route('becario.iniciarPausa') }}" method="POST" class="mt-2 bg-slate-800 p-3 rounded-4" style="background: rgba(255,255,255,0.05);">
                                @csrf
                                <select name="motivo" class="form-select bg-dark text-white border-0 mb-3">
                                    <option value="Almuerzo">Almuerzo</option>
                                    <option value="Personal">Personal</option>
                                </select>
                                <button class="btn btn-primary w-100 btn-action btn-sm">Iniciar Pausa</button>
                            </form>
                        </div>

                        <form action="{{ route('becario.finalizarPausa') }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-success btn-action w-100" style="border-color: #10b981; color: #10b981;">
                                <i class="bi bi-check2-circle"></i> Finalizar Pausa
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateClock() {
        const now = new Date();
        document.getElementById('live-clock').innerText = now.toLocaleTimeString();
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endsection