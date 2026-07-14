<tr>

    {{-- Becario --}}
    <td>

        <strong>

            {{ $asistencia->user->name }}

        </strong>

        <br>

        <small class="text-secondary">

            {{ $asistencia->user->email }}

        </small>

    </td>

    {{-- Fecha --}}
    <td>

        {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}

    </td>

    {{-- Entrada --}}
    <td class="d-none d-md-table-cell">

      @if($asistencia->hora_entrada)

    {{ \Carbon\Carbon::parse($asistencia->hora_entrada)->format('h:i:s A') }}

@else

    --

@endif

    </td>

    {{-- Salida --}}
    <td class="d-none d-md-table-cell">

        @if($asistencia->hora_salida)

    {{ \Carbon\Carbon::parse($asistencia->hora_salida)->format('h:i:s A') }}

@else

    --

@endif
    </td>

    {{-- Número de pausas --}}
    <td class="d-none d-md-table-cell">

        <span class="badge bg-info">

            {{ $asistencia->pausas->count() }}

        </span>

    </td>

    {{-- Tiempo total pausas --}}
    <td class="d-none d-md-table-cell">

        {{ $asistencia->tiempoPausas() }}

    </td>

    {{-- Tiempo trabajado --}}
    <td>

        {{ $asistencia->formatoTiempo($asistencia->tiempoTrabajado()) }}

    </td>

    {{-- Horas extra --}}
    <td class="d-none d-md-table-cell">

        @if($asistencia->tiempoHorasExtras() > 0)

            <span class="text-success fw-bold">

                {{ $asistencia->horasExtrasTotalFormato() }}

            </span>

        @else

            <span class="text-secondary">

                00:00:00

            </span>

        @endif

    </td>


    {{-- Acción --}}
    <td>

    <a
    href="{{ route('admin.historial.reporte', $asistencia->user->id) }}"
    class="btn btn-outline-primary btn-sm"
>

    <i class="bi bi-file-earmark-text me-1"></i>

    Reporte

</a>
</td>

</tr>
