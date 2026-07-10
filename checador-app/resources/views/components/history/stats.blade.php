@props(['asistencias'])

<div class="row g-3 mb-4">

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">

                <small class="text-secondary">
                    Registros
                </small>

                <h3 class="fw-bold text-white mt-2">
                    {{ $asistencias->total() }}
                </h3>

            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">

                <small class="text-secondary">
                    Becarios
                </small>

                <h3 class="fw-bold text-primary mt-2">
                    {{ $asistencias->pluck('user_id')->unique()->count() }}
                </h3>

            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">

                <small class="text-secondary">
                    Finalizados
                </small>

                <h3 class="fw-bold text-success mt-2">

                    {{ $asistencias->whereNotNull('hora_salida')->count() }}

                </h3>

            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">

                <small class="text-secondary">
                    En curso
                </small>

                <h3 class="fw-bold text-warning mt-2">

                    {{ $asistencias->whereNull('hora_salida')->count() }}

                </h3>

            </div>
        </div>
    </div>

</div>