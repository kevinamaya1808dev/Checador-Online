<div class="rounded-2xl bg-slate-800/50 border border-white/[0.08] shadow-sm overflow-hidden">

    <div class="px-4 py-3 border-b border-white/[0.08]">
        <h5 class="font-bold text-base mb-0">
            <i class="bi bi-calendar3 mr-2 text-blue-400"></i>
            Historial de jornadas
        </h5>
    </div>

    {{-- Tabla normal (tablet / desktop) --}}
    <div class="hidden md:block overflow-x-auto">

        <table class="w-full text-sm text-left">

            <thead>
                <tr class="border-b border-white/[0.08] text-slate-400 uppercase text-xs tracking-wide">
                    <th class="px-4 py-3 font-semibold">Fecha</th>
                    <th class="px-4 py-3 font-semibold">Entrada</th>
                    <th class="px-4 py-3 font-semibold">Salida</th>
                    <th class="px-4 py-3 font-semibold">Pausas</th>
                    <th class="px-4 py-3 font-semibold">Tiempo pausa</th>
                    <th class="px-4 py-3 font-semibold">Tiempo trabajado</th>
                    <th class="px-4 py-3 font-semibold">Horas extra</th>
                </tr>
            </thead>

            <tbody>
            @forelse($asistencias as $asistencia)
                <tr class="border-b border-white/[0.06] hover:bg-white/[0.04] transition-colors">

                    <td class="px-4 py-3">
                        {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $asistencia->hora_entrada
                            ? \Carbon\Carbon::parse($asistencia->hora_entrada)->format('h:i:s A')
                            : '--' }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $asistencia->hora_salida
                            ? \Carbon\Carbon::parse($asistencia->hora_salida)->format('h:i:s A')
                            : '--' }}
                    </td>

                    <td class="px-4 py-3">
                        <span class="inline-flex items-center justify-center rounded-md bg-sky-500/20 text-sky-300 text-xs font-semibold px-2 py-1">
                            {{ $asistencia->pausas->count() }}
                        </span>
                    </td>

                    <td class="px-4 py-3">
                        {{ $asistencia->tiempoPausas() }}
                    </td>

                    <td class="px-4 py-3 font-mono">
                        {{ $asistencia->formatoTiempo($asistencia->tiempoTrabajado()) }}
                    </td>

                    <td class="px-4 py-3">
                        @if($asistencia->tiempoHorasExtras())
                            <span class="text-emerald-400 font-bold">
                                {{ $asistencia->horasExtrasTotalFormato() }}
                            </span>
                        @else
                            <span class="text-slate-500">
                                00:00:00
                            </span>
                        @endif
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-6 text-slate-400">
                        No existen registros.
                    </td>
                </tr>
            @endforelse
            </tbody>

        </table>

    </div>

    {{-- Tarjetas (móvil) --}}
    <div class="block md:hidden p-3 space-y-3">

        @forelse($asistencias as $asistencia)

            <div class="rounded-xl bg-white/[0.03] border border-white/[0.08] overflow-hidden">

                <div class="flex justify-between items-center px-4 py-2.5 border-b border-white/[0.08] font-semibold text-white">
                    <span>
                        <i class="bi bi-calendar3 mr-1 text-blue-400"></i>
                        {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}
                    </span>
                    <span class="text-sm font-semibold rounded-md bg-emerald-600/20 text-emerald-300 px-2.5 py-1">
                        {{ $asistencia->formatoTiempo($asistencia->tiempoTrabajado()) }}
                    </span>
                </div>

                <div class="grid grid-cols-2">

                    <div class="px-4 py-2.5 border-r border-b border-white/[0.08]">
                        <p class="m-0 text-xs text-slate-400">Entrada</p>
                        <p class="mt-0.5 mb-0 text-sm text-white">
                            {{ $asistencia->hora_entrada
                                ? \Carbon\Carbon::parse($asistencia->hora_entrada)->format('h:i:s A')
                                : '--' }}
                        </p>
                    </div>

                    <div class="px-4 py-2.5 border-b border-white/[0.08]">
                        <p class="m-0 text-xs text-slate-400">Salida</p>
                        <p class="mt-0.5 mb-0 text-sm text-white">
                            {{ $asistencia->hora_salida
                                ? \Carbon\Carbon::parse($asistencia->hora_salida)->format('h:i:s A')
                                : '--' }}
                        </p>
                    </div>

                    <div class="px-4 py-2.5 border-r border-white/[0.08]">
                        <p class="m-0 text-xs text-slate-400">Pausas</p>
                        <p class="mt-0.5 mb-0 text-sm text-white">
                            {{ $asistencia->pausas->count() }}
                            <span class="text-xs text-slate-400">
                                ({{ $asistencia->tiempoPausas() }})
                            </span>
                        </p>
                    </div>

                    <div class="px-4 py-2.5">
                        <p class="m-0 text-xs text-slate-400">Horas extra</p>
                        <p class="mt-0.5 mb-0 text-sm {{ $asistencia->tiempoHorasExtras() ? 'text-amber-400' : 'text-slate-500' }}">
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

            <p class="text-center text-slate-400 py-6 mb-0">
                No existen registros.
            </p>

        @endforelse

    </div>

</div>