<div class="row g-4">

    {{-- Información del becario --}}
    <div class="col-lg-4">

        <div class="card bg-dark border-secondary h-100">

            <div class="card-body">

                <h4 class="fw-bold">

                    {{ $user->name }}

                </h4>

                <p class="text-secondary mb-0">

                    {{ $user->email }}

                </p>

            </div>

        </div>

    </div>

    {{-- Jornadas --}}
    <div class="col-lg-2">

        <div class="card bg-dark border-secondary h-100">

            <div class="card-body text-center">

                <h3 class="fw-bold">

                    {{ $resumen['jornadas'] }}

                </h3>

                <small class="text-secondary">

                    Jornadas

                </small>

            </div>

        </div>

    </div>

    {{-- Horas trabajadas --}}
    <div class="col-lg-2">

        <div class="card bg-dark border-secondary h-100">

            <div class="card-body text-center">

                <h5 class="fw-bold text-primary">

                    {{ $resumen['horas_trabajadas'] }}

                </h5>

                <small class="text-secondary">

                    Trabajadas

                </small>

            </div>

        </div>

    </div>

    {{-- Tiempo en pausa --}}
    <div class="col-lg-2">

        <div class="card bg-dark border-secondary h-100">

            <div class="card-body text-center">

                <h5 class="fw-bold text-warning">

                    {{ $resumen['tiempo_pausa'] }}

                </h5>

                <small class="text-secondary">

                    Pausas

                </small>

            </div>

        </div>

    </div>

    {{-- Horas extra --}}
    <div class="col-lg-2">

        <div class="card bg-dark border-secondary h-100">

            <div class="card-body text-center">

                <h5 class="fw-bold text-success">

                    {{ $resumen['horas_extra'] }}

                </h5>

                <small class="text-secondary">

                    Horas extra

                </small>

            </div>

        </div>

    </div>

</div>