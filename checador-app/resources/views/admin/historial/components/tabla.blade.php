<div class="card bg-dark border-secondary shadow-sm mt-4">

    <div class="card-header">

        <h5 class="mb-0">

            <i class="bi bi-calendar3 me-2"></i>

            Historial de jornadas

        </h5>

    </div>

    <div class="table-responsive">

        <table class="table table-dark table-hover align-middle mb-0">

            <thead>

                <tr>

                    <th>Fecha</th>

                    <th>Entrada</th>

                    <th>Salida</th>

                    <th>Pausas</th>

                    <th>Tiempo pausa</th>

                    <th>Tiempo trabajado</th>

                    <th>Horas extra</th>

                </tr>

            </thead>

            <tbody>

            @forelse($asistencias as $asistencia)

                <tr>

                    <td>

                        {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}

                    </td>

                    <td>

                        {{ $asistencia->hora_entrada
                            ? \Carbon\Carbon::parse($asistencia->hora_entrada)->format('h:i:s A')
                            : '--' }}

                    </td>

                    <td>

                        {{ $asistencia->hora_salida
                            ? \Carbon\Carbon::parse($asistencia->hora_salida)->format('h:i:s A')
                            : '--' }}

                    </td>

                    <td>

                        <span class="badge bg-info">

                            {{ $asistencia->pausas->count() }}

                        </span>

                    </td>

                    <td>

                        {{ $asistencia->tiempoPausas() }}

                    </td>

                    <td>

                        {{ $asistencia->formatoTiempo($asistencia->tiempoTrabajado()) }}

                    </td>

                    <td>

                        @if($asistencia->tiempoHorasExtras())

                            <span class="text-success fw-bold">

                                {{ $asistencia->horasExtrasTotalFormato() }}

                            </span>

                        @else

                            <span class="text-secondary">

                                00:00:00

                            </span>

                        @endif

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7" class="text-center py-4">

                        No existen registros.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>