<div class="row g-4">

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

    <div class="col-lg-2">

        <div class="card bg-dark border-secondary">

            <div class="card-body text-center">

                <h3>

                    {{ $totalJornadas }}

                </h3>

                <small>Jornadas</small>

            </div>

        </div>

    </div>

    <div class="col-lg-2">

        <div class="card bg-dark border-secondary">

            <div class="card-body text-center">

                <h5>

                    {{ gmdate('H:i:s',$totalTrabajo) }}

                </h5>

                <small>Trabajadas</small>

            </div>

        </div>

    </div>

    <div class="col-lg-2">

        <div class="card bg-dark border-secondary">

            <div class="card-body text-center">

                <h5>

                    {{ gmdate('H:i:s',$totalPausas) }}

                </h5>

                <small>Pausas</small>

            </div>

        </div>

    </div>

    <div class="col-lg-2">

        <div class="card bg-dark border-secondary">

            <div class="card-body text-center">

                <h5 class="text-success">

                    {{ gmdate('H:i:s',$totalExtras) }}

                </h5>

                <small>Horas extra</small>

            </div>

        </div>

    </div>

</div>