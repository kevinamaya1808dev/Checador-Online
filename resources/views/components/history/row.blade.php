<tr class="border-t border-white/10 hover:bg-white/5">

    {{-- Becario --}}
    <td class="px-4 py-3">

        <strong class="text-white">

            {{ $asistencia->user->name }}

        </strong>

        <br>

        <small class="text-gray-400">

            {{ $asistencia->user->email }}

        </small>

    </td>

    {{-- Fecha --}}
    <td class="px-4 py-3">

        {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}

    </td>

    {{-- Entrada --}}
    <td class="hidden md:table-cell px-4 py-3">

      @if($asistencia->hora_entrada)

    {{ \Carbon\Carbon::parse($asistencia->hora_entrada)->format('h:i:s A') }}

@else

    --

@endif

    </td>

    {{-- Salida --}}
    <td class="hidden md:table-cell px-4 py-3">

        @if($asistencia->hora_salida)

    {{ \Carbon\Carbon::parse($asistencia->hora_salida)->format('h:i:s A') }}

@else

    --

@endif
    </td>

    {{-- Número de pausas --}}
    <td class="hidden md:table-cell px-4 py-3">

        <span class="inline-flex items-center justify-center rounded-full bg-cyan-600 text-white text-sm px-2.5 py-1">

            {{ $asistencia->pausas->count() }}

        </span>

    </td>

    {{-- Tiempo total pausas --}}
    <td class="hidden md:table-cell px-4 py-3">

        {{ $asistencia->tiempoPausas() }}

    </td>

    {{-- Tiempo trabajado --}}
    <td class="px-4 py-3">

        {{ $asistencia->formatoTiempo($asistencia->tiempoTrabajado()) }}

    </td>

    {{-- Horas extra --}}
    <td class="hidden md:table-cell px-4 py-3">

        @if($asistencia->tiempoHorasExtras() > 0)

            <span class="text-green-500 font-bold">

                {{ $asistencia->horasExtrasTotalFormato() }}

            </span>

        @else

            <span class="text-gray-400">

                00:00:00

            </span>

        @endif

    </td>


    {{-- Acción --}}
    <td class="px-4 py-3">

    <a
    href="{{ route('admin.historial.reporte', $asistencia->user->id) }}"
    class="inline-flex items-center gap-1 border border-blue-500 text-blue-400 hover:bg-blue-500/10 rounded-lg px-3 py-1.5 text-sm transition-colors no-underline"
>

    <i class="bi bi-file-earmark-text mr-1"></i>

    Reporte

</a>
</td>

</tr>