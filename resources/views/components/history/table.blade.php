<div class="card border-0 shadow-sm">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            <i class="bi bi-table me-2"></i>
            Registros
        </h5>

        <span class="badge bg-secondary">

            {{ $asistencias->total() }}

            registros

        </span>

    </div>

    <div class="table-responsive">

        <table class="table table-dark table-hover align-middle mb-0">

            <thead>

<tr>

    <th>Becario</th>

    <th>Fecha</th>

   <th class="d-none d-md-table-cell">Entrada</th>

    <th class="d-none d-md-table-cell">Salida</th>

    <th class="d-none d-md-table-cell">Pausas</th>

    <th class="d-none d-md-table-cell">Tiempo pausa</th>

    <th>Tiempo trabajado</th>

    <th class="d-none d-md-table-cell">Horas extra</th>

    <th>Acciones</th>

</tr>

</thead>
            <tbody>

                @forelse($asistencias as $asistencia)

                    <x-history.row
                        :asistencia="$asistencia"
                    />

                @empty

                    <tr>

                        <td colspan="9" class="text-center py-5">

                            <i class="bi bi-folder-x display-6"></i>

                            <br><br>

                            No existen registros.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>
    @include('components.history.modal')

</div>