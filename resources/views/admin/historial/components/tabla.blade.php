<div class="card bg-dark border-secondary shadow-sm mt-4">

    <div class="card-header">

        <h5 class="mb-0">

            <i class="bi bi-calendar3 me-2"></i>

            Historial de jornadas

        </h5>

    </div>

    {{-- Tabla normal (tablet / desktop) --}}
    <div class="table-responsive d-none d-md-block">

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

    {{-- Tarjetas (móvil) --}}
    <div class="d-block d-md-none p-3">

        @forelse($asistencias as $asistencia)

            <div class="jornada-card mb-3">

                <div class="jornada-card-header">

                    <span>

                        <i class="bi bi-calendar3 me-1"></i>

                        {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}

                    </span>

                    <span class="jornada-badge-tiempo">

                        {{ $asistencia->formatoTiempo($asistencia->tiempoTrabajado()) }}

                    </span>

                </div>

                <div class="jornada-card-grid">

                    <div class="jornada-item jornada-border-right jornada-border-bottom">

                        <p class="jornada-label">Entrada</p>

                        <p class="jornada-value">

                            {{ $asistencia->hora_entrada
                                ? \Carbon\Carbon::parse($asistencia->hora_entrada)->format('h:i:s A')
                                : '--' }}

                        </p>

                    </div>

                    <div class="jornada-item jornada-border-bottom">

                        <p class="jornada-label">Salida</p>

                        <p class="jornada-value">

                            {{ $asistencia->hora_salida
                                ? \Carbon\Carbon::parse($asistencia->hora_salida)->format('h:i:s A')
                                : '--' }}

                        </p>

                    </div>

                    <div class="jornada-item jornada-border-right">

                        <p class="jornada-label">Pausas</p>

                        <p class="jornada-value">

                            {{ $asistencia->pausas->count() }}

                            <span class="jornada-value-sub">

                                ({{ $asistencia->tiempoPausas() }})

                            </span>

                        </p>

                    </div>

                    <div class="jornada-item">

                        <p class="jornada-label">Horas extra</p>

                        <p class="jornada-value {{ $asistencia->tiempoHorasExtras() ? 'text-warning' : 'text-secondary' }}">

                            @if($asistencia->tiempoHorasExtras())

                                {{ $asistencia->horasExtrasTotalFormato() }}

                            @else

                                00:00:00

                            @endif

                        </p>

                    </div>

                </div>

            </div>

        @empty

            <p class="text-center text-secondary py-4 mb-0">

                No existen registros.

            </p>

        @endforelse

    </div>

</div>

<style>

    .jornada-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .jornada-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.65rem 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        font-weight: 600;
        color: #fff;
    }

    .jornada-badge-tiempo {
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.2rem 0.6rem;
        border-radius: 0.375rem;
        background: rgba(25, 135, 84, 0.2);
        color: #75d8a4;
    }

    .jornada-card-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .jornada-item {
        padding: 0.6rem 1rem;
    }

    .jornada-border-right {
        border-right: 1px solid rgba(255, 255, 255, 0.1);
    }

    .jornada-border-bottom {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .jornada-label {
        margin: 0;
        font-size: 0.75rem;
        color: #adb5bd;
    }

    .jornada-value {
        margin: 2px 0 0;
        font-size: 0.9rem;
        color: #fff;
    }

    .jornada-value-sub {
        font-size: 0.75rem;
        color: #adb5bd;
    }

</style>